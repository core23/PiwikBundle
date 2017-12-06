<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Client;

use Core23\PiwikBundle\Connection\PiwikConnection;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;

final class ClientFactory implements ClientFactoryInterface
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * Initialize client.
     *
     * @param HttpClient     $client
     * @param MessageFactory $messageFactory
     */
    public function __construct(HttpClient $client, MessageFactory $messageFactory)
    {
        $this->client         = $client;
        $this->messageFactory = $messageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createPiwikClient(string $host, string $token): ClientInterface
    {
        $connection = new PiwikConnection($this->client, $this->messageFactory, $host);

        return new Client($connection, $token);
    }
}
