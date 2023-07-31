<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Schema
{
	public function __construct(public readonly string $name, public array $properties = [], public array $exclude = [])
	{
	}

	public function addProperty(Property $property) : static
	{
		$this->properties[] = $property;

		return $this;
	}
}