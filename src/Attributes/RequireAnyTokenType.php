<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;
use Derpierre65\DocsGenerator\Helpers\TokenTypeInterface;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class RequireAnyTokenType implements TokenTypeInterface
{
	public function __construct(
		public readonly array $scopeTypes,
		public readonly ?string $operationId = null,
	) {
	}
}