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

/**
 * Interface that the Notifier service implements.
 */
interface NotifierInterface
{

    /**
     * Triggers single notification to a particular receiver for a specific notification event.
     *
     * @param string $notificationKey
     * @param UserInterface $receiver
     * @param string $verb
     * @param mixed $subject
     * @param UserInterface $actor
     * @param \DateTime $createdAt
     * @return
     */
    public function trigger($notificationKey, UserInterface $receiver, $verb, $subject, UserInterface $actor = null, \DateTime $createdAt = null);

}
