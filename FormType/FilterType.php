<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Oz\NotificationBundle\FormEvent\EventSubscriber\AddMethodFieldSubscriber;
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

        /** The notificationKey associated with that filter */
        $builder->add( 'notificationKey', 'entity', array(
            'class' => $this->notificationKeyClass,
            'disabled' => true,
            )
        );

        /** The methods that are associated with that filter */
        $subscriber = new AddMethodFieldSubscriber($builder->getFormFactory(),  $this->methodClass);
        $builder->addEventSubscriber($subscriber);

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
        ));
    }

    public function getName()
    {
        return 'oz_notification_user_preferences_filter';
    }
}
