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

        $rootNode
            ->children()
                ->scalarNode('db_driver')->cannotBeOverwritten()->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('model_manager_name')->defaultValue('default')->end()
                ->arrayNode('class')->children()
                    ->scalarNode('notification_event_key')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('notification_event')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('notification')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('user_preferences')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('filter')->isRequired()->cannotBeEmpty()->end()
                ->end()
            ->end();


        $rootNode
            ->children()
                ->arrayNode('filters')->prototype('array')
                    ->children()
                        ->scalarNode('notification_key')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('default_methods')
                        ->prototype('scalar')->end()
                        ->defaultValue(array('email'))
                        ->end()
                        ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('description')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
