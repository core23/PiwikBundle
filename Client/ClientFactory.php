<?php

/*
 * This file is part of the ni-ju-san CMS.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Client;

use Core23\PiwikBundle\Connection\PiwikConnection;

class ClientFactory
{
    /**
     * @param string $host
     * @param string $token
     *
     * @return Client
     */
    public function createPiwikClient($host, $token)
    {
        $connection = new PiwikConnection($host);

        return new Client($connection, $token);
    }
}
