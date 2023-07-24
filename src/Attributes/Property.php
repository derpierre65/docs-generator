<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;
use Derpierre65\DocsGenerator\Enums\PropertyType;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Property
{
	public function __construct(
		public readonly string       $fieldName,
		public readonly PropertyType $type,
		public readonly mixed        $example,
		public readonly ?string      $operationId = null,
	) {
	}
}