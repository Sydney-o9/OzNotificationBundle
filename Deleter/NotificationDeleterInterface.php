<?php

namespace Oz\NotificationBundle\Deleter;

use Oz\NotificationBundle\Model\NotifiableInterface;
use Oz\NotificationBundle\Model\NotificationInterface;

/**
 * Deletes notifications
 */
interface NotificationDeleterInterface
{
    /**
     * Delete all notifications about the subject $subject
     *
     * @param NotifiableInterface $subject
     * @return Bool
     */
    function deleteNotificationsWithSubject(NotifiableInterface $subject);

    /**
     * Delete notification
     *
     * @param NotificationInterface $notification
     * @return Bool
     */
    function deleteNotification(NotificationInterface $notification);

}
