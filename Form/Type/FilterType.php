<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use merk\NotificationBundle\Form\EventListener\AddMethodFieldSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $methodClass;

    /**
     * @param string $class
     * @param string $methodClass
     * @param string $notificationKeyClass
     */
    public function __construct($class, $methodClass, $notificationKeyClass)
    {
        $this->class = $class;
        $this->methodClass = $methodClass;
        $this->notificationKeyClass = $notificationKeyClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $subscriber = new AddMethodFieldSubscriber($builder->getFormFactory(),  $this->methodClass);

        $builder->addEventSubscriber($subscriber);

        $builder->add('notificationKey', 'entity', array('class'=>$this->notificationKeyClass));



    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
        ));
    }

    public function getName()
    {
        return 'merk_notification_user_preferences_filter';
    }
}