<?php

/*
 * This file is part of the ni-ju-san CMS.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Connection;

use Core23\PiwikBundle\Exception\PiwikException;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;

final class PiwikConnection implements ConnectionInterface
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
     * @var string
     */
    private $apiUrl;

    /**
     * Initialize client.
     *
     * @param HttpClient     $client
     * @param MessageFactory $messageFactory
     * @param string         $apiUrl         base API URL
     */
    public function __construct(HttpClient $client, MessageFactory $messageFactory, $apiUrl)
    {
        $this->apiUrl         = $apiUrl;
        $this->client         = $client;
        $this->messageFactory = $messageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function send(array $params = array())
    {
        $params['module'] = 'API';

        $url      = $this->apiUrl.'?'.$this->getUrlParamString($params);
        $request  = $this->messageFactory->createRequest('GET', $url);
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new PiwikException(sprintf('"%s" returned an invalid status code: "%s"', $url, $response->getStatusCode()));
        }

        return $response->getBody()->getContents();
    }

    /**
     * Converts a set of parameters to a query string.
     *
     * @param array $params
     *
     * @return string query string
     */
    protected function getUrlParamString(array $params)
    {
        $query = array();
        foreach ($params as $key => $val) {
            if (is_array($val)) {
                $val = implode(',', $val);
            } elseif ($val instanceof \DateTime) {
                $val = $val->format('Y-m-d');
            } elseif (is_bool($val)) {
                if ($val) {
                    $val = 1;
                } else {
                    continue;
                }
            } else {
                $val = urlencode($val);
            }
            $query[] = $key.'='.$val;
        }

        return implode('&', $query);
    }
}
