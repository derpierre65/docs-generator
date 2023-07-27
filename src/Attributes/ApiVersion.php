<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class ApiVersion
{
	public function __construct(public readonly string $version, public readonly string $prefix, public readonly string $url = '', readonly ?string $displayName = null)
	{
	}

	public function getDisplayName() : string
	{
		return $this->displayName ?? $this->version;
	}

	public function toArray() : array
	{
		return [
			'version' => $this->version,
			'prefix' => $this->prefix,
			'url' => $this->url,
			'displayName' => $this->getDisplayName(),
		];
	}

	public function getApiURL() : string
	{
		return (str_ends_with($this->url, '/') ? $this->url : $this->url.'/').$this->prefix.'/';
	}
}