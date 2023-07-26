<?php

namespace Derpierre65\DocsGenerator\Tests\Unit;

use Derpierre65\DocsGenerator\Tests\CreateGenerator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
	use CreateGenerator;

	public function testEmptyValues()
	{
		$generator = $this->initializeGenerator('ApiVersion1');

		$this->assertEmpty($generator->getEndpoints());
		$this->assertEmpty($generator->getApiVersions());
		$this->assertEmpty($generator->getSchemes());
	}
}