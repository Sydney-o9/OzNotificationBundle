<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\FormType;

use Oz\NotificationBundle\Sender\SenderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserPreferencesType extends AbstractType
{
    private $class;
    private $sender;

    /**
     * @param string $class
     * @param \Oz\NotificationBundle\Sender\SenderInterface $sender
     */
    public function __construct($class, SenderInterface $sender)
    {
        $this->class = $class;
        $this->sender = $sender;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('defaultMethod', 'choice', array(
            'choices' => $this->getMethodChoices(),
            'multiple' => false,
            'expanded' => true,
        ));

        $builder->add('filters', 'collection', array(
            'type' => 'oz_notification_user_preferences_filter',
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false,
        ));
    }

    protected function getMethodChoices()
    {
        $choices = $this->sender->getAgentAliases();
        return array_combine($choices, $choices);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
        ));
    }

    public function getName()
    {
        return 'oz_notification_user_preferences';
    }
}
