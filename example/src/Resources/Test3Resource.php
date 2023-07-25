<?php

namespace Derpierre65\DocsGenerator\Example\Resources;

use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\Enums\PropertyType;

#[Schema('Test3')]
#[Property('id', PropertyType::INTEGER, 42)]
#[Property('username', PropertyType::STRING, 'derpierre65')]
#[Property('email', PropertyType::STRING, 'hello@derpierre65.dev')]
#[Property('overlay_token', PropertyType::STRING, 'pu72I3Vkz7iAJegTpzp28ctTWsm')]
#[Property('created_at', PropertyType::DATETIME, '2022-01-01T00:00:00Z')]
#[Property('just_an_object', PropertyType::OBJECT, [
	new Property('datetime', PropertyType::DATETIME),
	new Property('my_string', PropertyType::STRING),
])]
#[Property('nested_object_array', PropertyType::OBJECT, [
	new Property('datetime', PropertyType::DATETIME),
	new Property('my_string', PropertyType::STRING),
], isArray: true)]
#[Property('test', PropertyType::OBJECT, new Schema('Test2'))]
class Test3Resource
{
}