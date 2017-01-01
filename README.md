What is PiwikBundle?
=============================
[![Latest Stable Version](https://poser.pugx.org/core23/piwik-bundle/v/stable)](https://packagist.org/packages/core23/piwik-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/piwik-bundle/v/unstable)](https://packagist.org/packages/core23/piwik-bundle)
[![Build Status](http://img.shields.io/travis/core23/PiwikBundle.svg)](http://travis-ci.org/core23/PiwikBundle)
[![License](http://img.shields.io/packagist/l/core23/piwik-bundle.svg)](https://packagist.org/packages/core23/piwik-bundle)


[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/core23)
[![Donate to this project using PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://paypal.me/gripp)

This bundle provides a wrapper for using the [piwik] statistic inside the symfony sonata-project.

### Installation

```
php composer.phar require core23/piwik-bundle
php composer.phar require php-http/guzzle6-adapter # if you want to use Guzzle
```

### Enabling the bundle

```php
    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            
            new Http\HttplugBundle\HttplugBundle(),
            new Core23\PiwikBundle\Core23PiwikBundle(),

            // ...
        );
    }
```

Define a [HTTPlug] client in your configuration.

```yml
    config.yml
    
    httplug:
        classes:
            client: Http\Adapter\Guzzle6\Client
            message_factory: Http\Message\MessageFactory\GuzzleMessageFactory
            uri_factory: Http\Message\UriFactory\GuzzleUriFactory
            stream_factory: Http\Message\StreamFactory\GuzzleStreamFactory
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

[HTTPlug]: http://docs.php-http.org/en/latest/index.html
[piwik]: https://piwik.org
