<?php

namespace Derpierre65\DocsGenerator\Generator;

use Derpierre65\DocsGenerator\Attributes\ApiVersion;
use Derpierre65\DocsGenerator\Attributes\Endpoint;
use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\DocsGenerator;
use Derpierre65\DocsGenerator\Enums\Generator\TableType;
use Derpierre65\DocsGenerator\Generator\Traits\Files;
use Derpierre65\DocsGenerator\Generator\Traits\TemplateSystem;

class MarkdownGenerator
{
	use Files;
	use TemplateSystem;

	public function __construct(protected DocsGenerator $generator, protected array $config)
	{
	}

	public function generateResourcesList() : static
	{
		foreach ( $this->generator->getEndpoints() as $version => $endpoints ) {
			if ( $this->config['options']['clear_directory_before_generate'] ) {
				$this->rmdir($this->config['paths']['docs'].'/src/'.$version);
			}

			$apiVersion = $this->generator->getApiVersions()[$version];
			if ( $this->config['options']['generate_separate_resource_pages'] ) {
				$this->generateIndexResourceList($apiVersion, $endpoints);
			}
			$this->generateResourceIndex($apiVersion, $endpoints);
		}

		return $this;
	}

	protected function generateResourceIndex(ApiVersion $version, array $endpoints) : void
	{
		$html = $resources = [];

		$data = [
			'endpointTemplate' => $this->getTemplate('endpoint'),
			'indexTemplate' => $this->getTemplate('resource-index'),
			'responseEntryTemplate' => $this->getTemplate('endpoint-response-entry'),
			'responseIndexTemplate' => $this->getTemplate('endpoint-response-index'),
			'endpointHeaderLevel' => str_repeat('#', $this->config['options']['generate_separate_resource_pages'] ? 2 : 3),
			'endpointSubHeaderLevel' => str_repeat('#', $this->config['options']['generate_separate_resource_pages'] ? 3 : 4),
			'resourceHeaderLevel' => str_repeat('#', $this->config['options']['generate_separate_resource_pages'] ? 1 : 2),
		];

		// $this->pluginManager->fireAction(self::class, 'beforeGenerateResourceIndex', $data);

		/** @var Endpoint $endpoint */
		foreach ( $endpoints as $index => $endpoint ) {
			$summary = $endpoint->getSummary();
			$resource = $endpoint->getResource();

			if ( !isset($html[$resource->name]) ) {
				$html[$resource->name] = '';
				$resources[$resource->name] = $resource;
			}

			$html[$resource->name] .= $this->replaceTemplateVariables($this->replaceTemplateVariables($data['endpointTemplate'], [
				'endpoint_title' => $summary->title,
				'endpoint_summary' => $summary->summary,
				'endpoint_method' => $endpoint->method->value,
				'endpoint_url' => $endpoint->getPath(),
				'endpoint_authorization' => '', // TODO oauth authorization information
				'query_parameters' => '', // TODO
				'body_parameters' => '', // TODO
				'response_body' => $this->getTable(
					TableType::RESPONSE,
					$data['responseIndexTemplate'],
					$data['responseEntryTemplate'],
					$endpoint->getProperties(),
					0,
					$this->config['options']['resolve_schema_in_response']
				),
				'response_codes' => '', // TODO
				'response_example' => json_encode($this->buildResponseExample($endpoint->getProperties()), JSON_PRETTY_PRINT), // TODO
			]), [
				'endpoint_header_level' => $data['endpointHeaderLevel'],
				'endpoint_sub_header_level' => $data['endpointSubHeaderLevel'],
			]);
		}

		$siteConfig = [
			'sidebar' => ['./README.md'],
			'sidebarDepth' => $this->config['options']['generate_separate_resource_pages'] ? 1 : 2,
		];

		$header = '';
		if ( $this->config['options']['generate_separate_resource_pages'] ) {
			foreach ( $resources as $resourceName => $resource ) {
				$siteConfig['title'] = $resourceName;

				$this->saveFile(
					$this->config['paths']['docs'].'/src/'.$version->version.'/'.$resource->getPathURL().'/README.md',
					$this->replaceTemplateVariables($data['indexTemplate'], [
						'site_config' => $this->buildSiteConfig($siteConfig),
						'resource_name' => $resourceName,
						'resource_header_level' => $data['resourceHeaderLevel'],
						'endpoints' => $html[$resource->name],
					])
				);
			}
		}
		else {
			$indexHtml = '';
			if ( $this->config['options']['append_resources_table_in_single_page'] ) {
				$header .= $this->generateIndexResourceList($version, $endpoints, true);
			}
			else {
				$siteConfig['title'] = ucfirst($version->getDisplayName());
			}

			$siteConfigYml = $this->buildSiteConfig($siteConfig);

			foreach ( $resources as $resourceName => $resource ) {
				$indexHtml .= $this->replaceTemplateVariables($data['indexTemplate'], [
					'site_config' => $siteConfigYml,
					'header' => $header,
					'resource_name' => $resourceName,
					'resource_header_level' => $data['resourceHeaderLevel'],
					'endpoints' => $html[$resource->name],
				]);

				$header = $siteConfigYml = ''; // reset header and config yml
			}

			$this->saveFile($this->config['paths']['docs'].'/src/'.$version->version.'/README.md', $this->replaceTemplateVariables($indexHtml, [
				'header' => '',
			]));
		}
	}

