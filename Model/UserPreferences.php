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
 * Base UserPreferences object.
 *
 * @author Tim Nagel <tim@nagel.com.au>
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

    protected $defaultMethod;

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
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
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
    }

    /**
     * Returns a collection of filters for the user.
     *
     * @return array|\Doctrine\Common\Collections\Collection
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Sets the default method used to notify the user if the
     * filters do not specify a custom notification type.
     *
     * @param string $defaultMethod
     */
    public function setDefaultMethod($defaultMethod)
    {
        $this->defaultMethod = $defaultMethod;
    }

    /**
     * Sets the default method used to notify the user if the
     * filters do not specify a custom notification type.
     *
     * @return string
     */
    public function getDefaultMethod()
    {
        return $this->defaultMethod;
    }



}
