<?php

namespace Derpierre65\DocsGenerator\Helpers;

interface ScopeInterface
{
	public function toArray(): array;
	public function getScope(): string;
	public function getType() : string;
}