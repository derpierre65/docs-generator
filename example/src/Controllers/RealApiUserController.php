<?php

namespace Derpierre65\DocsGenerator\Example\Controllers;

use Derpierre65\DocsGenerator\Attributes\BodyParameter;
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
	#[Response(new Schema('User', exclude: ['avatar']))]
	public function store() {

	}

	#[Endpoint(EndpointMethod::GET, 'real-api', 'users/{user}')]
	#[Summary('Get user', 'Get user’s information.')]
	#[Response(new Schema('User'))]
	public function view() {

	}

	#[Endpoint(EndpointMethod::POST, 'real-api', 'user/acting-as/')]
	#[Summary('Acting as User', 'Start acting as an other user.')]
	#[BodyParameter('user_id', PropertyType::INTEGER, description: 'User ID of the other user.', required: true)]
	#[Response(new Schema('User', exclude: ['acting_as', 'managers', 'manager_in']))]
	public function actingAs() {

	}
}