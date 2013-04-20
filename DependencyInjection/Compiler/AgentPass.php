<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use InvalidArgumentException;

/**
 * Registers Sorting implementations.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class AgentPass implements CompilerPassInterface
{

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oz_notification.sender')) {
            return;
        }

        $senders = array();

        $notificationTypes = $container->getParameter('oz_notification_types');

        foreach($notificationTypes as $method => $conf){

            if (!$container->hasDefinition($conf['sender_agent'])) {
                throw new InvalidArgumentException(sprintf('The Sending Agent service "%s" does not exist', $conf['sender_agent']));
            }

            $senderAgent = new Reference($conf['sender_agent']);

            $senders[$method] = $senderAgent;
        }

        $container->getDefinition('oz_notification.sender')->replaceArgument(0, $senders);
    }
}


