<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class ApiVersion
{
	public function __construct(public readonly string $version, public readonly string $prefix, public readonly string $internalName = '', public readonly string $url = '')
	{
	}

	public function getApiURL() : string
	{
		return (str_ends_with($this->url, '/') ? $this->url : $this->url.'/').$this->prefix.'/';
	}
}