<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney_o9 <https://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Notifier;

use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\ModelManager\NotificationKeyManagerInterface;
use Oz\NotificationBundle\ModelManager\NotificationManagerInterface;
use Oz\NotificationBundle\ModelManager\NotificationEventManagerInterface;
use Oz\NotificationBundle\ModelManager\FilterManagerInterface;
use Oz\NotificationBundle\Model\NotifiableInterface;
use Oz\NotificationBundle\Sender\SenderInterface;
use Oz\NotificationBundle\Model\NotificationEventInterface;

/**
 * Notifier service.
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
    public function trigger($notificationKey, UserInterface $receiver, NotifiableInterface $subject, UserInterface $actor = null)
    {
        if (!is_string($notificationKey) ){
            throw new \InvalidArgumentException( sprintf('The notification key should be of string type, "%s" given.', gettype($notificationKey) ) );
        }

        $notificationKey = $this->notificationKeyManager->findByNotificationKey($notificationKey);
        if (!$notificationKey){
            throw new \InvalidArgumentException( sprintf('The notificationKey "%s" does not exist.', $notificationKey) );
        }

        if (is_null($subject)){
            throw new \InvalidArgumentException( sprintf('A notification cannot be triggered without a subject, NULL given.') );
        }

        $event = $this->notificationEventManager->create($notificationKey, $subject, $actor);

        /** If the receiver has a filter (subscribed to that event) */
        if ($filter = $this->filterManager->getFilterOwnedByUser($event, $receiver)){
            $notifications = $this->notificationManager->createForCommittedUser($event, $filter);
        }

        /** If the receiver hasn't subscribed, generate default notification */
        else{
            $notifications = $this->notificationManager->createForUncommittedUser($event, $receiver);
        }

        $this->notificationEventManager->update($event, false);

        $this->notificationManager->updateBulk($notifications);

        $this->sender->send($notifications);
    }

}
