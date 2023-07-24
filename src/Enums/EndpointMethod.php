<?php

namespace Derpierre65\DocsGenerator\Enums;

enum EndpointMethod: string
{
	case GET = 'GET';
	case POST = 'POST';
	case PATCH = 'PATCH';
	case DELETE = 'DELETE';
}