<?php

namespace Derpierre65\DocsGenerator;

use Derpierre65\DocsGenerator\Attributes\ApiVersion;
use Derpierre65\DocsGenerator\Attributes\Endpoint;
use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\RequireAnyScope;
use Derpierre65\DocsGenerator\Attributes\RequireAnyTokenType;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\Attributes\Summary;
use Derpierre65\DocsGenerator\Helpers\RequireScope;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DocsGenerator
{
	protected array $directories;

	protected array $endpoints = [];

	/** @var ApiVersion[] */
	protected array $apiVersions = [];

	protected array $schemes = [];

	public function __construct(array $scanDirectories, public readonly string $docsDirectory)
	{
		$this->directories = [];
		foreach ( $scanDirectories as $directory => $namespace ) {
			$this->directories[$this->normalizeDir($directory)] = $this->normalizeNamespace($namespace);
		}
	}

	public function fetch() : static
	{
		$files = $this->getFiles($directories = array_keys($this->directories));
		$files = array_map(function (SplFileInfo $fileInfo) use ($directories) : ?string {
			foreach ( $directories as $directory ) {
				if ( str_starts_with($this->normalizeDir($fileInfo->getPathname()), $directory) ) {
					return $this->directories[$directory].str_replace('/', '\\', substr(
							$fileInfo->getPathname(),
							strlen($directory),
							strlen($fileInfo->getPathname()) - strlen($directory) - 4
						));
				}
			}

			return null;
		}, $files);

		$this->endpoints = $this->apiVersions = $this->schemes = [];

		foreach ( $files as $key => $className ) {
			if ( !$className ) {
				continue;
			}

			$reflectionClass = new ReflectionClass($className);

			// looking for schema attributes
			$hasSchema = [];
			foreach ( $reflectionClass->getAttributes(Schema::class) as $aKey => $schema ) {
				/** @var Schema $schemaInstance */
				$schemaInstance = $schema->newInstance();

				if ( array_key_exists($schemaName = $schemaInstance->name, $this->schemes) ) {
					$this->log('warning', 'Overwrite schema '.$schemaName);
				}

				$this->schemes[$schemaName] = $schemaInstance;
				$hasSchema[] = $schemaName;
			}

			// looking for property attributes
			if ( !empty($hasSchema) ) {
				foreach ( $reflectionClass->getAttributes(Property::class) as $pKey => $property ) {
					$propertyInstance = $property->newInstance();
					foreach ( $hasSchema as $schema ) {
						$this->schemes[$schema]->addProperty($propertyInstance);
					}
				}
			}

			// looking for api versions
			foreach ( $reflectionClass->getAttributes(ApiVersion::class) as $_ => $versionAttribute ) {
				/** @var ApiVersion $version */
				$version = $versionAttribute->newInstance();
				$this->apiVersions[$version->internalName ?? $version->version] = $version;
			}

			// looking for endpoints
			foreach ( $reflectionClass->getMethods() as $rKey => $reflectionProperty ) {
				$summaries = $this->fetchAttributes($reflectionProperty->getAttributes(Summary::class));
				$tokenTypes = $this->fetchAttributes($reflectionProperty->getAttributes(RequireAnyTokenType::class), true);
				$scopes = $this->fetchAttributes(array_merge(
					$reflectionProperty->getAttributes(RequireScope::class),
					$reflectionProperty->getAttributes(RequireAnyScope::class),
				), true);
				$properties = $this->fetchAttributes($reflectionProperty->getAttributes(Property::class), true);

				foreach ( $reflectionProperty->getAttributes(Endpoint::class) as $subKey => $endpoint ) {
					/** @var Endpoint $endpointInstance */
					$endpointInstance = $endpoint->newInstance();
					// TODO maybe need to merge _ operation scopes/properties?
					// TODO maybe make operationId possible to an array?
					$endpointInstance->addScopes($scopes[$endpointInstance->operationId] ?? $scopes['_'] ?? []);
					$endpointInstance->setSummary($summaries[$endpointInstance->operationId] ?? $summaries['_'] ?? null);

					if ( !empty($propertyList = $properties[$endpointInstance->operationId] ?? $properties['_'] ?? []) ) {
						$endpointInstance->setProperties($propertyList);
					}

					if ( $tokenType = $tokenTypes[$endpointInstance->operationId] ?? $tokenTypes['_'] ?? null ) {
						$endpointInstance->setTokenType($tokenType);
					}

					$this->endpoints[] = $endpointInstance;
				}
			}
		}

		$finalEndpoints = [];
		foreach ( $this->endpoints as $endpoint ) {
			if ( !isset($this->apiVersions[$endpoint->version]) ) {
				$this->log('error', sprintf('Api version %s not found', $endpoint->version));
			}
			else {
				$endpoint->setVersion($this->apiVersions[$endpoint->version]);
				$finalEndpoints[$endpoint->version][] = $endpoint;
			}

			if ( $endpoint->schema instanceof Schema && empty($endpoint->schema->properties) ) {
				if ( array_key_exists($endpoint->schema->name, $this->schemes) ) {
					$endpoint->schema = $this->schemes[$endpoint->schema->name];
				}
				else {
					$this->log('warning', sprintf('Schema %s does\'nt exists.', $endpoint->schema->name));
				}
			}
		}

		$this->endpoints = $finalEndpoints;

		return $this;
	}

	public function saveApiVersionJson() : void
	{
		$jsonContent = json_encode(array_map(fn(ApiVersion $version) => $version->toArray(), $this->getApiVersions()), JSON_PRETTY_PRINT);
		if ( !file_exists($this->docsDirectory) ) {
			$this->log('error', 'src-docs directory doesn\'t exist.');

			return;
		}

		if ( !file_exists($dirname = dirname($jsonFile = $this->getApiJsonDirectory())) ) {
			mkdir($dirname, recursive: true);
		}
		file_put_contents($jsonFile, $jsonContent);
	}

	public function generate() : void
	{
		if ( empty($this->endpoints) ) {
			return;
		}

		dd('TODO generate documentation files');
	}

	protected function fetchAttributes(array $attributes, bool $asArray = false) : array
	{
		$group = [];
		foreach ( $attributes as $key => $value ) {
			$instance = $value->newInstance();
			$index = $instance->operationId ?? '_';
			if ( $asArray ) {
				$group[$index][] = $instance;
			}
			else {
				if ( array_key_exists($index, $group) ) {
					$this->log('warning', sprintf('Duplicate attribute %s', $instance->operationId ?? ''));
				}

				$group[$index] = $instance;
			}
		}

		return $group;
	}

	protected function normalizeDir(string $dir) : array|string
	{
		return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $dir);
	}

	protected function normalizeNamespace(string $namespace) : string
	{
		return rtrim($namespace, '\\');
	}

	protected function log(string $type, string $message) : void
	{
		echo '['.$type.'] '.$message.PHP_EOL;
	}

	protected function getFiles($directory, $hidden = false) : array
	{
		return iterator_to_array(
			Finder::create()->files()->ignoreDotFiles(!$hidden)->in($directory)->sortByName(),
			false
		);
	}

	//<editor-fold desc="getters">
	public function getApiJsonDirectory() : string
	{
		return $this->docsDirectory.'/src/.vuepress/api.json';
	}

	public function getEndpoints() : array
	{
		return $this->endpoints;
	}

	public function getApiVersions() : array
	{
		return $this->apiVersions;
	}

	public function getSchemes() : array
	{
		return $this->schemes;
	}
	//</editor-fold>
}