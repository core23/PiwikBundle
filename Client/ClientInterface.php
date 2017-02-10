<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Client;

use Core23\PiwikBundle\Connection\ConnectionInterface;
use Core23\PiwikBundle\Exception\PiwikException;

interface ClientInterface
{
    /**
     * Set Piwik API token.
     *
     * @param string $token auth token
     */
    public function setToken(string $token);

    /**
     * Call specific method & return it's response.
     *
     * @param string $method method name
     * @param array  $params method parameters
     * @param string $format return format (php, json, xml, csv, tsv, html, rss)
     *
     * @return mixed
     *
     * @throws PiwikException
     */
    public function call(string $method, array $params = array(), $format = 'php');

    /**
     * Return active connection.
     *
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface;
}
