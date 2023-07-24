<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class EndpointResource
{
	public function __construct(public readonly string $name)
	{
	}
}