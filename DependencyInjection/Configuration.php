<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney-o9 <http://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('oz_notification');

        /** Database driver */
        $rootNode
            ->children()
                ->scalarNode('db_driver')->cannotBeOverwritten()->isRequired()->cannotBeEmpty()->end()
            ->end();

        /** Model manager name*/
        $rootNode
            ->children()
                ->scalarNode('model_manager_name')->defaultValue('default')->end()
            ->end();

        /** Entity classes */
        $rootNode
            ->children()
                ->arraynode('class')->children()
                    ->scalarnode('user')->isrequired()->cannotbeempty()->end()
                    ->scalarnode('notification_key')->isrequired()->cannotbeempty()->end()
                    ->scalarnode('notification_event')->isrequired()->cannotbeempty()->end()
                    ->scalarnode('notification')->isrequired()->cannotbeempty()->end()
                    ->scalarnode('user_preferences')->isrequired()->cannotbeempty()->end()
                    ->scalarnode('filter')->isrequired()->cannotbeempty()->end()
                    ->scalarnode('method')->isrequired()->cannotbeempty()->end()
                ->end()
            ->end();

        /** Entity Managers */
        $rootNode
            ->children()
                ->scalarNode('notification_manager')->defaultValue('oz_notification.notification.manager.default')->end()
                ->scalarNode('notification_event_manager')->defaultValue('oz_notification.notification_event.manager.default')->end()
                ->scalarNode('notification_key_manager')->defaultValue('oz_notification.notification_key.manager.default')->end()
                ->scalarNode('filter_manager')->defaultValue('oz_notification.filter.manager.default')->end()
                ->scalarNode('method_manager')->defaultValue('oz_notification.method.manager.default')->end()
                ->scalarNode('user_preferences_manager')->defaultValue('oz_notification.user_preferences.manager.default')->end()
                ->scalarNode('device_manager')->defaultValue('oz_notification.device.manager.default')->end()
            ->end();

        /** Providers */
        $rootNode
            ->children()
                ->scalarNode('user_provider')->defaultValue('oz_notification.user.provider.default')->end()
                ->scalarNode('user_preferences_provider')->defaultValue('oz_notification.user_preferences.provider.default')->end()
                ->scalarNode('notification_provider')->defaultValue('oz_notification.notification.provider.default')->end()
            ->end();

        /** Deleters */
        $rootNode
            ->children()
                ->scalarNode('notification_deleter')->defaultValue('oz_notification.notification.deleter.default')->end()
            ->end();

        /** Listener */
        $rootNode
            ->children()
                ->scalarNode('notification_event_listener')->defaultValue('oz_notification.notification_event.listener.default')->end()
            ->end();

       /** Notifications types */
        $rootNode
            ->children()
                ->arrayNode('notification_types')->prototype('array')
                    ->children()
                        ->scalarNode('entity')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('renderer')->defaultValue('oz_notification.renderer.default')->end()
                        ->scalarNode('notification_factory')->defaultValue('oz_notification.notification.factory.default')->end()
                        ->scalarNode('sender_agent')->isRequired()->cannotBeEmpty()->end()

                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

       /** FormType, FormFactory, FormHandler, FormName */
        $rootNode
            ->children()
                ->arrayNode('user_preferences_form')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('factory')->defaultValue('oz_notification.user_preferences.form.factory.default')->cannotBeEmpty()->end()
                        ->scalarNode('type')->defaultValue('oz_notification.user_preferences.form.type.default')->cannotBeEmpty()->end()
                        ->scalarNode('name')->defaultValue('user_preferences')->cannotBeEmpty()->end()
                        ->scalarNode('handler')->defaultValue('oz_notification.user_preferences.form.handler.default')->cannotBeEmpty()->end()

                        ->end()
                    ->end()
                ->end()
            ->end()
        ;



        return $treeBuilder;
    }
}
