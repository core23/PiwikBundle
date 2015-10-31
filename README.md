What is PiwikBundle?
=============================
[![Latest Stable Version](http://img.shields.io/packagist/v/core23/piwik-bundle.svg)](https://packagist.org/packages/core23/piwik-bundle)
[![Build Status](http://img.shields.io/travis/core23/PiwikBundle.svg)](http://travis-ci.org/core23/PiwikBundle)
[![Latest Stable Version](https://poser.pugx.org/core23/piwik-bundle/v/stable.png)](https://packagist.org/packages/core23/piwik-bundle)
[![License](http://img.shields.io/packagist/l/core23/piwik-bundle.svg)](https://packagist.org/packages/core23/piwik-bundle)

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
