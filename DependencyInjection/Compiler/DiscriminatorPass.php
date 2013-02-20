<?php

namespace merk\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use InvalidArgumentException;


class DiscriminatorPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('merk_notification.discriminator')) {

            return;
        }

        $renderers = array();
        foreach ($container->findTaggedServiceIds('merk_notification.notification.renderer') as $id => $tags) {
            foreach ($tags as $tag) {
                if (empty($tag['alias'])) {
                    throw new InvalidArgumentException(sprintf('The Notification Renderer "%s" must have an alias', $id));
                }

                $renderers[$tag['alias']] = new Reference($id);
            }
        }

        $notificationFactories = array();
        foreach ($container->findTaggedServiceIds('merk_notification.notification.factory') as $id => $tags) {
            foreach ($tags as $tag) {
                if (empty($tag['alias'])) {
                    throw new InvalidArgumentException(sprintf('The Notification Factory "%s" must have an alias', $id));
                }

                //if there is a renderer with the same alias inject it
                if ($this->existsRendererWithAlias($container, $tag['alias'])){
                    $container->getDefinition($id)->replaceArgument(0, $renderers[$tag['alias']]);
                }

                $notificationFactories[$tag['alias']] = new Reference($id);
            }
        }

        $container->getDefinition('merk_notification.discriminator')->replaceArgument(2, $renderers);
        $container->getDefinition('merk_notification.discriminator')->replaceArgument(1, $notificationFactories);

    }

    /**
     * Returns true if it exists a tagged renderer with the alias $alias
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param $alias
     * @return bool
     */
    public function existsRendererWithAlias(ContainerBuilder $container, $alias){

        foreach ($container->findTaggedServiceIds('merk_notification.notification.renderer') as $id => $tags) {
            foreach ($tags as $tag) {
                if ($tag['alias'] === $alias) {
                    return true;
                } else{
                    continue;
                }
            }
        }

        return false;

    }



}



