<?php

namespace Derpierre65\DocsGenerator\Console\Commands;

use Derpierre65\DocsGenerator\Console\Command;
use splitbrain\phpcli\Options;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class InitCommand extends Command
{
	public $signature = 'init';

	public $description = 'Initialize the src-docs directory for your project.';

	public $npmPackage = 'github:derpierre65/docs-generator-npm';

	protected bool $srcDocsPresent = false;

	protected string $srcDocsDirectory;

	protected string $composerSrcDocs;

	public function registerArguments(Options $options) : void
	{
		$options->registerArgument('src-dir', 'Your src directory for the vuepress docs (default src-docs)', false, $this->signature);
	}

	public function handle() : int
	{
		$this->srcDocsDirectory = getcwd().'/'.($this->options->getArgs()[0] ?? 'src-docs');
		$this->composerSrcDocs = __DIR__.'/../../../src-docs';

		$this->cli->info('Initializing your project in {directory}...', ['directory' => $this->srcDocsDirectory]);

		$this->createSrcDirectory();
		$this->moveVuepressFiles();

		return self::SUCCESS;
	}

	private function createSrcDirectory() : void
	{
		$this->cli->info('Creating src-docs directory');
		$this->srcDocsPresent = file_exists($this->srcDocsDirectory);
		if ( $this->srcDocsPresent ) {
			$this->cli->warning('src-docs directory already exists');

			return;
		}

		mkdir($this->srcDocsDirectory);

		$this->cli->success('{directory} created.', ['directory' => $this->srcDocsDirectory]);
	}

	private function moveVuepressFiles() : void
	{
		if ( $this->srcDocsPresent ) {
			$this->cli->warning('src-docs already exists, most of the files will not be moved.');
			$this->moveImportantFiles();
		}
		else {
			$this->moveAllFiles();
		}
	}

	private function moveAllFiles() : void
	{
		/** @var SplFileInfo[] $files */
		$files = iterator_to_array(
			Finder::create()
				->files()
				->ignoreDotFiles(false)
				->in($this->composerSrcDocs)
				->sortByName(),
			false
		);

		$this->copyFiles($files);
	}

	private function moveImportantFiles() : void
	{
		$importantDirectories = [
			'/src/.vuepress/components/docs/',
			'/src/.vuepress/docs/',
		];

		foreach ( $importantDirectories as $directory ) {
			$this->copyFiles(iterator_to_array(
				Finder::create()
					->files()
					->ignoreDotFiles(false)
					->in($this->composerSrcDocs.$directory)
					->sortByName(),
				false
			), $directory);
		}
	}

	private function copyFiles(array $files, string $baseDir = '') : void
	{
		foreach ( $files as $file ) {
			$targetFile = str_replace(['//', '\\'], '/', $this->srcDocsDirectory.'/'.$baseDir.$file->getRelativePathname());
			$targetDirectory = dirname($targetFile);

			if ( !file_exists($targetDirectory) ) {
				$this->cli->info('Creating {directory}...', ['directory' => $targetDirectory]);
				if ( mkdir($targetDirectory, recursive: true) ) {
					$this->cli->success('Created directory {directory}.', ['directory' => $targetDirectory]);
				}
				else {
					$this->cli->error('Fail to create directory {directory}.', ['directory' => $targetDirectory]);
					!$this->srcDocsPresent && $this->cli->error('Check your permissions and try again (for a clean installation delete your src-docs directory).');
					exit;
				}
			}

			$this->cli->info('Copy {file}...', ['file' => $file->getRelativePathname()]);
			if ( copy($file->getPathname(), $targetFile) ) {
				$this->cli->success('Copied {file}.', ['file' => $file->getRelativePathname()]);
			}
			else {
				$this->cli->error('Fail to copy file {file}.', ['file' => $file->getRelativePathname()]);
				!$this->srcDocsPresent && $this->cli->error('Check your permissions and try again (for a clean installation delete your src-docs directory).');
				exit;
			}
		}
	}
}