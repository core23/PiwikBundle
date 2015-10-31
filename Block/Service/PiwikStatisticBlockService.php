<?php

/*
 * This file is part of the ni-ju-san CMS.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Block\Service;

use Core23\CoreBundle\Block\Service\BaseBlockService;
use Core23\PiwikBundle\Client\Client;
use Core23\PiwikBundle\Connection\PiwikConntection;
use Core23\PiwikBundle\Exception\PiwikException;
use Psr\Log\LoggerInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PiwikStatisticBlockService extends BaseBlockService
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param string          $name
     * @param EngineInterface $templating
     * @param LoggerInterface $logger
     */
    public function __construct($name, EngineInterface $templating, LoggerInterface $logger)
    {
        $this->logger    = $logger;

        parent::__construct($name, $templating);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'context'    => $blockContext,
                'settings'   => $blockContext->getSettings(),
                'block'      => $blockContext->getBlock(),
                'data'       => $this->getData($blockContext->getSettings()),
            ),
            $response
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add(
            'settings',
            'sonata_type_immutable_array',
            array(
                'keys'               => array(
                    array('title', 'text', array('required' => false, 'label' => 'form.label_title')),
                    array('host', 'text', array('required'  => false, 'label' => 'form.label_host')),
                    array('token', 'text', array('required' => false, 'label' => 'form.label_token')),
                    array('site', 'number', array('label'   => 'form.label_site')),
                    array('method', 'choice', array(
                        'choices' => array(
                            'VisitsSummary.getVisits'         => 'form.choice_visitors',
                            'VisitsSummary.getUniqueVisitors' => 'form.choice_unique_visitors',
                            'VisitsSummary.getActions '       => 'form.choice_hits',
                        ),
                        'label'   => 'form.label_method',
                    )),
                    array('period', 'choice', array(
                        'choices' => array(
                            'day'   => 'form.choice_day',
                            'week'  => 'form.choice_week',
                            'month' => 'form.choice_month',
                            'year'  => 'form.choice_year',
                        ),
                        'label'   => 'form.label_period',
                    )),
                    array('date', 'choice', array(
                        'choices' => array(
                            'last1'   => 'form.choice_today',
                            'last7'   => 'form.choice_1_week',
                            'last14'  => 'form.choice_2_weeks',
                            'last30'  => 'form.choice_1_month',
                            'last90'  => 'form.choice_3_months',
                            'last180' => 'form.choice_6_months',
                            'last360' => 'form.choice_1_year',
                        ),
                        'label'   => 'form.label_date',
                    )),
                ),
                'translation_domain' => 'Core23PiwikBundle',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'title'    => 'Piwik Statistic',
                'site'     => false,
                'method'   => 'VisitsSummary.getVisits',
                'host'     => null,
                'token'    => null,
                'period'   => 'day',
                'date'     => 'last30',
                'template' => 'Core23PiwikBundle:Block:block_piwik_statistic.html.twig',
            )
        );

        $resolver->setRequired(array('site', 'host', 'token'));
    }

    /**
     * {@inheritdoc}
     */
    public function getJavascripts($media)
    {
        return array(
            '/assets/js/chartist.js',
            '/assets/js/jquery.piwikTable.js',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getStylesheets($media)
    {
        return array(
            '/assets/css/chartist.css',
        );
    }

    /**
     * @param array $settings
     *
     * @return mixed|null
     */
    protected function getData($settings = array())
    {
        $host  = $settings['host'];
        $token = $settings['token'];

        try {
            $connection = new PiwikConntection($host);
            $client     = new Client($connection, $token);

            $response = $client->call(
                $settings['method'],
                array(
                    'idSite' => $settings['site'],
                    'period' => $settings['period'],
                    'date'   => $settings['date'],
                )
            );

            return $response;
        } catch (PiwikException $ce) {
            $this->logger->warning('Error retrieving Piwik url: '.$host);
        }

        return;
    }
}
