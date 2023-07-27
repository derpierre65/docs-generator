<?php

namespace Derpierre65\DocsGenerator\Example\Controllers;

use Derpierre65\DocsGenerator\Attributes\ApiVersion;

#[ApiVersion('kraken', 'kraken', 'https://api.example.org/')]
#[ApiVersion('helix', 'helix', 'https://api.example.org/')]
#[ApiVersion('real-api', 'v1', 'https://api.example.org/', 'Real API Example')]
class Controller
{
}