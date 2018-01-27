PiwikBundle
===========
[![Latest Stable Version](https://poser.pugx.org/core23/piwik-bundle/v/stable)](https://packagist.org/packages/core23/piwik-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/piwik-bundle/v/unstable)](https://packagist.org/packages/core23/piwik-bundle)
[![License](https://poser.pugx.org/core23/piwik-bundle/license)](https://packagist.org/packages/core23/piwik-bundle)

[![Total Downloads](https://poser.pugx.org/core23/piwik-bundle/downloads)](https://packagist.org/packages/core23/piwik-bundle)
[![Monthly Downloads](https://poser.pugx.org/core23/piwik-bundle/d/monthly)](https://packagist.org/packages/core23/piwik-bundle)
[![Daily Downloads](https://poser.pugx.org/core23/piwik-bundle/d/daily)](https://packagist.org/packages/core23/piwik-bundle)

[![Build Status](https://travis-ci.org/core23/PiwikBundle.svg)](https://travis-ci.org/core23/PiwikBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/core23/PiwikBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/core23/PiwikBundle)
[![Code Climate](https://codeclimate.com/github/core23/PiwikBundle/badges/gpa.svg)](https://codeclimate.com/github/core23/PiwikBundle)
[![Coverage Status](https://coveralls.io/repos/core23/PiwikBundle/badge.svg)](https://coveralls.io/r/core23/PiwikBundle)

[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/core23)
[![Donate to this project using PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://paypal.me/gripp)

This bundle provides a wrapper for using the [matomo] (Piwik) statistic inside the symfony sonata-project.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
composer require core23/piwik-bundle
composer require php-http/guzzle6-adapter # if you want to use Guzzle
```

### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Http\HttplugBundle\HttplugBundle::class     => ['all' => true],
    Core23\PiwikBundle\Core23PiwikBundle::class => ['all' => true],
];
```

## Usage

Define a [HTTPlug] client in your configuration.

```yaml
# config/packages/httplug.yaml

httplug:
    classes:
        client: Http\Adapter\Guzzle6\Client
        message_factory: Http\Message\MessageFactory\GuzzleMessageFactory
        uri_factory: Http\Message\UriFactory\GuzzleUriFactory
        stream_factory: Http\Message\StreamFactory\GuzzleStreamFactory
```

```twig
{# template.twig #}

{{ sonata_block_render({ 'type': 'core23_piwik.block.statistic' }, {
    'host': 'http://matomo.example.com',
    'site': 1,
    'token': 'MATOMO_API_TOKEN'
}) }}
```

## License

This bundle is under the [MIT license](LICENSE.md).

[HTTPlug]: http://docs.php-http.org/en/latest/index.html
[matomo]: https://matomo.org
