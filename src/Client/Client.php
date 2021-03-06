<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Client;

use Core23\PiwikBundle\Connection\ConnectionInterface;
use Core23\PiwikBundle\Exception\PiwikException;

final class Client implements ClientInterface
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
    public function __construct(ConnectionInterface $connection, string $token = 'anonymous')
    {
        $this->connection = $connection;
        $this->setToken($token);
    }

    /**
     * {@inheritdoc}
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function call(string $method, array $params = [], $format = 'php')
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
     * {@inheritdoc}
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
