<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface NotificationInterface
{
    /**
     * Gets owner of the notification
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser();

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface
     */
    public function setUser(UserInterface $user);

    /**
     * @return \DateTime
     */
    public function getSentAt();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Mark the notification as sent.
     */
    public function markSent();

    /**
     * Returns the notification event that triggered this
     * notification to be sent.
     *
     * @return NotificationEventInterface
     */
    public function getEvent();

    /**
     * @param NotificationEventInterface $event
     */
    public function setEvent(NotificationEventInterface $event);
}
