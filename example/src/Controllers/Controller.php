<?php

namespace Derpierre65\DocsGenerator\Example\Controllers;

use Derpierre65\DocsGenerator\Attributes\ApiVersion;

#[ApiVersion('1.0', 'kraken', 'kraken', 'https://api.example.org/')]
#[ApiVersion('2.0', 'helix', 'helix', 'https://api.example.org/')]
#[ApiVersion('3.0', 'v1', 'real-api', 'https://api.example.org/')]
class Controller
{
}