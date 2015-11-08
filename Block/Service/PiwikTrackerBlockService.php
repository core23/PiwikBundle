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
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PiwikTrackerBlockService extends BaseBlockService
{
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
                    array('host', 'text', array('required'           => false, 'label' => 'form.label_host')),
                    array('site', 'number', array('label'            => 'form.label_site')),
                    array('domaintitle', 'checkbox', array('label'   => 'form.label_domaintitle', 'required' => false)),
                    array('nocookies', 'checkbox', array('label'     => 'form.label_nocookies', 'required' => false)),
                    array('donottrack', 'checkbox', array('label'    => 'form.label_donottrack', 'required' => false)),
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
                'host'        => null,
                'site'        => false,
                'domaintitle' => false,
                'donottrack'  => false,
                'nocookies'   => false,
                'template'    => 'Core23PiwikBundle:Block:block_piwik_tracker.html.twig',
            )
        );

        $resolver->setRequired(array('site', 'host'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (!is_null($code) ? $code : $this->getName()), false, 'Core23PiwikBundle', array('class' => 'fa fa-code'));
    }
}