	protected function getTable(TableType $type, string $header, string $entry, array $properties, $level = 0, bool $shouldResolveSchema = false) : string
	{
		if ( empty($properties) ) {
			return '';
		}

		$html = '';

		/** @var Property $property */
		foreach ( $properties as $property ) {
			if ( $property->example instanceof Schema ) {
				// append original property
				$html .= $this->renderColumnProperty($type, $entry, $property, $level, !$shouldResolveSchema);

				if ( $shouldResolveSchema ) {
					$properties = $this->getSchemaProperties($property->example);
					$html .= $this->getTable($type, '', $entry, $properties, $level + 1);
				}
			}
			elseif ( is_array($property->example) ) {
				$renderProperties = array_filter($property->example, fn(Property $subProperty) => $subProperty instanceof Property);

				if ( !empty($renderProperties) ) {
					$html .= $this->renderColumnProperty($type, $entry, $property, $level);
					$html .= $this->getTable($type, '', $entry, $renderProperties, $level + 1);
				}
			}
			else {
				$html .= $this->renderColumnProperty($type, $entry, $property, $level);
			}
		}

		if ( $level === 0 ) {
			if ( empty($html) ) {
				return '';
			}

			return $this->replaceTemplateVariables($header, [
				'entries' => $html,
			]);
		}

		return $html;
	}

	protected function renderColumnProperty(TableType $type, string $template, Property $property, int $level = 0, bool $useSchemaName = false) : string
	{
		return $this->replaceTemplateVariables($template, [
			// using &nbsp; to force spaces
			'name' => str_repeat('&nbsp;&nbsp;&nbsp;', $level).$property->fieldName,
			'type' => ucfirst($useSchemaName && $property->example instanceof Schema ? $property->example->name : $property->type->value).($property->isArray ? '[]' : ''),
			'description' => $property->description ? : $this->config['defaults']['property_'.$type->value.'_description'],
			'example' => is_scalar($property->example) ? $property->example : '',
			'required' => false,
		]);
	}

	protected function generateIndexResourceList(ApiVersion $version, array $endpoints, $returnHtml = false) : string
	{
		$entryTemplate = $this->getTemplate('resources-list-entry');
		$html = '';

		/** @var Endpoint $endpoint */
		foreach ( $endpoints as $index => $endpoint ) {
			$summary = $endpoint->getSummary();
			$resource = $endpoint->getResource();

			$html .= $this->replaceTemplateVariables($entryTemplate, [
				'endpoint_resource' => $resource->name,
				'endpoint_name' => $summary->title,
				'endpoint_url' => ($this->config['options']['generate_separate_resource_pages'] ? $resource->getPathURL() : '').'/README.md',
				'endpoint_anchor' => $endpoint->getAnchor(),
				'endpoint_summary' => $summary->summary,
			]);
		}

		$html = $this->replaceTemplateVariables($this->getTemplate($this->config['options']['generate_separate_resource_pages'] ? 'resources-index-list' : 'resources-list'), [
			'api_name' => ucfirst($version->getDisplayName()),
			'api_version' => $version->version,
			'resource_list_entry' => $html,
		]);

		if ( !$returnHtml ) {
			$this->saveFile($this->config['paths']['docs'].'/src/'.$version->version.'/README.md', $html);
		}

		return $html;
	}

	protected function getSchemaProperties(Schema $schema)
	{
		// get the global schema
		$newSchema = $this->generator->getSchemes()[$schema->name];

		// merge schema and global schema withoutFields
		$ignoreFields = array_unique(array_merge($schema->withoutFields, $newSchema->withoutFields));

		// filter out any unwanted field
		return array_filter($newSchema->properties, fn(Property $value) => !in_array($value->fieldName, $ignoreFields));
	}

	protected function buildResponseExample(array $response) : array
	{
		$responseObject = [];
		/** @var Property $property */
		foreach ( $response as $property ) {
			if ( $property->example instanceof Schema ) {
				$this->appendToArray(
					$responseObject,
					$property->fieldName,
					$this->buildResponseExample($this->getSchemaProperties($property->example)),
					$property->isArray,
				);
			}
			elseif ( is_array($property->example) ) {
				$renderProperties = array_filter($property->example, fn(Property $subProperty) => $subProperty instanceof Property);

				if ( !empty($renderProperties) ) {
					$this->appendToArray(
						$responseObject,
						$property->fieldName,
						$this->buildResponseExample($renderProperties),
						$property->isArray,
					);
				}
			}
			else {
				$this->appendToArray(
					$responseObject,
					$property->fieldName,
					$property->example,
					$property->isArray,
				);
			}
		}

		return $responseObject;
	}

	protected function appendToArray(array &$object, string $key, mixed $value, bool $asArray) : void
	{
		if ( $asArray ) {
			$object[$key][] = $value;
		}
		else {
			$object[$key] = $value;
		}
	}
}