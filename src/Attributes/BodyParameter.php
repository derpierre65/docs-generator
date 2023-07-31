<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;
use Derpierre65\DocsGenerator\Enums\PropertyType;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class BodyParameter extends Property
{
	public function __construct(string $fieldName, PropertyType $type, mixed $example = null, string $description = '', bool $isArray = false, public bool $required = false, ?string $operationId = null)
	{
		parent::__construct($fieldName, $type, $example, $description, $isArray, $operationId);
	}
}