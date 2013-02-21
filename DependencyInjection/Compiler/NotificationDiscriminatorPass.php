<?php

namespace merk\NotificationBundle\DependencyInjection\Compiler;

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
        if (!$container->hasDefinition('merk_notification.notification.discriminator')) {

            return;
        }

        $notificationTypes = $container->getParameter('merk_notification_types');

        $output = $notificationTypes;

        foreach($notificationTypes as $method => $conf){

            /**  For Renderers   */
            $rendererDefinition = $container->getDefinition($conf['renderer']);

            //Bad bad bad..will not instantiate the service
            //$rendererClass = $container->getDefinition($conf['renderer'])->getClass();
            //$rendererDefinition = new Definition($rendererClass);

            //No need for setter injection (through ->addMethodCall()) or
            //constructor injection (through ->addArgument() or ->setArgument()
            //at the moment.

            /**   Update container with the definition   */
            $container->setDefinition(sprintf('merk_notification.notification.renderer.%s', $method), $rendererDefinition);

            //We can have a reference for it
            $renderer = new Reference(sprintf('merk_notification.notification.renderer.%s', $method));

            //Or use the original one
            //$renderer = new Reference($conf['renderer']);


            /**  For Notification Factories   */
            $factoryClass = $container->getDefinition($conf['notification_factory'])->getClass();
            $factoryDefinition = new Definition($factoryClass);

            //Setter injection
            $factoryDefinition
                ->addMethodCall('setClass', array($conf['entity']))
                ->addMethodCall('setRenderer', array($renderer));;

            /**   Update container with the definition   */
            $container->setDefinition(sprintf('merk_notification.notification.factory.%s', $method), $factoryDefinition);

            //We can have a reference for it
            $notificationFactory = new Reference(sprintf('merk_notification.notification.factory.%s', $method));
            //Or use the original one
            //$notificationFactory = new Reference($conf['notification_factory']);

            //Output the services
            $output[$method]['renderer'] = $renderer;
            $output[$method]['notification_factory'] = $notificationFactory;

        }

        $container->getDefinition('merk_notification.notification.discriminator')->replaceArgument(0, $output);


    }

}



