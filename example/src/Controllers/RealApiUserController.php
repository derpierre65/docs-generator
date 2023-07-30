<?php

namespace Derpierre65\DocsGenerator\Example\Controllers;

use Derpierre65\DocsGenerator\Attributes\Endpoint;
use Derpierre65\DocsGenerator\Attributes\EndpointResource;
use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\Response;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\Attributes\Summary;
use Derpierre65\DocsGenerator\Enums\EndpointMethod;
use Derpierre65\DocsGenerator\Enums\PropertyType;

#[EndpointResource('User')]
class RealApiUserController
{
	#[Endpoint(EndpointMethod::GET, 'real-api', 'users')]
	#[Summary('Get Users', 'Gets a list of all users.')]
	#[Property('data', PropertyType::ARRAY, new Schema('User'))]
	public function index()
	{
		// here my index stuff
	}

	#[Endpoint(EndpointMethod::PATCH, 'real-api', 'users/{user}')]
	#[Summary('Update user', 'Updates the user’s information.')]
	#[Response(new Schema('User', withoutFields: ['avatar']))]
	public function store() {

	}
}