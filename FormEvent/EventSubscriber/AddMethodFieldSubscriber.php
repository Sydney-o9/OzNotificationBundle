<?php

namespace Oz\NotificationBundle\FormEvent\EventSubscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityRepository;



class AddMethodFieldSubscriber implements EventSubscriberInterface
{

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * Class for the Query Builder
     *
     * @var string $methodClass
     */
    private $methodClass;


    public function __construct(FormFactoryInterface $factory, $methodClass)
    {
        $this->factory = $factory;

        $this->methodClass = $methodClass;

    }

    /**
     * Tells the dispatcher that we want to listen to the form.pre_set_data
     * event and that the preSetData method should be called.
     *
     *
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();

        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        if ($data) {
            //Retrieve methods to the particular Filter <----> NotificationKey
            $form->add($this->factory->createNamed('methods', 'entity', null, array(
                    'class' => $this->methodClass,
                    'multiple' => true,
                    'expanded' => true,
                    'query_builder' => function(EntityRepository $er) use ($data) {
                        $query = $er->createQueryBuilder('met')
                            ->select(array('met'))
                            ->leftJoin('met.methodMetadata', 'mm')
                            ->leftJoin('mm.notificationKey', 'nk')
                            ->where('mm.isCompulsory = :isCompulsory')
                            ->andWhere('nk.key = :key')
                            ->setParameter('isCompulsory', false)
                            ->setParameter('key', (string)$data->getNotificationKey());

                        return $query;

                    },
                )
            ));

        }
    }
}
