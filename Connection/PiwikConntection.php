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
use GuzzleHttp\Client;

class PiwikConntection implements ConnectionInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * Initialize client.
     *
     * @param string $apiUrl base API URL
     * @param Client $client guzzle client
     */
    public function __construct($apiUrl, Client $client = null)
    {
        if (null === $client) {
            $this->client = new Client(array('base_uri' => $apiUrl));
        } else {
            $this->client = $client;
        }
        $this->apiUrl = $apiUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function send(array $params = array())
    {
        $params['module'] = 'API';
        $url              = $this->apiUrl.'?'.$this->getUrlParamString($params);

        $response = $this->client->get($url);

        if (!$response->getStatusCode() === 200) {
            throw new PiwikException(sprintf('"%s" returned an invalid status code: "%s"', $url, $response->getStatusCode()));
        }

        return $response->getBody();
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
