<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface NotificationInterface
{
    public function getUser();
    public function setUser(UserInterface $user);

    public function getSentAt();
    public function getCreatedAt();
    public function markSent();

    /**
     * Returns the notification event that triggered this
     * notification to be sent.
     *
     * @return NotificationEventInterface
     */
    public function getEvent();
    public function setEvent(NotificationEventInterface $event);


}