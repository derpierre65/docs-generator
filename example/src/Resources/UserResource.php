<?php

namespace Derpierre65\DocsGenerator\Example\Resources;

use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\Enums\PropertyType;

#[Schema('User')]
#[Property('id', PropertyType::INTEGER)]
#[Property('username', PropertyType::STRING)]
#[Property('slug', PropertyType::STRING)]
#[Property('email', PropertyType::STRING)]
#[Property('overlay_token', PropertyType::STRING)]
#[Property('avatar', PropertyType::STRING)]
// #[Property('acting_as', [PropertyType::OBJECT, PropertyType::NULL], new Schema('User', withoutFields: ['acting_as']))]
#[Property('acting_as', PropertyType::OBJECT, new Schema('User', withoutFields: ['acting_as']))]
#[Property('scopes', PropertyType::STRING, isArray: true)]
#[Property('created_at', PropertyType::DATETIME)]
class UserResource
{
}