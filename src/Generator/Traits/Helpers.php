<?php

namespace Derpierre65\DocsGenerator\Generator\Traits;

trait Helpers
{
	protected function normalizeDir(string $dir) : array|string
	{
		return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $dir);
	}

	protected function normalizeNamespace(string $namespace) : string
	{
		return rtrim($namespace, '\\');
	}
}