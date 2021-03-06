<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Connection;

interface ConnectionInterface
{
    /**
     * Calls specific method on Piwik API.
     *
     * @param array $params parameters (associative array)
     *
     * @return string response
     */
    public function send(array $params = []): string;
}
