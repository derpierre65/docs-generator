<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class Summary
{
	public function __construct(public readonly string $title, public readonly string $summary, public readonly ?string $operationId = null)
	{
	}

	public function getAnchor() : string
	{
		return preg_replace('/\s+/', '-', trim(strtolower($this->title)));
	}
}