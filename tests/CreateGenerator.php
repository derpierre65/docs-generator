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
		$docsPath = __DIR__.'/src-docs';
		$config = [
			'scan_directories' => [
				__DIR__.'/Examples/'.$testCase => 'Derpierre65\DocsGenerator\Tests\Examples\\'.$testCase,
			],
			'paths' => [
				'docs' => $docsPath,
				'template' => $docsPath.'/generator',
			],
		];

		$generator = new DocsGenerator($config);
		if ( !file_exists($config['paths']['docs']) ) {
			mkdir($config['paths']['docs']);
		}

		return $generator;
	}

	public function assertApiVersionValues(ApiVersion $apiVersion, string $version, string $prefix, string $url = '') : void
	{
		$this->assertSame($apiVersion->version, $version);
		$this->assertSame($apiVersion->prefix, $prefix);
		$this->assertSame($apiVersion->url, $url);
	}
}