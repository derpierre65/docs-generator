<?php

namespace Derpierre65\DocsGenerator\Enums;

enum EndpointMethod: string
{
	case GET = 'GET';
	case POST = 'POST';
	case PUT = 'PUT';
	case PATCH = 'PATCH';
	case DELETE = 'DELETE';
	// other types: head, connect, options, trace
	// but i dont know if there are required, a normal rest api will only have the defined method types.
}