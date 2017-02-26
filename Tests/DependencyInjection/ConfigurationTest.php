<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Tests\DependencyInjection;

use Core23\PiwikBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testOptions()
    {
        $processor = new Processor();

        $config = $processor->processConfiguration(new Configuration(), array(array(
        )));

        $expected = array(
            'http' => array(
                'client'          => 'httplug.client.default',
                'message_factory' => 'httplug.message_factory.default',
            ),
        );

        $this->assertSame($expected, $config);
    }
}
