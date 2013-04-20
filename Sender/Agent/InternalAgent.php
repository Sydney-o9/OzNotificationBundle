<?php

namespace Oz\NotificationBundle\Sender\Agent;

use Oz\NotificationBundle\Model\NotificationInterface;


class InternalAgent extends Agent implements AgentInterface
{

    /**
     * {@inheritDoc}
     */
    public function send(NotificationInterface $notification)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function sendBulk(array $notifications, $useMessageBroker = true)
    {
        return true;
    }
}