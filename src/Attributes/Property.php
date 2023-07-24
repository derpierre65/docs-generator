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
		public mixed                 $example = null,
		public readonly ?string      $operationId = null,
	) {
		// overwrite default examples for some types
		if ( $this->example === null && $this->type !== PropertyType::NULL ) {
			$this->example = match ($this->type) {
				PropertyType::BOOLEAN => true,
				PropertyType::INTEGER => 42,
				PropertyType::STRING => 'hello world',
				PropertyType::DATE => '1970-01-01',
				PropertyType::DATETIME => '2022-01-01T00:00:00Z',
				default => $this->example,
			};
		}
	}
}