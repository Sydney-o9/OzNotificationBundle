<?php

namespace Oz\NotificationBundle\Deleter;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\Provider\UserProviderInterface;
use Oz\NotificationBundle\ModelManager\NotificationManagerInterface;
use Oz\NotificationBundle\Model\NotifiableInterface;
use Oz\NotificationBundle\Model\NotificationInterface;

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
     * Constructor
     *
     * @param NotificationManagerInterface $notificationManager
     */
    public function __construct(NotificationManagerInterface $notificationManager)
    {
        $this->notificationManager = $notificationManager;
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
