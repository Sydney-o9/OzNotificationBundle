<?php

namespace merk\NotificationBundle\Provider;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

//Interfaces
use Symfony\Component\Security\Core\User\UserInterface;
use merk\NotificationBundle\Security\UserProviderInterface;
use merk\NotificationBundle\ModelManager\NotificationManagerInterface;
//use FOS\MessageBundle\Reader\ReaderInterface;

/**
 * Provides notifications for the current authenticated user
 *
 */
class NotificationProvider implements NotificationProviderInterface
{
    /**
     * The notification manager
     *
     * @var NotificationManagerInterface
     */
    protected $notificationManager;

    /**
     * The reader used to mark notifications as read
     *
     * @var ReaderInterface
     */
    protected $notificationReader;

    /**
     * The participant provider instance
     *
     * @var ParticipantProviderInterface
     */
    protected $userProvider;

//, ReaderInterface $notificationReader
    public function __construct(NotificationManagerInterface $notificationManager, UserProviderInterface $userProvider)
    {
        $this->notificationManager = $notificationManager;
        //$this->notificationReader = $notificationReader;
        $this->userProvider = $userProvider;
    }

    /**
     * Tells how many unread internal notifications the authenticated participant has
     *
     * @return int the number of unread notifications
     */
    public function getNbUnreadInternalNotifications()
    {
        return $this->notificationManager->getNbUnreadNotifications($this->getAuthenticatedUser());
    }

    /**
     * Gets internal notifications of the current user
     *
     * @return array of InternalNotificationInterface
     */
    public function getInternalNotifications($limit)
    {
        $user = $this->getAuthenticatedUser();

        $internalNotifications = $this->notificationManager->findNotificationsForUserByType($user, 'internal', array("createdAt" => "DESC"), $limit);

        return $internalNotifications;
    }

    /**
     * Gets email notifications of the current user
     *
     * @return array of emailNotificationInterface
     */
    public function getEmailNotifications($limit)
    {
        $user = $this->getAuthenticatedUser();

        $emailNotifications = $this->notificationManager->findNotificationsForUserByType($user, 'email', array("createdAt" => "DESC"), $limit);

        return $emailNotifications;
    }

    /**
     * Gets sms notifications of the current user
     *
     * @return array of SMSNotificationInterface
     */
    public function getSMSNotifications($limit)
    {
        $user = $this->getAuthenticatedUser();

        $smsNotifications = $this->notificationManager->findNotificationsForUserByType($user, 'sms', array("createdAt" => "DESC"), $limit);

        return $smsNotifications;
    }

    /**
     * Gets the current authenticated user
     *
     * @return UserInterface
     */
    protected function getAuthenticatedUser()
    {
        return $this->userProvider->getAuthenticatedUser();
    }

}
