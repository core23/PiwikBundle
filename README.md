What is PiwikBundle?
=============================
[![Latest Stable Version](https://poser.pugx.org/core23/piwik-bundle/v/stable)](https://packagist.org/packages/core23/piwik-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/piwik-bundle/v/unstable)](https://packagist.org/packages/core23/piwik-bundle)
[![Build Status](http://img.shields.io/travis/core23/PiwikBundle.svg)](http://travis-ci.org/core23/PiwikBundle)
[![Dependency Status](https://www.versioneye.com/php/core23:piwik-bundle/badge.svg)](https://www.versioneye.com/php/core23:piwik-bundle)
[![License](http://img.shields.io/packagist/l/core23/piwik-bundle.svg)](https://packagist.org/packages/core23/piwik-bundle)


[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/core23)
[![Donate to this project using PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://paypal.me/gripp)

This bundle provides a wrapper for using the [piwik] statistic inside the symfony sonata-project.

### Installation

```
php composer.phar require core23/piwik-bundle
```

### Enabling the bundle

```php
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
