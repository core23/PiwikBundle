<?php

/*
 * This file is part of the ni-ju-san CMS.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class Core23PiwikExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');

        if (isset($bundles['SonataBlockBundle'])) {
            $loader->load('block.xml');
        }

        $this->configureClassesToCompile();
    }

    private function configureClassesToCompile()
    {
        $this->addClassesToCompile(array(
            'Core23\\PiwikBundle\\Block\\Service\\PiwikStatisticBlockService',
            'Core23\\PiwikBundle\\Client\\Client',
            'Core23\\PiwikBundle\\Connection\\ConnectionInterface',
            'Core23\\PiwikBundle\\Connection\\PiwikConnection',
            'Core23\\PiwikBundle\\Exception\\PiwikException',
        ));
    }
}
