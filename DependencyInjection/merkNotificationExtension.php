<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * Dependency injection extension
 */
class merkNotificationExtension extends Extension
{
    /**
     * Loads the extension into the DIC.
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @throws \InvalidArgumentException
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        /** Load XML files */
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (!in_array(strtolower($config['db_driver']), array('orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load(sprintf('%s.xml', $config['db_driver']));

        $xmlFiles = array(
            'form',
            'notifier',
            'renderer',
            'sender',
            'consumer',
            'logger',
            'listener',
            'validator',
            'discriminator',
            'notification_factory',
            'provider');

        foreach ($xmlFiles as $xmlFile) {
            $loader->load(sprintf('%s.xml', $xmlFile));
        }

        /** Load validation files */
        $xmlMappingFiles = $container->getParameter('validator.mapping.loader.xml_files_loader.mapping_files');
        $xmlMappingFiles[] = __DIR__.'/../Resources/config/validation/orm.xml';
        $container->setParameter('validator.mapping.loader.xml_files_loader.mapping_files', $xmlMappingFiles);

        /** Alias default managers */
        $container->setAlias('merk_notification.notification.manager', $config['notification_manager']);
        $container->setAlias('merk_notification.notification_event.manager', $config['notification_event_manager']);
        $container->setAlias('merk_notification.notification_key.manager', $config['notification_key_manager']);
        $container->setAlias('merk_notification.filter.manager', $config['filter_manager']);
        $container->setAlias('merk_notification.method.manager', $config['method_manager']);
        $container->setAlias('merk_notification.user_preferences.manager', $config['user_preferences_manager']);

        /** Alias default providers */
        $container->setAlias('merk_notification.user.provider', $config['user_provider']);
        $container->setAlias('merk_notification.notification.provider', $config['notification_provider']);

        /** Model manager name */
        $container->setParameter('merk_notification.model_manager_name', $config['model_manager_name']);

        /** Entity classes */
        $container->setParameter('merk_notification.user.class', $config['class']['user']);
        $container->setParameter('merk_notification.notification_key.class', $config['class']['notification_key']);
        $container->setParameter('merk_notification.notification_event.class', $config['class']['notification_event']);
        $container->setParameter('merk_notification.notification.class', $config['class']['notification']);
        $container->setParameter('merk_notification.user_preferences.class', $config['class']['user_preferences']);
        $container->setParameter('merk_notification.filter.class', $config['class']['filter']);
        $container->setParameter('merk_notification.method.class', $config['class']['method']);


        /** Load notification types */
        $notificationTypes = $config['notification_types'];
        $container->setParameter('merk_notification_types', $notificationTypes);
    }
}
