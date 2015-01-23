<?php

namespace Oz\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use InvalidArgumentException;


class NotificationDiscriminatorPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oz_notification.notification.discriminator')) {
            return;
        }

        /** Get all types of notifications available */
        $notificationTypes = $container->getParameter('oz_notification_types');
        $output = $notificationTypes;

        foreach($notificationTypes as $method => $conf){

            /** Update container with the definition for Renderers */

            $rendererDefinition = $container->getDefinition($conf['renderer']);
            $container->setDefinition(sprintf('oz_notification.notification.renderer.%s', $method), $rendererDefinition);
            $renderer = new Reference(sprintf('oz_notification.notification.renderer.%s', $method));

            /** Update container with the definition for Notification Factories + setter injections */

            $factoryClass = $container->getDefinition($conf['notification_factory'])->getClass();
            $factoryDefinition = new Definition($factoryClass);

            /** Special Case: iOSPushNotificationFactory */
            if ($method === 'ios_push'){
                $factoryDefinition = new Definition($container->getDefinition('oz_notification.notification.factory.ios_push')->getClass());
                $deviceManager = $container->findDefinition('oz_notification.device.manager');
                $factoryDefinition->addMethodCall('setDeviceManager', array($deviceManager));
            }

            $factoryDefinition
                ->addMethodCall('setClass', array($conf['entity']))
                ->addMethodCall('setRenderer', array($renderer));;

            $container->setDefinition(sprintf('oz_notification.notification.factory.%s', $method), $factoryDefinition);
            $notificationFactory = new Reference(sprintf('oz_notification.notification.factory.%s', $method));

            /** Output the services */
            $output[$method]['renderer'] = $renderer;
            $output[$method]['notification_factory'] = $notificationFactory;

        }

        /** Replace first argument for Notification Discriminator */
        $container->getDefinition('oz_notification.notification.discriminator')->replaceArgument(0, $output);

    }

}



