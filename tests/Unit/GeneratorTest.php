<?php
declare(strict_types=1);

namespace Derpierre65\DocsGenerator\Tests\Unit;

use Derpierre65\DocsGenerator\DocsGenerator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
	public function testGenerator()
	{
		$this->assertTrue(true);

		/*$generator = new DocsGenerator(
			$_SERVER['DOCUMENT_ROOT'].'example/Controllers',
			$_SERVER['DOCUMENT_ROOT'].'example/Resources',
			'Derpierre65\DocsGenerator\Example\Controllers',
			'Derpierre65\DocsGenerator\Example\Resources'
		);

		dd($generator->fetchEndpoints());*/
	}
}