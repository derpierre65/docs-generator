<?php

return [
	/**
	 * Your docs directory where you published the vuepress files.
	 */
	'docs_dir' => __DIR__.'/../src-docs',

	/**
	 * List of your app source directories to scan for endpoints.
	 * Use your directory as key and your namespace as value.
	 */
	'scan_directories' => [
		// __DIR__ => 'App',
	],

	'options' => [
		/**
		 * If true, the generator will generate for every resource a single page.
		 * If false, all resources will be generated together as a single page.
		 */
		'generate_separate_resource_pages' => true,

		/**
		 * If true, the generator will generate all properties of a schema in the response body.
		 * If false, the generator use the schema name as response type.
		 */
		'resolve_schema_in_response' => true,

		/**
		 * option if generate_separate_resource_pages is true
		 */
		'append_resources_table_in_single_page' => true,

		'clear_directory_before_generate' => true,
	],

	'defaults' => [
		'property_response_description' => 'No description',
		'property_query_description' => 'No description',
		'property_body_description' => 'No description',
	],
];