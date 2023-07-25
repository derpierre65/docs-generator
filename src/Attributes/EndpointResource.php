<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class EndpointResource
{
	public function __construct(public readonly string $name, public readonly ?string $operationId = null)
	{
	}

	public function getPathURL() : string
	{
		return preg_replace('/\s+/', '-', trim(strtolower($this->name)));
	}
}