<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use merk\NotificationBundle\Model\FilterInterface;
use merk\NotificationBundle\Model\NotificationEventInterface;
use merk\NotificationBundle\Model\NotificationInterface;
use merk\NotificationBundle\ModelManager\NotificationManager as BaseNotificationManager;
use merk\NotificationBundle\Renderer\RendererInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use merk\NotificationBundle\ModelManager\NotificationDiscriminator;


/**
 * Doctrine ORM implementation of the NotificationManager class.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class NotificationManager extends BaseNotificationManager
{
    protected $em;
    protected $repository;
    protected $class;
    protected $renderer;
    protected $notificationDiscriminator;

    public function __construct(EntityManager $em, $class, RendererInterface $renderer, NotificationDiscriminator $notificationDiscriminator)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $this->renderer = $renderer;

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

        $this->notificationDiscriminator = $notificationDiscriminator;
    }


    /**
     * {@inheritDoc}
     */
    public function createForCommittedUser(NotificationEventInterface $event, FilterInterface $filter)
    {
        /** If user hasn't access to that event, no notifications are created */
        if (!$this->CanUserAccessToEvent($event, $filter->getUserPreferences()->getUser())){return array();}

        $notifications = array();

        $methods = $filter->getMethods();
        $iterator = $methods->getIterator();

        /** Iterate through each method and create a notification*/
        while($method = $iterator->current()){

            /** @var NotificationInterface $notification  */
            $notification = $this->notificationDiscriminator->createNotification($method->getName());

            $notification->setEvent($event);

            $notification->setUser($filter->getUserPreferences()->getUser());
            $notification->setRecipientName($filter->getRecipientName());
            $notification->setRecipientData($filter->getRecipientData());

            $template = $this->renderer->render($notification);
            $notification->setSubject($template['subject']);
            $notification->setMessage($template['body']);

            $notifications[] = $notification;

            $iterator->next();

        }

        return $notifications;
    }

    /**
     * {@inheritDoc}
     */
    public function createForCommittedUsers(NotificationEventInterface $event, array $filters)
    {
        $notificationsForEvent = array();

        /** Iterate through each filter */
        foreach ($filters AS $filter) {

            $notifications = $this->createForCommittedUser($event, $filter);

            foreach ($notifications AS $notification){

                $notificationsForEvent[] = $notification;
            }

        }

        return $notificationsForEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function createForUncommittedUser(NotificationEventInterface $event, UserInterface $user)
    {
        /** If user hasn't access to that event, no notifications are created */
        if (!$this->CanUserAccessToEvent($event, $user)){return array();}

        /** Start generating notifications. */
        $methods = $event->getNotificationKey()->getDefaultMethods()->toArray();

        $notifications = array();

        foreach($methods as $method){

            /** @var NotificationInterface $notification  */
            $notification = $this->notificationDiscriminator->createNotification($method->getName());
            $notification->setEvent($event);

            $notification->setUser($user);
            $notification->setRecipientName($user->getUsername());
            $notification->setRecipientData($user->getEmail());

            $template = $this->renderer->render($notification);
            $notification->setSubject($template['subject']);
            $notification->setMessage($template['body']);

            $notifications[] = $notification;

        }

        return $notifications;
    }


    /**
     * {@inheritDoc}
     */
    public function createForUncommittedUsers(NotificationEventInterface $event, array $users)
    {
        $uncommittedNotifications = array();

        foreach ($users as $user){

            $notifications = $this->createForUncommittedUser($event, $user);

            foreach ($notifications as $notification){

                $uncommittedNotifications[] = $notification;
            }

        }

        return $uncommittedNotifications;
    }


    /**
     * {@inheritDoc}
     */
    public function CanUserAccessToEvent(NotificationEventInterface $event, UserInterface $user){

        /** If notification key is not subscribable, user can not access to that event */
        if (false === $event->getNotificationKey()->isSubscribable() ){
            return false;
        }

        /** If user does not have required roles, user can not access to that event */
        $requiredRoles = $event->getNotificationKey()->getSubscriberRoles();
        $roles = $user->getRoles();

        $canAccess = false;
        foreach ($requiredRoles as $requiredRole){
            if (in_array($requiredRole, $roles)){
                $canAccess = true;
                break;
            }
        }

        return $canAccess;
    }

    /**
     * {@inheritDoc}
     */
    public function update(NotificationInterface $notification, $flush = true)
    {
        $this->em->persist($notification);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function updateBulk(array $notifications, $flush = true)
    {
        foreach ($notifications AS $notification) {

            $this->update($notification, false);
        }

        if ($flush) {
            $this->em->flush();
        }
    }

}