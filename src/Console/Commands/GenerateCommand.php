<?php

namespace Derpierre65\DocsGenerator\Console\Commands;

use Derpierre65\DocsGenerator\Console\Command;
use Derpierre65\DocsGenerator\DocsGenerator;
use splitbrain\phpcli\Options;

class GenerateCommand extends Command
{
	public $signature = 'generate';

	public $description = 'Generate the documentation files.';

	protected string $srcDocsDirectory;

	protected string $composerSrcDocs;

	public function registerArguments(Options $options) : void
	{
		$options->registerOption('watch', 'Generate the documentation after any change', 'w', command: $this->signature);
		$options->registerArgument('config-path', 'Path for your docs-generator config file.', true, $this->signature);
	}

	public function handle() : int
	{
		$configPath = getcwd().'/'.($this->options->getArgs()[0] ?? 'docs-generator.php');
		if ( !file_exists($configPath) ) {
			$this->cli->error('Config file not found.');

			return self::FAILURE;
		}

		$config = require_once($configPath);

		foreach ( ['paths', 'scan_directories'] as $key ) {
			if ( !array_key_exists($key, $config) ) {
				$this->cli->error('Config key {key} not found.', ['key' => $key]);

				return self::FAILURE;
			}
		}

		if ( !is_array($config['scan_directories']) || empty($config['scan_directories']) ) {
			$this->cli->error('No scan directories found in config.');

			return self::FAILURE;
		}

		$generator = new DocsGenerator($config);
		$generator->generate();

		// if ( $this->hasOption('watch', 'w') ) {
		// 	Watch::paths(array_keys($config['scan_directories']))
		// 		->onAnyChange(function (string $type, string $path) use ($config) {
		// 			$this->cli->info('Detected change in '. $path. ', generate the docs');
		// 			$generator = new DocsGenerator($config);
		// 			$generator->generate();
		// 		})
		// 		->setIntervalTime(500)
		// 		->start();
		// }

		return self::SUCCESS;
	}
}