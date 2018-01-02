<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Tests\Block\Service;

use Core23\PiwikBundle\Block\Service\PiwikTrackerBlockService;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Test\AbstractBlockServiceTestCase;

class PiwikTrackerBlockServiceTest extends AbstractBlockServiceTestCase
{
    public function testExecute(): void
    {
        $block = new Block();

        $blockContext = new BlockContext($block, [
            'host'        => null,
            'site'        => false,
            'domaintitle' => false,
            'donottrack'  => false,
            'nocookies'   => false,
            'template'    => '@Core23Piwik/Block/block_piwik_tracker.html.twig',
        ]);

        $blockService = new PiwikTrackerBlockService('block.service', $this->templating);
        $blockService->execute($blockContext);

        $this->assertSame('@Core23Piwik/Block/block_piwik_tracker.html.twig', $this->templating->view);

        $this->assertSame($blockContext, $this->templating->parameters['context']);
        $this->assertInternalType('array', $this->templating->parameters['settings']);
        $this->assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
    }

    public function testDefaultSettings(): void
    {
        $blockService = new PiwikTrackerBlockService('block.service', $this->templating);
        $blockContext = $this->getBlockContext($blockService);

        $this->assertSettings([
            'host'        => null,
            'site'        => false,
            'domaintitle' => false,
            'donottrack'  => false,
            'nocookies'   => false,
            'template'    => '@Core23Piwik/Block/block_piwik_tracker.html.twig',
        ], $blockContext);
    }
}
