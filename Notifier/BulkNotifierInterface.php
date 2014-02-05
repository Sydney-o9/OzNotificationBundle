<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney_o9 <https://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Notifier;

use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\Model\NotifiableInterface;

/**
 * Interface for the Bulk Notifier service.
 */
interface BulkNotifierInterface
{
    /**
     * Triggers bulk notification to all users subscribed to specific notification event.
     *
     * @param string $notificationKey
     * @param mixed $subject
     * @param UserInterface $actor
     */
    public function trigger($notificationKey, NotifiableInterface $subject, UserInterface $actor = null);

}
