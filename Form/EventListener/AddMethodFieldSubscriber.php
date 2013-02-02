<?php

namespace merk\NotificationBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityRepository;



class AddMethodFieldSubscriber implements EventSubscriberInterface
{

    private $factory;


    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;

    }


    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        if ($data) {

            //The hole Method Entity (Works the best but can't choose specific methods for each filter)
//            $form->add($this->factory->createNamed('methods', 'entity', null, array(
//                    'class' => 'AcmeNotificationBundle:Method',
//                    'multiple' => true,
//                    'expanded' => true,
//                )
//            ));


            //Retrieve methods to the particular Filter <----> NotificationKey
            $form->add($this->factory->createNamed('methods', 'entity', null, array(
                    'class' => 'AcmeNotificationBundle:Method',
                    'multiple' => true,
                    'expanded' => true,
                'query_builder' => function(EntityRepository $er) use ($data) {
                    $query = $er->createQueryBuilder('met')
                        ->select(array('met'))
                        ->leftJoin('met.notificationKeys', 'nk')
                        ->andWhere('nk.notificationKey = :key')
                        ->setParameter('key', (string)$data->getNotificationKey());

                    return $query;

                },
                )
            ));


        }
    }
}