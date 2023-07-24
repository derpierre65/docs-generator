<?php

namespace Derpierre65\DocsGenerator\Tests\Examples\ApiVersion2;

use Derpierre65\DocsGenerator\Attributes\ApiVersion;

#[ApiVersion('1.0', 'v1', 'my-api', 'https://api.example.org/')]
#[ApiVersion('2.0', 'v2', 'my-second-api', 'https://api.example.org/')]
#[ApiVersion('3.0', 'v3', 'my-third-api', 'https://api.example.org/')]
class TestClass
{
}