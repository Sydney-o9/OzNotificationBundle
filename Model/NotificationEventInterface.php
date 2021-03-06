<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 * (c) Richard Shank <develop@zestic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Model;

/**
 * Interface that describes a specific event that
 * causes notifications to be sent.
 */
interface NotificationEventInterface
{
    /**
     * Returns the event key.
     *
     * @return string
     */
    public function getNotificationKey();

    /**
     * Returns the subject of the event.
     *
     * @return \Oz\NotificationBundle\Model\NotifiableInterface
     */
    public function getSubject();

    /**
     * Returns the user that caused the event.
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getActor();

    /**
     * Returns when the event occurred.
     *
     * @return \DateTime
     */
    public function getCreatedAt();
}
