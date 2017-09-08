<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Tests\DependencyInjection;

use Core23\PiwikBundle\DependencyInjection\Core23PiwikExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class Core23PiwikExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault()
    {
        $this->setParameter('kernel.bundles', array());
        $this->load();

        $this->assertContainerBuilderHasAlias('core23.piwik.http.client', 'httplug.client.default');
        $this->assertContainerBuilderHasAlias('core23.piwik.http.message_factory', 'httplug.message_factory.default');
    }

    protected function getContainerExtensions(): array
    {
        return array(
            new Core23PiwikExtension(),
        );
    }
}
