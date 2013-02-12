<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\Notifier;

use merk\NotificationBundle\ModelManager\NotificationManagerInterface;
use merk\NotificationBundle\ModelManager\NotificationEventManagerInterface;
use merk\NotificationBundle\Sender\SenderInterface;
use \merk\NotificationBundle\ModelManager\FilterManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;

/**
 * Notifier service.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class Notifier implements NotifierInterface
{
    /**
     * @var NotificationEventManagerInterface
     */
    protected $notificationEventManager;

    /**
     * @var NotificationManagerInterface
     */
    protected $notificationManager;

    /**
     * @var FilterManagerInterface
     */
    protected $filterManager;

    /**
     * @var SenderInterface
     */
    protected $sender;

    /**
     * @param NotificationEventManagerInterface $notificationEventManager
     * @param FilterManagerInterface $filterManager
     * @param NotificationManagerInterface $notificationManager
     * @param SenderInterface $sender
     */
    public function __construct(NotificationEventManagerInterface $notificationEventManager, FilterManagerInterface $filterManager, NotificationManagerInterface $notificationManager, SenderInterface $sender)
    {
        $this->notificationEventManager = $notificationEventManager;
        $this->notificationManager = $notificationManager;
        $this->filterManager = $filterManager;
        $this->sender = $sender;
    }


    /**
     * {@inheritDoc}
     */
    public function triggerSingleNotification($notificationKey, UserInterface $receiver, $verb, $subject, UserInterface $actor = null, DateTime $createdAt = null)
    {
        $this->trigger($notificationKey, $receiver, $verb, $subject, $actor, $createdAt);
    }


    /**
     * {@inheritDoc}
     */
    public function trigger($notificationKey, UserInterface $receiver, $verb, $subject, UserInterface $actor = null, DateTime $createdAt = null)
    {
        $event = $this->notificationEventManager->create($notificationKey, $subject, $verb, $actor, $createdAt);

        /** If the receiver has a filter (subscribed to that event) */
        if ($filter = $this->filterManager->getFilterForEventOwnedBySingleReceiver($event, $receiver)){
            echo "The user has a filter. <br />";
            $notifications = $this->notificationManager->createForEvent($event, $filter);
        }

        /** If the receiver hasn't subscribed, generate default notification */
        else{
            echo "The user has no filters. <br />";
            $notifications = $this->notificationManager->createDefaultNotificationsForUser($event, $receiver);
        }

        $this->sender->send($notifications);

        $this->notificationEventManager->update($event, false);

        $this->notificationManager->updateBulk($notifications);

    }

    /**
     * {@inheritDoc}
     */
    public function triggerBulkNotification($notificationKey, $verb, $subject, UserInterface $actor = null, DateTime $createdAt = null)
    {

        $event = $this->notificationEventManager->create($notificationKey, $subject, $verb, $actor, $createdAt);

        $filters = $this->filterManager->getFiltersForEvent($event);

        $notifications = $this->generateNotificationForAllUsers($event, $filters);

        $this->sender->send($notifications);

        $this->notificationEventManager->update($event, false);

        $this->notificationManager->updateBulk($notifications);

    }

    /**
     * Generate notifications for All Users: Committed and Uncommitted to a filter/Notification key
     *
     */
    public function generateNotificationForAllUsers($event, $filters){

        $committedNotifications = $this->generateNotificationForCommittedUsers($event, $filters);

        $uncommittedNotifications = $this->generateNotificationForUncommittedUsers($event);

        $notifications = array_merge($committedNotifications, $uncommittedNotifications);

        return $notifications;
    }

    /**
     * Generate notifications for Users Committed to a filter/Notification key
     *
     */
    public function generateNotificationForCommittedUsers($event, $filters){

        $committedNotifications = $this->notificationManager->createForEvent($event, $filters);

        return $committedNotifications;

    }

    /**
     * Generate notifications for Users Uncommitted to a filter/Notification key
     * Todo: move the logic to notificationManager
     *
     */
    public function generateNotificationForUncommittedUsers($event){


        $users = $this->filterManager->getUncommittedUsers($event->getNotificationKey());

        $uncommittedNotifications = array();

        foreach ($users as $user){
            $notifications = $this->notificationManager->createDefaultNotificationsForUser($event, $user);

            foreach ($notifications as $notification){

                $uncommittedNotifications[] = $notification;
            }


        }

        return $uncommittedNotifications;
    }

}