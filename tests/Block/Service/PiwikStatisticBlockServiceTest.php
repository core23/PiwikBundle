<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Tests\Block\Service;

use Core23\PiwikBundle\Block\Service\PiwikStatisticBlockService;
use Core23\PiwikBundle\Client\ClientFactoryInterface;
use Core23\PiwikBundle\Client\ClientInterface;
use Core23\PiwikBundle\Exception\PiwikException;
use Psr\Log\LoggerInterface;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Test\AbstractBlockServiceTestCase;

final class PiwikStatisticBlockServiceTest extends AbstractBlockServiceTestCase
{
    private $logger;

    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger  = $this->createMock(LoggerInterface::class);
        $this->factory = $this->createMock(ClientFactoryInterface::class);
    }

    public function testExecute(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())->method('call')
            ->with($this->equalTo('VisitsSummary.getVisits'), $this->equalTo([
                'idSite' => 'foo',
                'period' => 'day',
                'date'   => 'last30',
            ]))
            ->will($this->returnValue(['bar']));

        $this->factory->expects($this->once())->method('createPiwikClient')
            ->will($this->returnValue($client));

        $block = new Block();

        $blockContext = new BlockContext($block, [
            'title'    => 'Piwik Statistic',
            'site'     => 'foo',
            'method'   => 'VisitsSummary.getVisits',
            'host'     => 'localhost',
            'token'    => '0815',
            'period'   => 'day',
            'date'     => 'last30',
            'template' => '@Core23Piwik/Block/block_piwik_statistic.html.twig',
        ]);

        $blockService = new PiwikStatisticBlockService('block.service', $this->templating, $this->factory);
        $blockService->execute($blockContext);

        $this->assertSame('@Core23Piwik/Block/block_piwik_statistic.html.twig', $this->templating->view);

        $this->assertSame($blockContext, $this->templating->parameters['context']);
        $this->assertInternalType('array', $this->templating->parameters['settings']);
        $this->assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
        $this->assertSame(['bar'], $this->templating->parameters['data']);
    }

    public function testExecuteOffline(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())->method('call')
            ->with($this->equalTo('VisitsSummary.getVisits'), $this->equalTo([
                'idSite' => 'foo',
                'period' => 'day',
                'date'   => 'last30',
            ]))
            ->willThrowException(new PiwikException('Offline'));

        $this->factory->expects($this->once())->method('createPiwikClient')
            ->will($this->returnValue($client));

        $this->logger->expects($this->once())->method('warning');

        $block = new Block();

        $blockContext = new BlockContext($block, [
            'title'    => 'Piwik Statistic',
            'site'     => 'foo',
            'method'   => 'VisitsSummary.getVisits',
            'host'     => 'localhost',
            'token'    => '0815',
            'period'   => 'day',
            'date'     => 'last30',
            'template' => '@Core23Piwik/Block/block_piwik_statistic.html.twig',
        ]);

        $blockService = new PiwikStatisticBlockService('block.service', $this->templating, $this->factory);
        $blockService->setLogger($this->logger);
        $blockService->execute($blockContext);

        $this->assertSame('@Core23Piwik/Block/block_piwik_statistic.html.twig', $this->templating->view);

        $this->assertSame($blockContext, $this->templating->parameters['context']);
        $this->assertInternalType('array', $this->templating->parameters['settings']);
        $this->assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
        $this->assertNull($this->templating->parameters['data']);
    }

    public function testDefaultSettings(): void
    {
        $blockService = new PiwikStatisticBlockService('block.service', $this->templating, $this->factory);
        $blockService->setLogger($this->logger);
        $blockContext = $this->getBlockContext($blockService);

        $this->assertSettings([
            'title'    => 'Piwik Statistic',
            'site'     => false,
            'method'   => 'VisitsSummary.getVisits',
            'host'     => null,
            'token'    => null,
            'period'   => 'day',
            'date'     => 'last30',
            'template' => '@Core23Piwik/Block/block_piwik_statistic.html.twig',
        ], $blockContext);
    }
}
