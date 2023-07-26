<?php

namespace Derpierre65\DocsGenerator\Console;

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

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

	protected ?Options $options;

	abstract function handle() : int;

	public function prepare(CLI $cli, Options $options) : void
	{
		$this->cli = $cli;
		$this->options = $options;
	}

	public function registerArguments(Options $options)
	{
	}

	public function hasOption(string $option, string $short = '') : bool
	{
		$args = array_map('strtolower', $this->options->getArgs());

		return in_array('--'.$option, $args) || $short && in_array('-'.$short, $args);
	}
}