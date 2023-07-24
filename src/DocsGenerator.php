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
use Symfony\Component\Finder\Finder;

class DocsGenerator
{
	protected array $directories;

	public function __construct(string $endpointDirectory, string $schemaDirectory, string $endpointNamespace, string $schemaNamespace)
	{
		$this->directories = [
			$this->normalizeDir($endpointDirectory) => $endpointNamespace,
			$this->normalizeDir($schemaDirectory) => $schemaNamespace,
		];
	}

	protected function normalizeDir(string $dir)
	{
		return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $dir);
	}

	public function generate()
	{
		$endpoints = $this->fetchEndpoints();

		dd('TODO generate documentation files');
	}

	public function fetchEndpoints() : array
	{
		$files = $this->getFiles($directories = array_keys($this->directories));
		$files = array_map(function (\SplFileInfo $fileInfo) use ($directories) : ?string {
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

		/**
		 * @var Endpoint[] $endpoints
		 */
		$endpoints = $schemas = $apiVersions = [];
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

				if ( array_key_exists($schemaName = $schemaInstance->name, $schemas) ) {
					$this->log('warning', 'Overwrite schema '.$schemaName);
				}

				$schemas[$schemaName] = $schemaInstance;
				$hasSchema[] = $schemaName;
			}

			// looking for property attributes
			if ( !empty($hasSchema) ) {
				foreach ( $reflectionClass->getAttributes(Property::class) as $pKey => $property ) {
					$propertyInstance = $property->newInstance();
					foreach ( $hasSchema as $schema ) {
						$schemas[$schema]->addProperty($propertyInstance);
					}
				}
			}

			// looking for api versions
			foreach ( $reflectionClass->getAttributes(ApiVersion::class) as $_ => $versionAttribute ) {
				/** @var ApiVersion $version */
				$version = $versionAttribute->newInstance();
				$apiVersions[$version->internalName ?? $version->version] = $version;
			}

			// looking for endpoints
			foreach ( $reflectionClass->getMethods() as $rKey => $reflectionProperty ) {
				$summaries = $this->test($reflectionProperty->getAttributes(Summary::class));
				$tokenTypes = $this->test($reflectionProperty->getAttributes(RequireAnyTokenType::class), true);
				$scopes = $this->test(array_merge(
					$reflectionProperty->getAttributes(RequireScope::class),
					$reflectionProperty->getAttributes(RequireAnyScope::class),
				), true);
				$properties = $this->test($reflectionProperty->getAttributes(Property::class), true);

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

					$endpoints[] = $endpointInstance;
				}
			}
		}

		$finalEndpoints = [];
		foreach ( $endpoints as $endpoint ) {
			if ( !isset($apiVersions[$endpoint->version]) ) {
				$this->log('error', sprintf('Api version %s not found', $endpoint->version));
			}
			else {
				$endpoint->setVersion($apiVersions[$endpoint->version]);
				$finalEndpoints[$endpoint->version][] = $endpoint;
			}

			if ( $endpoint->schema instanceof Schema && empty($endpoint->schema->properties) ) {
				if ( array_key_exists($endpoint->schema->name, $schemas) ) {
					$endpoint->schema = $schemas[$endpoint->schema->name];
				}
				else {
					$this->log('warning', sprintf('Schema %s does\'nt exists.', $endpoint->schema->name));
				}
			}
		}

		return $finalEndpoints;
	}

	protected function test(array $abc, bool $asArray = false) : array
	{
		$group = [];
		foreach ( $abc as $key => $value ) {
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
}