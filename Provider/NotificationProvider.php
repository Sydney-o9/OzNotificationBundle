<?php

namespace Oz\NotificationBundle\Provider;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\Provider\UserProviderInterface;
use Oz\NotificationBundle\ModelManager\NotificationManagerInterface;

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
     * The user provider instance
     *
     * @var UserProviderInterface
     */
    protected $userProvider;

    public function __construct(NotificationManagerInterface $notificationManager, UserProviderInterface $userProvider)
    {
        $this->notificationManager = $notificationManager;
        $this->userProvider = $userProvider;
    }

    /**
     * Tells how many unread internal notifications the authenticated participant has
     *
     * @return int the number of unread notifications
     */
    public function getNbUnreadInternalNotifications()
    {
        return $this->notificationManager->getNbUnreadInternalNotifications($this->getAuthenticatedUser());
    }

    /**
     * Gets internal notifications of the current user
     *
     * @param int The number of notifications to show
     * @param bool Whether the notifications need to be marked as read or not
     *
     * @return array of InternalNotificationInterface
     */
    public function getInternalNotifications($limit, $andMarkAsRead = true)
    {
        $user = $this->getAuthenticatedUser();

        $internalNotifications = $this->notificationManager->findNotificationsForUserByType($user, 'internal', array("createdAt" => "DESC"), $limit);

        if ($andMarkAsRead){
            $this->notificationManager->markInternalNotificationAsReadByUser($user);
        }

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
