# PHP API Docs Generator

[![GitHub Build Status](https://img.shields.io/github/actions/workflow/status/derpierre65/docs-generator/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/derpierre65/docs-generator/actions)

A docs generator for your php api with vuepress and OAuth API support.

## Table of contents

1. [Installation](#installation)
2. [Publish Assets](#publish-assets)

## Installation

Add the repository to composer `repositories` configuration
```json
{
	"repositories": [
		{
			"type": "github",
			"url": "https://github.com/derpierre65/docs-generator/"
		}
	]
}
```

```
composer require derpierre65/docs-generator
```

## Publish Assets

You can use `php vendor/bin/docs.php init <your source directory for docs>` to publish the vuepress files.  
When you update the package, you can run the command again to overwrite all files from docs-generator (the old files will not be touched).


more soon