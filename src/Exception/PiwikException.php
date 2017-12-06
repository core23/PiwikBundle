<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Exception;

final class PiwikException extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        $message = 'Piwik API error: '.$message;
        parent::__construct($message);
    }
}
