<?php

namespace Derpierre65\DocsGenerator\Generator;

use Derpierre65\DocsGenerator\Attributes\Endpoint;
use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\DocsGenerator;

class MarkdownGenerator
{
	public function __construct(protected DocsGenerator $generator, protected array $config)
	{
	}

	public function generateResourcesList() : static
	{
		foreach ( $this->generator->getEndpoints() as $version => $endpoints ) {
			$this->generateIndexResourceList($version, $endpoints);
			$this->generateResourceIndex($version, $endpoints);
		}

		return $this;
	}

	protected function generateResourceIndex(string $version, array $endpoints) : void
	{
		$html = $resources = [];
		$entryTemplate = $this->getTemplate('resources-index-entry');
		$indexTemplate = $this->getTemplate('resources-index');
		$resourceIndexHeaderTemplate = $this->getTemplate('resources-index-header');
		$responseEntryTemplate = $this->getTemplate('resources-index-response-entry');
		$responseIndexTemplate = $this->getTemplate('resources-index-response-index');

		/** @var Endpoint $endpoint */
		foreach ( $endpoints as $index => $endpoint ) {
			$summary = $endpoint->getSummary();
			$resource = $endpoint->getResource();

			if ( !isset($html[$resource->name]) ) {
				$html[$resource->name] = '';
				$resources[$resource->name] = $resource;
			}

			$html[$resource->name] .= $this->replaceTemplateVariables($entryTemplate, [
				'endpoint_title' => $summary->title,
				'endpoint_summary' => $summary->summary,
				'endpoint_method' => $endpoint->method->value,
				'endpoint_url' => $endpoint->getPath(),
				'endpoint_authorization' => '', // TODO oauth authorization information
				'query_parameters' => '', // TODO
				'body_parameters' => '', // TODO
				'response_body' => $this->getTable(
					$responseIndexTemplate,
					$responseEntryTemplate,
					$endpoint->getProperties(),
					0,
					$this->config['options']['resolve_schema_in_response']
				),
				'response_codes' => '', // TODO
			]);
		}

		if ( $this->config['options']['generate_separate_resource_pages'] ) {
			foreach ( $resources as $resourceName => $resource ) {
				$this->saveFile(
					$this->config['docs_dir'].'/src/'.$version.'/'.$resource->getPathURL().'/README.md',
					$this->replaceTemplateVariables($indexTemplate, [
						'header' => $resourceIndexHeaderTemplate,
						'resource_name' => $resourceName,
						'endpoints' => $html[$resource->name],
					])
				);
			}
		}
		else {
			$indexHtml = '';
			$header = $resourceIndexHeaderTemplate;
			foreach ( $resources as $resourceName => $resource ) {
				$indexHtml .= $this->replaceTemplateVariables($indexTemplate, [
					'header' => $header,
					'resource_name' => $resourceName,
					'endpoints' => $html[$resource->name],
				]);

				$header = '';
			}

			$this->saveFile($this->config['docs_dir'].'/src/'.$version.'/README.md', $this->replaceTemplateVariables($indexHtml, [
				'header' => $resourceIndexHeaderTemplate,
			]));
		}
	}

	protected function getTable(string $header, string $entry, array $properties, $level = 0, bool $shouldResolveSchema = false)
	{
		if ( empty($properties) ) {
			return '';
		}

		$html = '';

		/** @var Property $property */
		foreach ( $properties as $property ) {
			if ( $property->example instanceof Schema ) {
				// append original property
				$html .= $this->renderColumnProperty($entry, $property, $level, !$shouldResolveSchema);

				if ( $shouldResolveSchema ) {
					$schema = $property->example;

					// get the global schema
					$newSchema = clone($this->generator->getSchemes()[$schema->name]); // TODO i dont know if i need to clone the original schema here, currently it works without clone

					// merge schema and global schema withoutFields
					$ignoreFields = array_unique(array_merge($schema->withoutFields, $newSchema->withoutFields));

					// filter out any unwanted field
					$properties = array_filter($newSchema->properties, fn(Property $value) => !in_array($value->fieldName, $ignoreFields));

					$html .= $this->getTable('', $entry, $properties, $level + 1);
				}
			}
			elseif ( is_array($property->example) ) {
				$renderProperties = array_filter($property->example, fn(Property $subProperty) => $subProperty instanceof Property);

				if ( !empty($renderProperties) ) {
					$html .= $this->renderColumnProperty($entry, $property, $level);
					$html .= $this->getTable('', $entry, $renderProperties, $level + 1);
				}
			}
			else {
				$html .= $this->renderColumnProperty($entry, $property, $level);
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

	protected function renderColumnProperty(string $template, Property $property, int $level = 0, bool $useSchemaName = false) : string
	{
		return $this->replaceTemplateVariables($template, [
			// using &nbsp; to force spaces
			'name' => str_repeat('&nbsp;&nbsp;&nbsp;', $level).$property->fieldName,
			'type' => ucfirst($useSchemaName && $property->example instanceof Schema ? $property->example->name : $property->type->value).($property->isArray ? '[]' : ''),
			'description' => $property->description,
		]);
	}

	protected function generateIndexResourceList(string $version, array $endpoints) : void
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
				'endpoint_url' => $resource->getPathURL().'/README.md',
				'endpoint_anchor' => $endpoint->getAnchor(),
				'endpoint_summary' => $summary->summary,
			]);
		}

		$this->saveFile($this->config['docs_dir'].'/src/'.$version.'/README.md', $this->replaceTemplateVariables($this->getTemplate('resources-list'), [
			'resource_list_entry' => $html,
		]));
	}

	protected function replaceTemplateVariables(string $template, array $variables) : string
	{
		$formattedVariables = [];
		foreach ( $variables as $key => $value ) {
			$formattedVariables['%'.$key.'%'] = $value;
		}

		return str_replace(array_keys($formattedVariables), array_values($formattedVariables), $template);
	}

	protected function saveFile(string $filename, string $html) : void
	{
		if ( !file_exists($dir = dirname($filename)) ) {
			mkdir($dir, recursive: true);
		}

		file_put_contents($filename, $html);
	}

	protected function getTemplate(string $template) : string
	{
		return file_get_contents($this->config['template_path'].$template.'.md') ?? '';
	}
}