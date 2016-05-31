<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle;

use Oz\NotificationBundle\DependencyInjection\Compiler\ValidatorPass;
use Oz\NotificationBundle\DependencyInjection\Compiler\AgentPass;
use Oz\NotificationBundle\DependencyInjection\Compiler\NotificationDiscriminatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * OzNotificationBundle
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class OzNotificationBundle extends Bundle
{
    /**
     * Adds a compiler pass to the container builder.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ValidatorPass());

        $container->addCompilerPass(new AgentPass());

        $container->addCompilerPass(new NotificationDiscriminatorPass());
    }
}
