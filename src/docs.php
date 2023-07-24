#!/usr/bin/env php
<?php

// register auto loader
require_once $GLOBALS['_composer_autoload_path'];

use Derpierre65\DocsGenerator\Console\Command;
use Derpierre65\DocsGenerator\Console\Commands\InitCommand;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Minimal extends CLI
{
	protected array $commands = [];

	protected function setup(Options $options)
	{
		$this->registerCommands($options, [
			InitCommand::class,
		]);

		$options->registerOption('version', 'print version', 'v');
	}

	protected function registerCommands(Options $options, $classList) : void
	{
		foreach ( $classList as $class ) {
			/** @var Command $instance */
			$instance = new $class();
			$this->commands[$instance->signature] = $instance;
			$options->registerCommand($instance->signature, $instance->description);
		}
	}

	protected function main(Options $options) : void
	{
		if ( isset($this->commands[$options->getCmd()]) ) {
			$command = $this->commands[$options->getCmd()];
			$command->prepare($this);
			$command->handle();
		}
		elseif ( $options->getOpt('version') ) {
			$this->info('1.0.0');
		}
		else {
			echo $options->help();
		}
	}
}

(new Minimal())->run();