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
        $rootNode = $treeBuilder->root('merk_notification');

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
                ->scalarNode('notification_manager')->defaultValue('merk_notification.notification.manager.default')->end()
                ->scalarNode('notification_event_manager')->defaultValue('merk_notification.notification_event.manager.default')->end()
                ->scalarNode('notification_key_manager')->defaultValue('merk_notification.notification_key.manager.default')->end()
                ->scalarNode('filter_manager')->defaultValue('merk_notification.filter.manager.default')->end()
                ->scalarNode('method_manager')->defaultValue('merk_notification.method.manager.default')->end()
                ->scalarNode('user_preferences_manager')->defaultValue('merk_notification.user_preferences.manager.default')->end()
            ->end();

        /** Providers */
        $rootNode
            ->children()
                ->scalarNode('user_provider')->defaultValue('merk_notification.user.provider.default')->end()
                ->scalarNode('user_preferences_provider')->defaultValue('merk_notification.user_preferences.provider.default')->end()
                ->scalarNode('notification_provider')->defaultValue('merk_notification.notification.provider.default')->end()
            ->end();

       /** Notifications types */
        $rootNode
            ->children()
                ->arrayNode('notification_types')->prototype('array')
                    ->children()
                        ->scalarNode('entity')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('renderer')->defaultValue('merk_notification.renderer.default')->end()
                        ->scalarNode('notification_factory')->defaultValue('merk_notification.notification.factory.default')->end()
                        ->scalarNode('sender_agent')->isRequired()->cannotBeEmpty()->end()

                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
