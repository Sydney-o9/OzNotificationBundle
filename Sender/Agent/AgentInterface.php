<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Sender\Agent;

use Oz\NotificationBundle\Model\NotificationInterface;

/**
 * Interface that represents a service that will take notifications
 * and send them by a specific method, like SMS or Email.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface AgentInterface
{
    /**
     * Sends a single notification.
     *
     * @param \Oz\NotificationBundle\Model\NotificationInterface $notification
     */
    public function send(NotificationInterface $notification);

    /**
     * Sends a group of notifications.
     *
     * @param array $notifications
     */
    public function sendBulk(array $notifications);
}