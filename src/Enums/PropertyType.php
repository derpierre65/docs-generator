<?php

namespace Derpierre65\DocsGenerator\Enums;

enum PropertyType: string
{
	case ARRAY = 'array';
	case BOOLEAN = 'boolean';
	case DATE = 'date';
	case DATETIME = 'datetime';
	case INTEGER = 'integer';
	case OBJECT = 'object';
	case SCHEMA = 'schema';
	case STRING = 'string';
	case NULL = 'null';
}