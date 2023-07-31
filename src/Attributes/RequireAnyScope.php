<?php

namespace Derpierre65\DocsGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class RequireAnyScope extends RequireScope
{
	protected string $type = 'requireAny';

	public function __construct(object|string $scope, ?string $operationId = null)
	{
		parent::__construct($scope, false, $operationId);
	}
}