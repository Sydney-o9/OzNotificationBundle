<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Renderer;

use Oz\NotificationBundle\Model\NotificationInterface;

/**
 * Describes the interface that a Renderer service must implement.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface RendererInterface
{
    /**
     * Renders the template required for the notification
     *
     * TODO: consider caching the result of a render
     *
     * @param \Oz\NotificationBundle\Model\NotificationInterface $notification
     * @return array(
     *             'subject' => // Subject to be used for the notification,
     *             'body' => // Body of the notification
     *         )
     */
    public function render(NotificationInterface $notification);
}