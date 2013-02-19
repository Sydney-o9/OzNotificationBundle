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
     * Create all notifications for a committed user using the event triggered
     *
     * @param NotificationEventInterface $event
     * @param FilterInterface $filter
     * @return NotificationInterface
     */
    public function createForCommittedUser(NotificationEventInterface $event, FilterInterface $filter);

    /**
     * Create all notifications for an array of committed users using the event triggered
     *
     * @param NotificationEventInterface $event
     * @param array $filters
     * @return array Notification[]
     */
    public function createForCommittedUsers(NotificationEventInterface $event, array $filters);


    /**
     * Create all notifications for an uncommitted users using the event triggered
     *
     * @param NotificationEventInterface $event
     * @param UserInterface $user
     * @return array NotificationInterface[]
     */
    public function createForUncommittedUser(NotificationEventInterface $event, UserInterface $user);

    /**
     * Create all notifications for an array of uncommitted users using the event triggered
     *
     * @param NotificationEventInterface $event
     * @param array $users
     * @return array Notification[]
     */
    public function createForUncommittedUsers(NotificationEventInterface $event, array $users);

    /**
     * Check if the user has access to that notification key.
     *
     * @param NotificationEventInterface $event
     * @param userInterface $user
     * @return boolean
     */
    public function CanUserAccessToEvent(NotificationEventInterface $event, UserInterface $user);


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