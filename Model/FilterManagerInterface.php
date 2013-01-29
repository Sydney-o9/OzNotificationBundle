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
     * Build filters from parameters defined in config file
     *
     * @param array $filterParameters
     * @return array|\Doctrine\Common\Collections\Collection FilterInterface[]
     */
    public function buildConfigFilters(array $filterParameters);


}