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

use merk\NotificationBundle\Sender\SenderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use merk\NotificationBundle\Form\EventListener\AddMethodFieldSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterType extends AbstractType
{
    private $class;

    private $sender;

    /**
     * @param string $class
     * @param \merk\NotificationBundle\Sender\SenderInterface $sender
     */
    public function __construct($class, SenderInterface $sender)
    {
        $this->class = $class;
        $this->sender = $sender;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new AddMethodFieldSubscriber($builder->getFormFactory());

        $builder->addEventSubscriber($subscriber);

        $builder->add('notificationKey', null);

        //TYPE 1: CHOICE
        $builder->add('method', 'choice', array(
            'choices' => $this->getMethodChoices(),
            'multiple' => false,
            'expanded' => true
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
        return 'merk_notification_user_preferences_filter';
    }
}