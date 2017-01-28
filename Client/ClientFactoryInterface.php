<?php

namespace Core23\PiwikBundle\Client;

interface ClientFactoryInterface
{
    /**
     * Creates new Piwik Client
     *
     * @param string $host
     * @param string $token
     *
     * @return ClientInterface
     */
    public function createPiwikClient($host, $token);
}
