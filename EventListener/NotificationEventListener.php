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

namespace Oz\NotificationBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Oz\NotificationBundle\Exception\NoSubjectLoadedException;
use Oz\NotificationBundle\Model\NotificationEventInterface as BaseNotificationEventInterface;
use Oz\NotificationBundle\Model\NotificationInterface as BaseNotificationInterface;
use Oz\NotificationBundle\ModelManager\NotificationEventManager as BaseNotificationEventManager;
use Oz\NotificationBundle\Model\NotificationInterface;
use Oz\NotificationBundle\Model\NotifiableInterface;

/**
 * Doctrine ORM listener updating the subject of NotificationEvent objects
 */
class NotificationEventListener implements EventSubscriber
{
    /**
     * @var BaseNotificationEventManager
     */
    private $notificationEventManager;

    /**
     * @var NotificationDeleterInterface
     */
    private $notificationDeleter;

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

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::postLoad,
            Events::postRemove,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->handlePersistEvents($args);
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->handlePersistEvents($args);
    }

    /**
     * {@inheritDoc}
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        /**
         * Every time we fetch an event, we load the subject
         *
         * If subject is retrieved, we add it to the subject field
         * If subject is not retrieved, we set NULL to the subject field
         */
        if ($entity instanceof BaseNotificationEventInterface) {

            if (null === $this->notificationEventManager) {
                $this->notificationEventManager = $this->container->get('oz_notification.notification_event.manager');
            }

            try{
                $this->notificationEventManager->replaceSubject($entity);
            } catch (NoSubjectLoadedException $e) {
                $entity->setSubject(null);
            }

        }

    }

    /**
     * On prePersist and preUpdate events
     */
    private function handlePersistEvents(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof BaseNotificationEventInterface) {
            if (null === $this->notificationEventManager) {
                $this->notificationEventManager = $this->container->get('oz_notification.notification_event.manager');
            }

            $this->notificationEventManager->updateSubject($entity);

            if ($args instanceof PreUpdateEventArgs) {
                /**
                 * We are doing a update, so we must force Doctrine to update the
                 * changeset in case we changed something above
                 */
                $em   = $args->getEntityManager();
                $uow  = $em->getUnitOfWork();
                $meta = $em->getClassMetadata(get_class($entity));
                $uow->recomputeSingleEntityChangeSet($meta, $entity);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        ladybug_dump($entity);

        /**
         * Every time we delete a subject, we delete the notifications that are linked to it
         *
         */
        if ($entity instanceof NotifiableInterface) {

            if (null === $this->notificationDeleter) {
                $this->notificationDeleter = $this->container->get('oz_notification.notification.deleter');
            }

            $this->notificationDeleter
                ->deleteNotificationsWithSubject($entity);

        }

    }
}
