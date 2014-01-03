<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Notifier;

use Oz\NotificationBundle\ModelManager\NotificationKeyManagerInterface;
use Oz\NotificationBundle\ModelManager\NotificationManagerInterface;
use Oz\NotificationBundle\ModelManager\NotificationEventManagerInterface;
use Oz\NotificationBundle\ModelManager\FilterManagerInterface;
use Oz\NotificationBundle\Sender\SenderInterface;

use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\Model\NotificationEventInterface;

use DateTime;

/**
 * Notifier service.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class Notifier implements NotifierInterface
{
    /**
     * @var NotificationKeyManagerInterface
     */
    protected $notificationKeyManager;

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
     * @param NotificationKeyManagerInterface $notificationKeyManager
     * @param FilterManagerInterface $filterManager
     * @param NotificationManagerInterface $notificationManager
     * @param SenderInterface $sender
     */
    public function __construct(NotificationKeyManagerInterface $notificationKeyManager, NotificationEventManagerInterface $notificationEventManager, FilterManagerInterface $filterManager, NotificationManagerInterface $notificationManager, SenderInterface $sender)
    {
        $this->notificationKeyManager = $notificationKeyManager;
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
        echo "Notification Key";
        ladybug_dump($notificationKey);
        echo "Verb";
        ladybug_dump($verb);
        echo "Subject";
        ladybug_dump($subject);
        echo "Actor";
        ladybug_dump($actor);
        if (!is_string($notificationKey) || !is_string($verb)){
            throw new \InvalidArgumentException(sprintf('"NotificationKey" and "Verb" should be of string type, "%s" and "%s" given respectively.', gettype($notificationKey), gettype($verb)));
        }

        $notificationKey = $this->notificationKeyManager->findByNotificationKey($notificationKey);
        if (!$notificationKey){
            throw new \InvalidArgumentException(sprintf('The notificationKey "%s" does not exist.', $notificationKey));
        }

        $event = $this->notificationEventManager->create($notificationKey, $subject, $verb, $actor, $createdAt);

        /** If the receiver has a filter (subscribed to that event) */
        if ($filter = $this->filterManager->getFilterOwnedByUser($event, $receiver)){
            $notifications = $this->notificationManager->createForCommittedUser($event, $filter);
        }

        /** If the receiver hasn't subscribed, generate default notification */
        else{
            $notifications = $this->notificationManager->createForUncommittedUser($event, $receiver);
        }
        echo "Notifications";

        $this->notificationEventManager->update($event, false);

        $this->notificationManager->updateBulk($notifications);

        $this->sender->send($notifications);
    }


    /**
     * {@inheritDoc}
     */
    public function triggerBulkNotification($notificationKey, $verb, $subject, UserInterface $actor = null, DateTime $createdAt = null)
    {
        if (!is_string($notificationKey) || !is_string($verb)){
            throw new \InvalidArgumentException(sprintf('"NotificationKey" and "Verb" should be of string type, "%s" and "%s" given respectively.', gettype($notificationKey), gettype($verb)));
        }

        $notificationKey = $this->notificationKeyManager->findByNotificationKey($notificationKey);
        if (!$notificationKey){
            throw new \InvalidArgumentException(sprintf('The notificationKey "%s" does not exist.', $notificationKey));
        }

        if (false === $notificationKey->isBulkable()){
                throw new \InvalidArgumentException(sprintf('NotificationKey "%s" is not bulkable. This notification can not be sent in mass.', $notificationKey->getNotificationKey()));
        }

        $event = $this->notificationEventManager->create($notificationKey, $subject, $verb, $actor, $createdAt);

        $notifications = $this->generateNotificationForAllUsers($event);

        $this->notificationEventManager->update($event, false);

        $this->notificationManager->updateBulk($notifications);

        $this->sender->send($notifications);
    }

    /**
     * Generate notifications for All Users: Committed and Uncommitted to a filter/Notification key
     *
     */
    public function generateNotificationForAllUsers(NotificationEventInterface $event){

        $committedNotifications = $this->generateNotificationForCommittedUsers($event);

        $uncommittedNotifications = $this->generateNotificationForUncommittedUsers($event);

        $notifications = array_merge($committedNotifications, $uncommittedNotifications);

        return $notifications;
    }

    /**
     * Generate notifications for Users Committed to a filter/Notification key
     *
     */
    public function generateNotificationForCommittedUsers(NotificationEventInterface $event){

        $filters = $this->filterManager->getFiltersForEvent($event);

        $committedNotifications = $this->notificationManager->createForCommittedUsers($event, $filters);

        return $committedNotifications;

    }

    /**
     * Generate notifications for Users Uncommitted to a filter/Notification key
     *
     */
    public function generateNotificationForUncommittedUsers(NotificationEventInterface $event){

        $users = $this->filterManager->getUncommittedUsers($event->getNotificationKey());

        $uncommittedNotifications = $this->notificationManager->createForUncommittedUsers($event, $users);

        return $uncommittedNotifications;
    }

}
