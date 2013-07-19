<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Oz\NotificationBundle\Model\NotificationEventInterface as BaseNotificationEventInterface;
use Oz\NotificationBundle\ModelManager\NotificationEventManager as BaseNotificationEventManager;
use Oz\NotificationBundle\Model\NotificationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * TODO: rename to Subscriber.
 *
 * Doctrine ORM listener updating the NotificationEvent subject.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class NotificationEventListener implements EventSubscriber
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
            Events::prePersist,
            Events::preUpdate,
            Events::postLoad,
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->handlePersistEvents($args);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->handlePersistEvents($args);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof BaseNotificationEventInterface) {
            if (null === $this->notificationEventManager) {
                $this->notificationEventManager = $this->container->get('oz_notification.notification_event.manager');
            }

            $this->notificationEventManager->replaceSubject($entity);
        }

    }

    private function handlePersistEvents(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof BaseNotificationEventInterface) {
            if (null === $this->notificationEventManager) {
                $this->notificationEventManager = $this->container->get('oz_notification.notification_event.manager');
            }

            $this->notificationEventManager->updateSubject($entity);

            if ($args instanceof PreUpdateEventArgs) {
                // We are doing a update, so we must force Doctrine to update the
                // changeset in case we changed something above
                $em   = $args->getEntityManager();
                $uow  = $em->getUnitOfWork();
                $meta = $em->getClassMetadata(get_class($entity));
                $uow->recomputeSingleEntityChangeSet($meta, $entity);
            }
        }
    }
}
