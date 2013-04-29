<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Model;


/**
 * Contains all the notification keys accessible to users
 * so that they can subscribe to it.
 */
abstract class NotificationKey implements NotificationKeyInterface
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * Key of the notification (e.g newsletter.of.the.week)
     *
     * @var string $notificationKey
     */
    protected $notificationKey;

    /**
     * The fully qualified class of the subject object of the notification.
     *
     * @var string $subjectClass
     */
    protected $subjectClass;

    /**
     * Description of the notification (e.g Inform users on the latest news)
     *
     * @var string $description
     */
    protected $description;

    /**
     * Capacity for the notification to be sent to more than one user at a time
     *
     * @var boolean $isBulkable
     */
    protected $isBulkable;

    /**
     * Capacity for a user to subscribe to that notification key
     *
     * @var boolean $isSubscribable
     */
    protected $isSubscribable;

    /**
     * A subscriber needs to have specific roles to access to that notification key.
     *
     * @var array
     */
    protected $subscriberRoles;


    public function __construct()
    {
        $this->subscriberRoles = array();
    }

    /**
     * @return string The notification key
     */
    public function __toString()
    {
        return $this->notificationKey;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNotificationKey()
    {
        return $this->notificationKey;
    }

    /**
     * @param string $notificationKey
     */
    public function setNotificationKey($notificationKey)
    {
        $this->notificationKey = $notificationKey;
    }

    /**
     * @return string
     */
    public function getSubjectClass()
    {
        return $this->subjectClass;
    }

    /**
     * @param string $subjectClass
     */
    public function setSubjectClass($subjectClass)
    {
        $this->subjectClass = $subjectClass;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return boolean
     */
    public function getIsBulkable()
    {
        return $this->isBulkable;
    }

    /**
     * @param boolean $isBulkable
     */
    public function setIsBulkable($isBulkable)
    {
        $this->isBulkable = $isBulkable;
    }

    /**
     * @return boolean
     */
    public function isBulkable()
    {
        return ($this->getIsBulkable() === true) ? true :false;
    }

    /**
     * @return boolean
     */
    public function getIsSubscribable()
    {
        return $this->isSubscribable;
    }

    /**
     * @param boolean $isSubscribable
     */
    public function setIsSubscribable($isSubscribable)
    {
        $this->isSubscribable = $isSubscribable;
    }

    /**
     * @return boolean
     */
    public function isSubscribable()
    {
        return ($this->getIsSubscribable() === true) ? true :false;
    }

    /**
     * Get subscriber roles
     *
     * @return array The roles a subscriber needs to have to
     * access to this notification key
     */
    public function getSubscriberRoles()
    {
        $subscriberRoles = $this->subscriberRoles;

        return array_unique($subscriberRoles);
    }

    /**
     * Sets the roles a subscriber needs to have to access to the
     * notification key
     *
     * @param array $subscriberRoles
     * @return \Oz\NotificationBundle\Model\NotificationKey
     */
    public function setSubscriberRoles(array $subscriberRoles)
    {
        $this->subscriberRoles = array();

        foreach ($subscriberRoles as $subscriberRole) {
            $this->addSubscriberRole($subscriberRole);
        }

        return $this;
    }

    /**
     * Add a subscriber role to the notification key
     *
     * @param string
     */
    public function addSubscriberRole($subscriberRole)
    {
        $subscriberRole = strtoupper($subscriberRole);

        if (!in_array($subscriberRole, $this->subscriberRoles, true)) {
            $this->subscriberRoles[] = $subscriberRole;
        }

        return $this;
    }

    /**
     * Check if the notification key has the role $subscriberRole
     *
     * @param string $subscriberRole
     *
     * @return boolean
     */
    public function hasSubscriberRole($subscriberRole)
    {
        return in_array(strtoupper($subscriberRole), $this->getSubscriberRoles(), true);
    }

    /**
     * Removes subscriber role
     *
     * @param string $subscriberRole
     *
     * @return self
     */
    public function removeSubscriberRole($subscriberRole)
    {
        if (false !== $key = array_search(strtoupper($subscriberRole), $this->subscriberRoles, true)) {
            unset($this->subscriberRoles[$key]);
            $this->subscriberRoles = array_values($this->subscriberRoles);
        }

        return $this;
    }

}
