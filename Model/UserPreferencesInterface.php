<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Model;

/**
 * UserPreferences interface
 */
interface UserPreferencesInterface
{
    /**
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser();

    /**
     * Adds a notification filter for the specific user.
     *
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter);

    /**
     * Removes the supplied filter from the user's preferences.
     *
     * @param FilterInterface $filter
     */
    public function removeFilter(FilterInterface $filter);

    /**
     * Returns a collection of filters for the user.
     *
     * @return array|\Doctrine\Common\Collections\Collection
     */
    public function getFilters();

}
