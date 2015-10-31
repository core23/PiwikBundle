What is PiwikBundle?
=============================
[![Build Status](https://secure.travis-ci.org/core23/PiwikBundle.png?branch=master)](http://travis-ci.org/core23/PiwikBundle)

This bundle provides a wrapper for using the [piwik] statistic inside the symfony sonata-project.

### Installation

```
php composer.phar require core23/piwik-bundle
```

### Enabling the bundle

```php
<?php
// app/AppKernel.php

	public function registerBundles()
	{
		return array(
			// ...

			new Core23\PiwikBundle\Core23PiwikBundle(),

			// ...
		);
	}
```

### Usage

```twig
{# template.twig #}
{{ sonata_block_render({ 'type': 'core23.piwik.block.statistic' }, {
    'host': 'http://piwik.example.com',
    'site': 1,
	'token': 'PIWIK_API_TOKEN'
}) }}
```

This bundle is available under the [MIT license](LICENSE.md).

[piwik]: https://piwik.org
