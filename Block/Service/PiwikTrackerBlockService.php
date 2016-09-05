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

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PiwikTrackerBlockService extends AbstractAdminBlockService
{
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse($blockContext->getTemplate(), array(
            'context'  => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block'    => $blockContext->getBlock(),
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', ImmutableArrayType::class, array(
            'keys' => array(
                array('host', TextType::class, array(
                    'required' => false,
                    'label'    => 'form.label_host',
                )),
                array('site', NumberType::class, array(
                    'label' => 'form.label_site',
                )),
                array('domaintitle', CheckboxType::class, array(
                    'label'    => 'form.label_domaintitle',
                    'required' => false,
                )),
                array('nocookies', CheckboxType::class, array(
                    'label'    => 'form.label_nocookies',
                    'required' => false,
                )),
                array('donottrack', CheckboxType::class, array(
                    'label'    => 'form.label_donottrack',
                    'required' => false,
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
            'host'        => null,
            'site'        => false,
            'domaintitle' => false,
            'donottrack'  => false,
            'nocookies'   => false,
            'template'    => 'Core23PiwikBundle:Block:block_piwik_tracker.html.twig',
        ));

        $resolver->setRequired(array('site', 'host'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (!is_null($code) ? $code : $this->getName()), false, 'Core23PiwikBundle', array(
            'class' => 'fa fa-code',
        ));
    }
}
