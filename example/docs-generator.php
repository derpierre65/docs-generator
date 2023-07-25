<?php

return [
	/**
	 * Your docs directory where you published the vuepress files.
	 */
	'docs_dir' => __DIR__.'/src-docs',

	/**
	 * List of your app source directories to scan for endpoints.
	 * Use your directory as key and your namespace as value.
	 */
	'scan_directories' => [
		__DIR__.'/src' => 'Derpierre65\\DocsGenerator\\Example',
	],
];