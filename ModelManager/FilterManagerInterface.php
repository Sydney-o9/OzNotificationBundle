<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\ModelManager;

use Symfony\Component\Security\Core\User\UserInterface;
use merk\NotificationBundle\Model\NotificationEventInterface;



interface FilterManagerInterface
{
    /**
     * Obtain filters owned by all users for a particular event
     *
     * @param NotificationEventInterface $event
     * @return array
     */
    public function getFiltersForEvent(NotificationEventInterface $event);


    /**
     * Obtain filters owned by a particular receiver for a particular event
     *
     * @param NotificationEventInterface $event
     * @param UserInterface $receiver
     * @return array
     */
    public function getFiltersForEventOwnedBySingleReceiver(NotificationEventInterface $event, UserInterface $receiver);



    /**
     * Obtain filter for a particular user that subscribed to a particular notification key
     *
     * @param UserInterface $user
     * @param string | \merk\NotificationBundle\Model\NotificationKeyInterface $notificationKey
     * @return \merk\NotificationBundle\Model\Filter |null
     */
    public function getUserFilterByNotificationKey(UserInterface $user, $notificationKey);


    /**
     * Build filters from parameters defined in config file
     *
     * @param array $filterParameters
     * @return array|\Doctrine\Common\Collections\Collection FilterInterface[]
     */
    public function buildConfigFilters(array $filterParameters);


}