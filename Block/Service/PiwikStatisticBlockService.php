<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\PiwikBundle\Block\Service;

use Core23\PiwikBundle\Client\ClientFactoryInterface;
use Core23\PiwikBundle\Exception\PiwikException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PiwikStatisticBlockService extends AbstractAdminBlockService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ClientFactoryInterface
     */
    protected $factory;

    /**
     * PiwikStatisticBlockService constructor.
     *
     * @param string                 $name
     * @param EngineInterface        $templating
     * @param ClientFactoryInterface $factory
     * @param LoggerInterface        $logger
     */
    public function __construct(string $name, EngineInterface $templating, ClientFactoryInterface $factory, LoggerInterface $logger = null)
    {
        parent::__construct($name, $templating);

        if (null === $logger) {
            $logger = new NullLogger();
        } else {
            @trigger_error('Injecting a logger in the constructor method is depreacted', E_USER_DEPRECATED);
        }

        $this->factory = $factory;
        $this->logger  = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse($blockContext->getTemplate(), array(
            'context'  => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block'    => $blockContext->getBlock(),
            'data'     => $this->getData($blockContext->getSettings()),
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', ImmutableArrayType::class, array(
            'keys' => array(
                array('title', TextType::class, array(
                    'required' => false,
                    'label'    => 'form.label_title',
                )),
                array('host', TextType::class, array(
                    'required' => false,
                    'label'    => 'form.label_host',
                )),
                array('token', TextType::class, array(
                    'required' => false,
                    'label'    => 'form.label_token',
                )),
                array('site', NumberType::class, array(
                    'label' => 'form.label_site',
                )),
                array('method', ChoiceType::class, array(
                    'choices' => array(
                        'form.choice_visitors'        => 'VisitsSummary.getVisits',
                        'form.choice_unique_visitors' => 'VisitsSummary.getUniqueVisitors',
                        'form.choice_hits'            => 'VisitsSummary.getActions ',
                    ),
                    'choices_as_values' => true,
                    'label'             => 'form.label_method',
                )),
                array('period', ChoiceType::class, array(
                    'choices' => array(
                        'form.choice_day'   => 'day',
                        'form.choice_week'  => 'week',
                        'form.choice_month' => 'month',
                        'form.choice_year'  => 'year',
                    ),
                    'choices_as_values' => true,
                    'label'             => 'form.label_period',
                )),
                array('date', ChoiceType::class, array(
                    'choices' => array(
                        'form.choice_today'    => 'last1',
                        'form.choice_1_week'   => 'last7',
                        'form.choice_2_weeks'  => 'last14',
                        'form.choice_1_month'  => 'last30',
                        'form.choice_3_months' => 'last90',
                        'form.choice_6_months' => 'last180',
                        'form.choice_1_year'   => 'last360',
                    ),
                    'choices_as_values' => true,
                    'label'             => 'form.label_date',
                )),
            ),
            'translation_domain' => 'Core23PiwikBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'Piwik Statistic',
            'site'     => false,
            'method'   => 'VisitsSummary.getVisits',
            'host'     => null,
            'token'    => null,
            'period'   => 'day',
            'date'     => 'last30',
            'template' => 'Core23PiwikBundle:Block:block_piwik_statistic.html.twig',
        ));

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
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (null !== $code ? $code : $this->getName()), false, 'Core23PiwikBundle', array(
            'class' => 'fa fa-area-chart',
        ));
    }

    /**
     * @param array $settings
     *
     * @return array|null
     */
    protected function getData($settings = array())
    {
        try {
            $client = $this->factory->createPiwikClient($settings['host'], $settings['token']);

            $response = $client->call($settings['method'], array(
                'idSite' => $settings['site'],
                'period' => $settings['period'],
                'date'   => $settings['date'],
            ));

            return $response;
        } catch (PiwikException $ce) {
            $this->logger->warning('Error retrieving Piwik url: '.$settings['host'], array(
                'exception' => $ce,
            ));
        }

        return null;
    }
}
