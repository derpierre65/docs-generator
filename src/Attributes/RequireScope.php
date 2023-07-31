<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;
use Derpierre65\DocsGenerator\Attributes\Interfaces\ScopeInterface;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class RequireScope implements ScopeInterface
{
	protected string $type = 'require';

	// TODO add tokentype?
	public function __construct(public readonly object|string $scope, public readonly bool $optional = false, public readonly ?string $operationId = null)
	{
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function getScope() : string
	{
		if ( is_string($this->scope) ) {
			return $this->scope;
		}

		return $this->scope->value;
	}

	public function toArray() : array
	{
		return [
			'type' => $this->type,
			'optional' => $this->optional,
			'scope' => $this->getScope(),
		];
	}
}