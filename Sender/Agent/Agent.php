<?php

namespace merk\NotificationBundle\Sender\Agent;

use merk\NotificationBundle\Model\NotificationInterface;


abstract class Agent implements AgentInterface
{

    /**
     * Sends a single notification.
     *
     * @param \merk\NotificationBundle\Model\NotificationInterface $notification
     * @return bool
     */
    public function send(NotificationInterface $notification)
    {
        return true;
    }

    /**
     * Sends a group of notifications.
     *
     * @param array $notifications
     * @param bool $useMessageBroker
     * @return bool
     */
    public function sendBulk(array $notifications, $useMessageBroker = true)
    {
        return true;
    }
}