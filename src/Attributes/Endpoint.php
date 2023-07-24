<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;
use Derpierre65\DocsGenerator\Enums\EndpointMethod;
use Derpierre65\DocsGenerator\Enums\TokenType;
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

	public function addScopes(array $scopes) : static
	{
		array_push($this->scopes, ...$scopes);

		return $this;
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
		/** @var TokenType $type */
		foreach ( $this->tokenTypes->scopeTypes as $type ) {
			$tokenTypes[] = $type->value;
		}

		return $tokenTypes;
	}

	public function getProperties() : array
	{
		return array_merge($this->schema->properties ?? [], $this->properties);
	}

	public function getData() : array
	{
		return [
			'operationName' => $this->operationId,
			'apiVersion' => $this->apiVersion,
			'summary' => $this->summary?->summary,
			'url' => $this->getPath(),
			'oauth' => [
				'scopes' => $this->getScopes(),
				'tokenType' => $this->getTokenTypes(),
			],
			'response' => $this->getProperties(),
		];
	}
}