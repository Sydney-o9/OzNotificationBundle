<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\ModelManager;

use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\Model\NotificationEventInterface;
use Oz\NotificationBundle\Model\FilterInterface;



interface FilterManagerInterface
{
    /**
     * Obtain filters owned by all users for a particular event
     * 1 event ---> 1 notification key ----> many filters for all users (still 1 filter/user/event)
     *
     * @param NotificationEventInterface $event
     * @return array
     */
    public function getFiltersForEvent(NotificationEventInterface $event);


    /**
     * Obtain filter owned by a particular receiver for a particular event
     * 1 event ---> 1 notification key ----> 1 filter/user/event
     *
     * @param NotificationEventInterface $event
     * @param UserInterface $user
     * @return FilterInterface|null
     */
    public function getFilterOwnedByUser(NotificationEventInterface $event, UserInterface $user);


    /**
     * Obtain filter for a particular user that subscribed to a particular notification key
     *
     * @param UserInterface $user
     * @param string | \Oz\NotificationBundle\Model\NotificationKeyInterface $notificationKey
     * @return \Oz\NotificationBundle\Model\Filter |null
     */
    public function getUserFilterByNotificationKey(UserInterface $user, $notificationKey);


    /**
     * Obtain users that are not committed to a particular notification key
     * They haven't told their preference yet.
     *
     * (USERS THAT DID NOT UPDATE THEIR PREFERENCES AND THEREFORE DO NOT
     *  HAVE A FILTER YET.)
     *
     * @param string|\Oz\NotificationBundle\Model\NotificationKeyInterface $notificationKey
     * @return UserInterface[]
     */
    public function getUncommittedUsers($notificationKey);


}