<?php

namespace merk\NotificationBundle\Model;


/**
* Contains all the notification events accessible to users
* so that they can subscribe to it.
 */
abstract class NotificationKey implements NotificationKeyInterface
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $notificationKey
     */
    protected $notificationKey;

    /**
     * @var string $description
     */
    protected $description;


    /**
     * Can this notification be sent in mass?
     *
     * @var boolean $isBulkable
     */
    protected $isBulkable;

    /**
     * A notification key is meant to be used by a subscriber
     * with specific roles.
     *
     * @var array
     */
    protected $subscriberRoles;


    public function addSubscriberRole($subscriberRole)
    {
        $subscriberRole = strtoupper($subscriberRole);

        if (!in_array($subscriberRole, $this->subscriberRoles, true)) {
            $this->subscriberRoles[] = $subscriberRole;
        }

        return $this;
    }

    /**
     * Returns the roles a subscriber needs to have to
     * access to this notificationKey
     *
     * @return array The roles
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
     * @return \merk\NotificationBundle\Model\NotificationKey
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
     * Check if the the notificationKey has the role $subscriberRole
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
     * Removes a role that the subscriber needs to have
     * access to the notificationKey.
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
    public function getIsBulkable()
    {
        return $this->isBulkable;
    }

    /**
     * @return boolean
     */
    public function isBulkable()
    {
        return ($this->getIsBulkable() === true) ? true :false;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the event key.
     *
     * @return string
     */
    public function getNotificationKey()
    {
        return $this->notificationKey;
    }

    /**
     * Set the event key.
     *
     * @param string $notificationKey
     */
    public function setNotificationKey($notificationKey)
    {
        $this->notificationKey = $notificationKey;
    }

    public function __toString(){

        return $this->notificationKey;

    }

    public function __construct()
    {
        $this->subscriberRoles = array();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
