<?php

namespace Derpierre65\DocsGenerator\Tests;

use Derpierre65\DocsGenerator\Attributes\ApiVersion;
use Derpierre65\DocsGenerator\DocsGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait CreateGenerator
{
	public function initializeGenerator(string $testCase) : DocsGenerator
	{
		$docsDirectory = __DIR__.'/src-docs';
		$generator = new DocsGenerator(
			__DIR__.'/Examples/'.$testCase,
			'Derpierre65\DocsGenerator\Tests\Examples\\'.$testCase,
			$docsDirectory,
		);
		if ( !file_exists($docsDirectory) ) {
			mkdir($docsDirectory);
		}

		return $generator;
	}

	public function assertApiVersionValues(ApiVersion $apiVersion, string $version, string $prefix, string $internalName = '', string $url = '') : void
	{
		$this->assertSame($apiVersion->version, $version);
		$this->assertSame($apiVersion->prefix, $prefix);
		$this->assertSame($apiVersion->internalName, $internalName);
		$this->assertSame($apiVersion->url, $url);
	}
}