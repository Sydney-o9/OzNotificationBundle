<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\FormType\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotificationKeyType extends AbstractType
{
    private $class;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('notificationKey', 'text');
        $builder->add('subjectClass', 'text');
        $builder->add('description', 'textarea');
        $builder->add('isBulkable', CheckboxType::class, array(
            'label'    => 'This notification be sent in bulk',
            'required' => false,
            'data' => true
        ));
        $builder->add('isSubscribable', CheckboxType::class, array(
            'label'    => 'User can subscribe to this notification',
            'required' => false,
            'data' => true
        ));
        $builder->add('methodMetadata', 'collection', array(
            'type' => 'oz_notification_admin_method_metadata',
            'label' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
        ));
    }

    public function getName()
    {
        return 'oz_notification_admin_notification_key';
    }
}
