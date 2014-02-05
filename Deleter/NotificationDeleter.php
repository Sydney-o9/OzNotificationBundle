<?php

namespace Oz\NotificationBundle\Provider;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\Provider\UserProviderInterface;
use Oz\NotificationBundle\ModelManager\NotificationManagerInterface;

/**
 * Deletes notifications
 */
class NotificationDeleter implements NotificationDeleterInterface
{
    /**
     * The notification manager
     *
     * @var NotificationManagerInterface
     */
    protected $notificationManager;

    /**
     * The notification key manager interface
     *
     * @var NotificationKeyManagerInterface
     */
    protected $notificationKeyManager;

    public function __construct(NotificationManagerInterface $notificationManager, NotificationKeyManagerInterface $notificationKeyManager)
    {
        $this->notificationManager = $notificationManager;
        $this->notificationKeyManager = $notificationKeyManager;
    }

    /**
     * Delete all notifications about the subject $subject
     *
     * @param NotifiableInterface $subject
     * @return Bool
     */
    public function deleteNotificationsWithSubject(NotifiableInterface $subject)
    {
        return $this->notificationManager
            ->findNotificationsWithSubject($subject);
    }


    public function deleteNotification(NotificationInterface $notification)
    {
        return $this->notificationManager
            ->remove($notification);

    }

}
