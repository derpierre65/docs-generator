<?php

namespace Derpierre65\DocsGenerator\Generator\Traits;

trait Files
{
	protected function saveFile(string $filename, string $html) : void
	{
		if ( !file_exists($dir = dirname($filename)) ) {
			mkdir($dir, recursive: true);
		}

		do {
			$html = rtrim(preg_replace("/\n\n\n/i", "\n\n", $html), "\n");
		} while ( preg_match("/\n\n\n/i", $html) );

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