<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;
use Derpierre65\DocsGenerator\Enums\PropertyType;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Property
{
	public function __construct(
		public string           $fieldName,
		public PropertyType     $type,
		public mixed            $example = null,
		public string           $description = '',
		public bool             $isArray = false,
		public readonly ?string $operationId = null,
	) {
		if ( $this->type === PropertyType::ARRAY ) {
			$this->isArray = true;
		}

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