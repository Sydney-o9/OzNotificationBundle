<?php

namespace Oz\NotificationBundle\FormType\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MethodMetadataType extends AbstractType
{
    /**
     * @var string
     */
    private $methodClass;
    /**
     * @var string
     */
    private $methodMetadataclass;

    /**
     * @var string
     */
    private $notificationKeyClass;

    /**
     * @param string $methodClass
     * @param string $methodMetadataClass
     * @param string $notificationKeyClass
     */
    public function __construct($methodClass, $methodMetadataClass, $notificationKeyClass)
    {
        $this->methodClass = $methodClass;
        $this->methodMetadataClass = $methodMetadataClass;
        $this->notificationKeyClass = $notificationKeyClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('method');
        $builder->add('isDefault', CheckboxType::class, array(
            'label'    => 'This method is used by default',
            'required' => false
        ));
        $builder->add('isCompulsory', CheckboxType::class, array(
            'label'    => 'This method is compulsory',
            'required' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->methodMetadataClass,
        ));
    }

    public function getName()
    {
        return 'oz_notification_admin_method_metadata';
    }

}
