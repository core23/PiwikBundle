<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Client;

interface ClientFactoryInterface
{
    /**
     * Creates new Piwik Client.
     *
     * @param string $host
     * @param string $token
     *
     * @return ClientInterface
     */
    public function createPiwikClient(string $host, string $token): ClientInterface;
}
