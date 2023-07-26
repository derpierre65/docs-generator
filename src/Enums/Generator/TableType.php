<?php

namespace Derpierre65\DocsGenerator\Enums\Generator;

enum TableType: string
{
	case RESPONSE = 'response';
	case QUERY = 'query';
	case BODY = 'body';
}
