<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\ModelManager;

use merk\NotificationBundle\Model\NotificationEventInterface;
use merk\NotificationBundle\Model\FilterInterface;
use merk\NotificationBundle\Model\NotificationInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface NotificationManagerInterface
{
    /**
     * @param NotificationEventInterface $event
     * @param FilterInterface $filter
     * @return NotificationInterface
     */
    public function create(NotificationEventInterface $event, FilterInterface $filter);

    /**
     * Returns an array of notifications for a given event and supplied recipients.
     *
     * @param NotificationEventInterface $event
     * @param array $filters
     *
     * @return array
     */
    public function createForEvent(NotificationEventInterface $event, array $filters);


    /**
     * Create the default notifications using the event triggered
     * and the user (uncommitted to the notificationKey)
     *
     * @param NotificationEventInterface $event
     * @param UserInterface $user
     * @return array NotificationInterface[]
     */
    public function createForUncommittedUser(NotificationEventInterface $event, UserInterface $user);

    /**
     * Persists and flushes a notification to persistent storage.
     *
     * @param NotificationInterface $notification
     * @param bool $flush
     */
    public function update(NotificationInterface $notification, $flush = true);

    /**
     * Persists and flushes multiple notifications to persistent storage.
     *
     * @param array $notifications
     * @param bool $flush
     */
    public function updateBulk(array $notifications, $flush = true);
}