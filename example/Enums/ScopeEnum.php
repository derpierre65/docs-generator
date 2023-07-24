<?php

namespace Derpierre65\DocsGenerator\Example\Enums;

enum ScopeEnum: string
{
	case MY_TEST_SCOPE = 'test:read';
	case MY_SECOND_TEST_SCOPE = 'test:manage';
	case MY_THIRD_SCOPE = 'test2:manage';
}