<?php

namespace merk\NotificationBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use merk\NotificationBundle\ModelManager\NotificationEventManager as BaseNotificationEventManager;
use merk\NotificationBundle\Model\NotificationInterface as BaseNotificationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Doctrine ORM listener updating the NotificationEvent subject.
 *
 */
class NotificationListener implements EventSubscriber
{
    /**
     * @var BaseNotificationEventManager
     */
    private $notificationEventManager;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;

    }

    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
        );
    }

    public function postLoad(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if ($entity instanceof BaseNotificationInterface) {

            if (null === $this->notificationEventManager) {
                $this->notificationEventManager = $this->container->get('merk_notification.notification_event.manager');
            }

            $this->notificationEventManager->replaceSubject($entity->getEvent());
        }
    }

}