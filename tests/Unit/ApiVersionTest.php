<?php
declare(strict_types=1);

namespace Derpierre65\DocsGenerator\Tests\Unit;

use Derpierre65\DocsGenerator\Tests\CreateGenerator;
use PHPUnit\Framework\TestCase;

class ApiVersionTest extends TestCase
{
	use CreateGenerator;

	public function testSingleApiVersion()
	{
		// arrange
		$generator = $this->initializeGenerator('ApiVersion1');

		// act
		$generator->fetch();

		// assert
		$apiVersions = $generator->getApiVersions();
		$this->assertCount(1, $apiVersions);
		$this->assertEmpty($generator->getEndpoints());
		$this->assertEmpty($generator->getSchemes());

		$this->assertApiVersionValues($apiVersions['my-api'], '1.0', 'v1', 'my-api', 'https://api.example.org/');
	}

	public function testMultipleApiVersions()
	{
		// arrange
		$generator = $this->initializeGenerator('ApiVersion2');

		// act
		$generator->fetch();

		// assert
		$apiVersions = $generator->getApiVersions();
		$this->assertCount(3, $apiVersions);
		$this->assertEmpty($generator->getEndpoints());
		$this->assertEmpty($generator->getSchemes());

		$this->assertApiVersionValues($apiVersions['my-api'], '1.0', 'v1', 'my-api', 'https://api.example.org/');
		$this->assertApiVersionValues($apiVersions['my-second-api'], '2.0', 'v2', 'my-second-api', 'https://api.example.org/');
		$this->assertApiVersionValues($apiVersions['my-third-api'], '3.0', 'v3', 'my-third-api', 'https://api.example.org/');
	}

	public function testSingleApiVersionJson()
	{
		// arrange
		$generator = $this->initializeGenerator('ApiVersion1');
		@unlink($generator->getApiJsonDirectory());

		$this->assertFileDoesNotExist($apiJsonFile = $generator->getApiJsonDirectory());

		// act
		$generator->fetch()->saveApiVersionJson();

		$this->assertFileExists($apiJsonFile);
		$jsonContent = file_get_contents($apiJsonFile);

		$this->assertJson($jsonContent);
		$json = json_decode($jsonContent, true);

		// assert
		$this->assertCount(count($generator->getApiVersions()), $json);

		foreach ( $generator->getApiVersions() as $apiVersion ) {
			$this->assertArrayHasKey($apiVersion->internalName, $json);
			$this->assertSame($json[$apiVersion->internalName], $apiVersion->toArray());
		}
	}

	public function testMultipleApiVersionJson()
	{
		// arrange
		$generator = $this->initializeGenerator('ApiVersion2');
		@unlink($generator->getApiJsonDirectory());

		$this->assertFileDoesNotExist($apiJsonFile = $generator->getApiJsonDirectory());

		// act
		$generator->fetch()->saveApiVersionJson();

		$this->assertFileExists($apiJsonFile);
		$jsonContent = file_get_contents($apiJsonFile);

		$this->assertJson($jsonContent);
		$json = json_decode($jsonContent, true);

		// assert
		$this->assertCount(count($generator->getApiVersions()), $json);

		foreach ( $generator->getApiVersions() as $apiVersion ) {
			$this->assertArrayHasKey($apiVersion->internalName, $json);
			$this->assertSame($json[$apiVersion->internalName], $apiVersion->toArray());
		}
	}
}