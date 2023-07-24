<?php

namespace Derpierre65\DocsGenerator\Example\Controllers;

use Derpierre65\DocsGenerator\Attributes\Endpoint;
use Derpierre65\DocsGenerator\Attributes\EndpointResource;
use Derpierre65\DocsGenerator\Attributes\Property;
use Derpierre65\DocsGenerator\Attributes\RequireAnyScope;
use Derpierre65\DocsGenerator\Attributes\RequireAnyTokenType;
use Derpierre65\DocsGenerator\Attributes\Response;
use Derpierre65\DocsGenerator\Attributes\ResponseCode;
use Derpierre65\DocsGenerator\Attributes\Schema;
use Derpierre65\DocsGenerator\Attributes\Summary;
use Derpierre65\DocsGenerator\Example\Enums\Scope;
use Derpierre65\DocsGenerator\Example\Enums\ScopeEnum;
use Derpierre65\DocsGenerator\Enums\EndpointMethod;
use Derpierre65\DocsGenerator\Enums\PropertyType;
use Derpierre65\DocsGenerator\Enums\TokenType;
use Derpierre65\DocsGenerator\Helpers\RequireScope;

#[EndpointResource('User')]
class TestController extends Controller
{
	#[Summary('Get list of Users @index')]
	#[Endpoint(EndpointMethod::GET, 'kraken', 'users', operationId: 'kraken-users-index')]
	#[Endpoint(EndpointMethod::GET, 'helix', 'users', operationId: 'helix-users-index')]
	#[RequireAnyTokenType([TokenType::CLIENT_ACCESS_TOKEN, TokenType::USER_ACCESS_TOKEN])]
	#[RequireScope(ScopeEnum::MY_TEST_SCOPE)]
	#[RequireScope(Scope::MY_SECOND_TEST_SCOPE)]
	#[Response(new Schema('Test'))]
	public function index(){}

	#[Summary('Get list of Users @index2')]
	#[Summary('Get list of Users with social connections @index2', 'helix-users-index-2')]
	#[Endpoint(EndpointMethod::GET, 'kraken', 'users', new Schema('Test'))]
	#[Endpoint(EndpointMethod::GET, 'helix', 'users', new Schema('Test2'), 'helix-users-index-2')]
	#[RequireAnyTokenType([TokenType::CLIENT_ACCESS_TOKEN, TokenType::USER_ACCESS_TOKEN])]
	#[RequireScope(ScopeEnum::MY_TEST_SCOPE)] // required for both routes
	#[RequireScope(Scope::MY_SECOND_TEST_SCOPE, operationId: 'helix-users-index-2')] // only required for 'helix-users-index-2' route
	public function index2() {}

	#[Summary('Get list of Users')]
	#[Endpoint(EndpointMethod::GET, 'kraken', 'users', new Schema('Test'), 'kraken-users-index-3')]
	#[Endpoint(EndpointMethod::GET, 'helix', 'users', new Schema('Test2'), 'helix-users-index-3')]
	#[RequireAnyTokenType([TokenType::CLIENT_ACCESS_TOKEN, TokenType::USER_ACCESS_TOKEN], 'kraken-users-index-3')]
	#[RequireAnyTokenType([TokenType::USER_ACCESS_TOKEN], 'helix-users-index-3')]
	#[RequireScope(ScopeEnum::MY_TEST_SCOPE)]
	#[RequireAnyScope(ScopeEnum::MY_THIRD_SCOPE)]
	#[RequireAnyScope(ScopeEnum::MY_SECOND_TEST_SCOPE)]
	#[Property('test', PropertyType::ARRAY, ['test', 'test2'], 'kraken-users-index-3')]
	#[ResponseCode(200, 'Successfully retrieved the list of Users.')]
	#[ResponseCode(400, 'broadcaster_id query parameter is required.')]
	#[ResponseCode(400, 'The ID in the user_id query parameter is not valid')]
	#[ResponseCode(400, 'The number of user_id query parameters exceeds the maximum allowed')]
	#[ResponseCode(401, 'The Authorization header is required and must contain a user access token.')]
	#[ResponseCode(401, 'The ID in the broadcaster_id query parameter must match the user ID in the access token.')]
	#[ResponseCode(401, 'The client ID specified in the Client-Id header does not match the client ID specified in the OAuth token.')]
	#[ResponseCode(500)]

	// todo add automatic if RequireAnyScope is available
	#[ResponseCode(401, 'The [user access token] must include the [channel:read:vips or channel:manage:vips] scope.')]
	// todo add automatic if RequireScope is available
	#[ResponseCode(401, 'The [user access token] must include the [channel:read:vips and channel:manage:vips] scopes.')]
	public function index3() {}

	// #[Summary('Get list of Users')]
	// #[Endpoint(EndpointMethod::GET, 'kraken-users-index-3', 'kraken', 'users', scopes: [Scope::MY_SECOND_TEST_SCOPE], schema: new Schema('Test'))]
	// #[Endpoint(EndpointMethod::GET, 'helix-users-index-3', 'helix', 'users', schema: new Schema('Test2'))]
	// #[RequireAnyTokenType([AccessTokenType::APP_TOKEN, AccessTokenType::USER_ACCESS_TOKEN], 'kraken-users-index-3')]
	// #[RequireAnyTokenType([AccessTokenType::USER_ACCESS_TOKEN], 'helix-users-index-3')]
	// #[RequireScope(ScopeEnum::MY_TEST_SCOPE)]
	// #[Property('new_field', PropertyType::INTEGER, 42)]
	// public function index4() {
	//
	// }
	//
	// #[Summary('Get list of Users')]
	// #[Endpoint(
	// 	EndpointMethod::GET,
	// 	'users-id',
	// 	'kraken',
	// 	'users/{user_id}',
	// 	new RequireAnyTokenType([AccessTokenType::APP_TOKEN, AccessTokenType::USER_ACCESS_TOKEN]),
	// 	[new RequireScope(Scope::MY_TEST_SCOPE)],
	// )]
	// #[Endpoint(
	// 	EndpointMethod::GET,
	// 	'users-me',
	// 	'kraken',
	// 	'users/@me',
	// 	new RequireAnyTokenType([AccessTokenType::USER_ACCESS_TOKEN]),
	// 	[new RequireScope(Scope::MY_TEST_SCOPE)],
	// )]
	// #[Endpoint(
	// 	EndpointMethod::GET,
	// 	'users-token-test',
	// 	'kraken',
	// 	'users/@token',
	// 	new RequireAnyTokenType([AccessTokenType::USER_ACCESS_TOKEN]),
	// 	[new RequireScope(Scope::MY_TEST_SCOPE)],
	// 	new Schema('Test'),
	// )]
	// #[Response(new Schema('Test'))]
	// #[Response(new Schema('Test2'), 'users-me')]
	// public function view()
	// {
	// }

	public function store()
	{
	}

	public function update()
	{
	}

	public function destroy()
	{
	}
}