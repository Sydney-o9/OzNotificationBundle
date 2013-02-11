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

//        $users = $this->filterManager->getUsersMissingFilter('job.posted');
//        $users = $this->filterManager->getSubscribedUsers('job.posted');
//        $users = $this->filterManager->getUnsubscribedUsers('job.posted');

        /** If the receiver has a filter (subscribed to that event) */
        if ($filter = $this->filterManager->getFilterForEventOwnedBySingleReceiver($event, $receiver)){

            echo "The user has a filter";
            $notifications = $this->notificationManager->createForEvent($event, $filter);
        }

        /** If the receiver hasn't subscribed, generate default notification */
        else{
            echo "The user has no filters";
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
        ladybug_dump($event);
        $filters = $this->filterManager->getFiltersForEvent($event);
        ladybug_dump($filters);
        $notifications = $this->notificationManager->createForEvent($event, $filters);
        ladybug_dump_die($notifications);
        $this->sender->send($notifications);

        $this->notificationEventManager->update($event, false);

        $this->notificationManager->updateBulk($notifications);

    }

}