<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney_o9 <https://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Sender;

use Oz\NotificationBundle\Sender\Agent\AgentInterface;

/**
 * Sender service.
 */
class Sender implements SenderInterface
{
    /**
     * An array of sending agents.
     *
     * @var array
     */
    protected $agents = array();

    /**
     * @param array $agents
     * @throws \InvalidArgumentException
     */
    public function __construct(array $agents)
    {

        foreach ($agents as $agent) {
            if (!$agent instanceof AgentInterface) {
                throw new \InvalidArgumentException(sprintf('Agent %s must implement AgentInterface', get_class($agent)));
            }
        }

        $this->agents = $agents;
    }

    /**
     * Returns an array of agent aliases that can be used to send
     * a notification.
     *
     * @return array
     */
    public function getAgentAliases()
    {
        return array_keys($this->agents);
    }

    /**
     * Gets an agent by its alias, or throws an exception when
     * that agent does not exist.
     *
     * @param string $alias
     * @return AgentInterface
     * @throws \InvalidArgumentException when the alias doesnt exist
     */
    protected function getAgent($alias)
    {
        if (!isset($this->agents[$alias])) {
            throw new \InvalidArgumentException(sprintf('Alias "%s" does not exist', $alias));
        }

        return $this->agents[$alias];
    }

    /**
     * Sorts the array of notifications by notification method
     * and sends each in bulk to the appropriate agent for sending.
     *
     * @param array $notifications
     */
    public function send(array $notifications)
    {

        $sortedNotifications = array();
        foreach ($notifications as $notification) {
            /** Make sure the notification is ongoing */
            if(!$notification->isOngoing()){
                continue;
            }

            $sortedNotifications[$notification->getType()][] = $notification;
        }

        foreach ($sortedNotifications as $method => $notifications) {
            $this->getAgent($method)->sendBulk($notifications);
        }

    }
}
