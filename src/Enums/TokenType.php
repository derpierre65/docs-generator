<?php

namespace Derpierre65\DocsGenerator\Enums;

enum TokenType: string
{
	case USER_ACCESS_TOKEN = 'user_access_token';
	case CLIENT_ACCESS_TOKEN = 'client_access_token';
	case REFRESH_TOKEN = 'refresh_token';
}