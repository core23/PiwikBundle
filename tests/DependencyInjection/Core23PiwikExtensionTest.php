<?php

declare(strict_types=1);

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
    public function testLoadDefault(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->load();

        $this->assertContainerBuilderHasAlias('core23_piwik.http.client', 'httplug.client.default');
        $this->assertContainerBuilderHasAlias('core23_piwik.http.message_factory', 'httplug.message_factory.default');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new Core23PiwikExtension(),
        ];
    }
}
