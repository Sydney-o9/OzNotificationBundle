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

namespace Oz\NotificationBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Oz\NotificationBundle\Model\UserPreferencesInterface;
use Oz\NotificationBundle\Model\FilterInterface;

/**
 * User Preferences
 */
abstract class UserPreferences implements UserPreferencesInterface
{

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @var \Oz\NotificationBundle\Entity\Filter[]
     */
    protected $filters;

    /**
     * @var \Symfony\Component\Security\Core\User\UserInterface
     */
    protected $user;


    public function __construct()
    {
        $this->filters = new ArrayCollection;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Adds a notification filter for the specific user.
     *
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters->add($filter);
        $filter->setUserPreferences($this);

        return $this;
    }

    /**
     * Removes the supplied filter from the user's preferences.
     *
     * @param FilterInterface $filter
     */
    public function removeFilter(FilterInterface $filter)
    {
        $this->filters->remove($filter);
        $filter->setUserPreferences(null);

        return $this;
    }

    /**
     * Returns a collection of filters for the user.
     *
     * @return ArrayCollection of FilterInterface
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param ArrayCollection of FilterInterface
     */
    public function setFilters(ArrayCollection $filters)
    {
        $this->filters = $filters;

        return $this;
    }

}
