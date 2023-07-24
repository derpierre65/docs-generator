<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class Response
{
	public function __construct(public Schema|array $properties, public readonly ?string $operationId = null)
	{
	}
}