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

use Core23\PiwikBundle\Connection\ConnectionInterface;
use Core23\PiwikBundle\Exception\PiwikException;

class Client
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var string
     */
    private $token;

    /**
     * Initialize Piwik client.
     *
     * @param ConnectionInterface $connection Piwik active connection
     * @param string              $token      auth token
     */
    public function __construct(ConnectionInterface $connection, $token = 'anonymous')
    {
        $this->connection = $connection;
        $this->setToken($token);
    }

    /**
     * Set Piwik API token.
     *
     * @param string $token auth token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

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
    public function call($method, array $params = array(), $format = 'php')
    {
        $params['method']     = $method;
        $params['token_auth'] = $this->token;
        $params['format']     = $format;
        $data                 = $this->getConnection()->send($params);

        if ('php' === $format) {
            $object = unserialize($data);

            if (isset($object['result']) && 'error' === $object['result']) {
                throw new PiwikException($object['message']);
            }

            return $object;
        }

        return $data;
    }

    /**
     * Return active connection.
     *
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
