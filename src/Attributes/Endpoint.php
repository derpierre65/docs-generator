<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;
use Derpierre65\DocsGenerator\Enums\EndpointMethod;
use Derpierre65\DocsGenerator\Helpers\RequireScope;
use Derpierre65\DocsGenerator\Helpers\ScopeInterface;
use Derpierre65\DocsGenerator\Helpers\TokenTypeInterface;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Endpoint
{
	protected ?ApiVersion $apiVersion = null;

	protected ?Summary $summary = null;

	/** @var Property[] */
	protected array $properties = [];

	/** @var array ScopeInterface[] */
	protected array $scopes = [];

	/** @var array TokenTypeInterface[] */
	protected array $tokenTypes = [];

	protected ?EndpointResource $resource = null;

	protected string $anchor = '';

	public function __construct(
		public readonly EndpointMethod $method,
		public readonly string         $version,
		public readonly string         $path,
		public ?Schema                 $schema = null,
		public ?string                 $operationId = null,
	) {
		if ( $this->operationId === null ) {
			$this->operationId = implode('-', [
				$method->value,
				$version,
				$path,
			]);
		}
	}

	public function addScopes(array $scopes) : static
	{
		array_push($this->scopes, ...$scopes);

		return $this;
	}

	public function setResource(?EndpointResource $resource = null) : static
	{
		$this->resource = $resource;

		return $this;
	}

	public function setSummary(?Summary $summary = null) : static
	{
		$this->summary = $summary;

		return $this;
	}

	/**
	 * @param TokenTypeInterface[] $tokenType
	 *
	 * @return $this
	 */
	public function setTokenType(array $tokenType) : static
	{
		$this->tokenTypes = $tokenType;

		return $this;
	}

	public function setVersion(ApiVersion $version) : static
	{
		$this->apiVersion = $version;

		return $this;
	}

	public function setProperties(array $properties) : static
	{
		$this->properties = $properties;

		return $this;
	}

	public function setAnchor(string $anchor) : static
	{
		$this->anchor = $anchor;

		return $this;
	}

	public function getAnchor() : string
	{
		return $this->anchor;
	}

	public function getResource() : ?EndpointResource
	{
		return $this->resource;
	}

	public function getPath() : string
	{
		$path = '';
		if ( $this->apiVersion ) {
			$path = $this->apiVersion->getApiURL();
		}
		$path .= $this->path;

		return $path;
	}

	public function getScopes() : array
	{
		$scopes = [];
		/** @var RequireScope $scope */
		foreach ( $this->scopes as $scope ) {
			if ( is_string($scope) ) {
				$scopeInfo = [
					'optional' => false,
					'scope' => $scope,
				];
			}
			elseif ( $scope instanceof ScopeInterface ) {
				$scopeInfo = $scope->toArray();
			}
			// should be an enum
			elseif ( is_object($scope) ) {
				$scopeInfo = [
					'optional' => false,
					'scope' => $scope->value,
				];
			}
			else {
				continue;
			}

			$scopes[$scopeInfo['scope']] = $scopeInfo;
		}

		return $scopes;
	}

	public function getTokenTypes() : array
	{
		$tokenTypes = [];
		/** @var RequireAnyTokenType $type */
		foreach ( $this->tokenTypes as $type ) {
			$tokenTypes[] = $type->scopeTypes;
		}

		return $tokenTypes;
	}

	public function getProperties() : array
	{
		return array_merge($this->schema->properties ?? [], $this->properties);
	}

	public function getSummary() : ?Summary
	{
		return $this->summary;
	}

	public function toArray() : array
	{
		return [
			'id' => $this->resource?->name . '-' . $this->anchor,
			'title' => $this->summary?->title,
			'summary' => $this->summary?->summary,
			'url' => $this->getPath(),
			'resource' => $this->resource?->name,
			'operationId' => $this->operationId,
			'apiVersion' => $this->apiVersion,
			'oauth' => [
				'scopes' => $this->getScopes(),
				'tokenType' => $this->getTokenTypes(),
			],
			'response' => $this->getProperties(),
		];
	}
}