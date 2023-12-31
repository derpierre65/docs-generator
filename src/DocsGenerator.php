<?php

namespace Derpierre65\DocsGenerator;

use Derpierre65\DocsGenerator\Attributes\ApiVersion;
use Derpierre65\DocsGenerator\Attributes\BodyParameter;
use Derpierre65\DocsGenerator\Attributes\Endpoint;
use Derpierre65\DocsGenerator\Attributes\EndpointResource;
use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\QueryParameter;
use Derpierre65\DocsGenerator\Attributes\RequireAnyScope;
use Derpierre65\DocsGenerator\Attributes\RequireAnyTokenType;
use Derpierre65\DocsGenerator\Attributes\RequireScope;
use Derpierre65\DocsGenerator\Attributes\Response;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\Attributes\Summary;
use Derpierre65\DocsGenerator\Generator\MarkdownGenerator;
use Derpierre65\DocsGenerator\Generator\Plugin\PluginManager;
use Derpierre65\DocsGenerator\Generator\Traits\Helpers;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DocsGenerator
{
	use Helpers;

	protected array $scanDirectories = [];

	protected array $endpoints = [];

	/** @var ApiVersion[] */
	protected array $apiVersions = [];

	protected array $schemes = [];

	protected array $config;

	protected PluginManager $pluginManager;

	public function __construct($config)
	{
		$this->config = $config;

		// initialize scan directories
		foreach ( $config['scan_directories'] as $directory => $namespace ) {
			$this->scanDirectories[$this->normalizeDir($directory)] = $this->normalizeNamespace($namespace);
		}

		// initialize plugins
		$this->pluginManager = new PluginManager();
		foreach ( $this->config['plugins'] as $pluginClass ) {
			$this->pluginManager->registerPlugin(new $pluginClass());
		}
	}

	public function generate() : void
	{
		$this->pluginManager->fireAction(self::class, 'beforeGenerate');

		$this
			->fetch()
			->generateApiJson()
			->generateMarkdownFiles();
	}

	public function fetch() : static
	{
		$this->pluginManager->fireAction(self::class, 'beforeFetchFiles');

		$files = $this->getFiles($directories = array_keys($this->scanDirectories));
		$files = array_map(function (SplFileInfo $fileInfo) use ($directories) : ?string {
			foreach ( $directories as $directory ) {
				if ( str_starts_with($this->normalizeDir($fileInfo->getPathname()), $directory) ) {
					return $this->scanDirectories[$directory].str_replace('/', '\\', substr(
							$fileInfo->getPathname(),
							strlen($directory),
							strlen($fileInfo->getPathname()) - strlen($directory) - 4
						));
				}
			}

			return null;
		}, $files);

		$data = ['files' => $files];
		$this->pluginManager->fireAction(self::class, 'afterFetchFiles', $data);
		$files = $data['files'];

		$this->endpoints = $this->apiVersions = $this->schemes = [];

		// look for all existing schemes in all files before scan for endpoints
		foreach ( $files as $key => $className ) {
			if ( !$className ) {
				continue;
			}

			$reflectionClass = new ReflectionClass($className);

			// looking for api versions
			foreach ( $reflectionClass->getAttributes(ApiVersion::class) as $_ => $versionAttribute ) {
				/** @var ApiVersion $version */
				$version = $versionAttribute->newInstance();
				$this->apiVersions[$version->version] = $version;
			}

			// looking for schema
			$hasSchema = [];
			foreach ( $reflectionClass->getAttributes(Schema::class) as $aKey => $schema ) {
				/** @var Schema $schemaInstance */
				$schemaInstance = $schema->newInstance();

				if ( array_key_exists($schemaName = $schemaInstance->name, $this->schemes) ) {
					$this->log('warning', 'Overwrite schema '.$schemaName);
				}

				$this->schemes[$schemaName] = $schemaInstance;
				$hasSchema[] = $schemaInstance->name;
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
		}

		// now we have all schemes, lets scan the project for endpoints
		foreach ( $files as $key => $className ) {
			if ( !$className ) {
				continue;
			}

			$reflectionClass = new ReflectionClass($className);
			$classEndpointResource = $this->fetchAttributes($reflectionClass->getAttributes(EndpointResource::class));

			// looking for endpoints
			foreach ( $reflectionClass->getMethods() as $rKey => $reflectionProperty ) {
				$summaries = $this->fetchAttributes($reflectionProperty->getAttributes(Summary::class));
				$tokenTypes = $this->fetchAttributes($reflectionProperty->getAttributes(RequireAnyTokenType::class), true);
				$scopes = $this->fetchAttributes(array_merge(
					$reflectionProperty->getAttributes(RequireScope::class),
					$reflectionProperty->getAttributes(RequireAnyScope::class),
				), true);
				$properties = $this->fetchAttributes($reflectionProperty->getAttributes(Property::class), true);
				$queryParameters = $this->fetchAttributes($reflectionProperty->getAttributes(QueryParameter::class), true);
				$bodyParameters = $this->fetchAttributes($reflectionProperty->getAttributes(BodyParameter::class), true);
				$endpointResource = $this->fetchAttributes($reflectionProperty->getAttributes(EndpointResource::class));
				$responses = $this->fetchAttributes($reflectionProperty->getAttributes(Response::class));

				foreach ( $reflectionProperty->getAttributes(Endpoint::class) as $subKey => $endpoint ) {
					/** @var Endpoint $endpointInstance */
					$endpointInstance = $endpoint->newInstance();

					// TODO better merge, allow multiple schemes as default response
					$operationId = $endpointInstance->operationId;
					$response = $responses[$operationId] ?? $responses['_'] ?? null;
					if ( $response && $response->properties instanceof Schema ) {
						$schema = $response->properties;
						$globalSchema = $this->schemes[$schema->name];
						$schema->properties = $globalSchema->properties;
						$endpointInstance->schema = $schema;
					}

					// TODO maybe need to merge _ operation scopes/properties?
					// TODO maybe make operationId possible to an array?
					$endpointInstance
						->addScopes($scopes[$operationId] ?? $scopes['_'] ?? [])
						->setQueryParameters($queryParameters[$operationId] ?? $queryParameters['_'] ?? [])
						->setBodyParameters($bodyParameters[$operationId] ?? $bodyParameters['_'] ?? [])
						->setSummary($summaries[$operationId] ?? $summaries['_'] ?? null)
						->setResource($endpointResource[$operationId] ?? $endpointResource['_'] ?? $classEndpointResource['_'] ?? null);

					if ( !empty($propertyList = $properties[$operationId] ?? $properties['_'] ?? []) ) {
						$endpointInstance->setProperties($propertyList);
					}

					if ( $tokenType = $tokenTypes[$operationId] ?? $tokenTypes['_'] ?? null ) {
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
				continue;
			}

			$endpointIdentifier = implode('-', [$endpoint->version, $endpoint->method->value, $endpoint->path]);
			if ( !$endpoint->getResource() ) {
				$this->log('error', sprintf('Endpoint %s has no resource', $endpointIdentifier));
				continue;
			}

			if ( !$endpoint->getSummary() ) {
				$this->log('error', 'Found endpoint without an summary: '.$endpointIdentifier);
				continue;
			}

			$endpoint->setVersion($this->apiVersions[$endpoint->version]);
			$finalEndpoints[$endpoint->version][] = $endpoint;

			// TODO i don't know why, currently it's not required
			// if ( $endpoint->schema instanceof Schema && empty($endpoint->schema->properties) ) {
			// 	if ( array_key_exists($endpoint->schema->name, $this->schemes) ) {
			// 		$endpoint->schema = $this->schemes[$endpoint->schema->name];
			// 	}
			// 	else {
			// 		$this->log('warning', sprintf('Schema %s does\'nt exists.', $endpoint->schema->name));
			// 	}
			// }
		}

		$this->endpoints = $finalEndpoints;

		// get anchors
		foreach ( $this->endpoints as $apiVersion => $endpoints ) {
			$anchors = [];
			/** @var Endpoint $endpoint */
			foreach ( $endpoints as $key => $endpoint ) {
				$resource = $endpoint->getResource();
				$summary = $endpoint->getSummary();
				$anchor = $summary->getAnchor();

				$anchorNumber = 0;
				while ( !empty($anchors[$resource->name]) && in_array($anchor, $anchors[$resource->name]) ) {
					$anchorNumber++;
					$anchor = $summary->getAnchor().'-'.$anchorNumber;
				}

				$endpoint->setAnchor($anchor);
				$anchors[$resource->name][] = $anchor;
			}
		}

		return $this;
	}

	public function generateApiJson() : static
	{
		$jsonContent = json_encode(array_map(fn(ApiVersion $version) => $version->toArray(), $this->getApiVersions()), JSON_PRETTY_PRINT);
		if ( !file_exists($this->config['paths']['docs']) ) {
			$this->log('error', 'src-docs directory doesn\'t exist.');

			return $this;
		}

		if ( !file_exists($dirname = dirname($jsonFile = $this->getApiJsonDirectory())) ) {
			mkdir($dirname, recursive: true);
		}
		file_put_contents($jsonFile, $jsonContent);

		return $this;
	}

	public function generateMarkdownFiles() : void
	{
		$generator = new MarkdownGenerator($this, $this->config);
		$generator->generateResourcesList();
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
		return $this->config['paths']['docs'].'/src/.vuepress/api.json';
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