<?php

namespace Oz\NotificationBundle\Sender\Agent;

use Oz\NotificationBundle\Model\NotificationInterface;


abstract class Agent implements AgentInterface
{

    /**
     * Sends a single notification.
     *
     * @param \Oz\NotificationBundle\Model\NotificationInterface $notification
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
    public function sendBulk(array $notifications, $useMessageBroker = false)
    {
        return true;
    }
}
