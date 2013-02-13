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

use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;

/**
 * Interface that the Notifier service implements.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface NotifierInterface
{

    /**
     * Triggers single notification to a particular receiver for a specific notification event.
     *
     * @param string $notificationKey
     * @param UserInterface $receiver
     * @param string $verb
     * @param mixed $subject
     * @param UserInterface $actor
     * @param \DateTime $createdAt
     * @return
     */
    public function triggerSingleNotification($notificationKey, UserInterface $receiver, $verb, $subject, UserInterface $actor = null, DateTime $createdAt = null);


    /**
     * Triggers bulk notification to all users subscribed to specific notification event.
     *
     * @param string $notificationKey
     * @param mixed $subject
     * @param string $verb
     * @param UserInterface $actor
     * @param \DateTime $createdAt
     */
    public function triggerBulkNotification($notificationKey, $verb, $subject, UserInterface $actor = null, DateTime $createdAt = null);

}