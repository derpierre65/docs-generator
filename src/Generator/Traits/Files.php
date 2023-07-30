<?php

namespace Derpierre65\DocsGenerator\Generator\Traits;

trait Files
{
	protected function saveFile(string $filename, string $html) : void
	{
		if ( !file_exists($dir = dirname($filename)) ) {
			mkdir($dir, recursive: true);
		}

		file_put_contents($filename, $html);
	}

	protected function rmdir(string $path) : void
	{
		foreach ( glob($path.'/*') as $file ) {
			is_dir($file) ? $this->rmdir($file) : @unlink($file);
		}

		rmdir($path);
	}
}