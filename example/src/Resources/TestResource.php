<?php

namespace Derpierre65\DocsGenerator\Example\Resources;

use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\Enums\PropertyType;

#[Schema('Test', [
	new Property('id', PropertyType::INTEGER, 42),
	new Property('username', PropertyType::STRING, 'derpierre65'),
	new Property('email', PropertyType::STRING, 'hello@derpierre65.dev'),
	new Property('overlay_token', PropertyType::STRING, 'pu72I3Vkz7iAJegTpzp28ctTWsm'),
	new Property('created_at', PropertyType::DATETIME, '2022-01-01T00:00:00Z'),
])]
class TestResource{}