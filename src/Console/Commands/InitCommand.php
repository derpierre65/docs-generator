<?php

namespace Derpierre65\DocsGenerator\Console\Commands;

use Derpierre65\DocsGenerator\Console\Command;

class InitCommand extends Command
{
	public $signature = 'init';

	public $description = 'Initialize the src-docs directory for your project.';

	public $npmPackage = 'github:derpierre65/docs-generator-npm';

	protected bool $srcDocsPresent = false;

	public function handle() : int
	{
		$this->cli->info('Initialize your project.');

		$this->installNPMPackage();
		$this->createSrcDirectory();

		return self::SUCCESS;
	}

	private function installNPMPackage() : void
	{
		passthru('npm install -D '.$this->npmPackage);
	}

	private function createSrcDirectory() : void
	{
		$this->cli->info('Creating src-docs directory');
		$this->srcDocsPresent = file_exists('src-docs');
		if ( $this->srcDocsPresent ) {
			$this->cli->warning('src-docs directory already exists');

			return;
		}

		mkdir('src-docs');
	}
}