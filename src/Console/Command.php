<?php

namespace Derpierre65\DocsGenerator\Console;

use splitbrain\phpcli\CLI;

abstract class Command
{
	public const SUCCESS = 0;
	public const FAILURE = 1;
	public const INVALID = 2;

	/** @var string */
	public $signature;
	/** @var string */
	public $description;

	protected ?CLI $cli = null;

	abstract function handle(): int;

	public function prepare(CLI $cli) : void
	{
		$this->cli = $cli;
	}
}