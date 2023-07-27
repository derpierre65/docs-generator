<?php

namespace Derpierre65\DocsGenerator\Tests\Examples\ApiVersion2;

use Derpierre65\DocsGenerator\Attributes\ApiVersion;

#[ApiVersion('my-api', 'v1', 'https://api.example.org/')]
#[ApiVersion('my-second-api', 'v2', 'https://api.example.org/')]
#[ApiVersion('my-third-api', 'v3', 'https://api.example.org/')]
class TestClass
{
}