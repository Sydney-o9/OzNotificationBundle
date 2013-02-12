<?php

namespace merk\NotificationBundle\Model;

/**
 * Interface that describes all the notification event
 * accessible
 *
 */
interface NotificationKeyInterface
{

    /**
     * Returns the event key.
     *
     * @return string
     */
    public function getNotificationKey();


    /**
     * Returns available methods of the notification key.
     *
     * @return string
     */
    public function getMethods();

    /**
     * Returns the default methods of the notification key.
     *
     * @return string
     */
    public function getDefaultMethods();

    /**
     * Returns the description of the notification key.
     *
     * @return string
     */
    public function getDescription();


    /**
     * Check if the the notificationKey has the role $subscriberRole
     *
     * @param string $subscriberRole
     *
     * @return boolean
     */
    public function hasSubscriberRole($subscriberRole);

    /**
     * Sets the roles a subscriber needs to have to access to the
     * notification key
     *
     * This overwrites any previous roles.
     *
     * @param array $subscriberRoles
     *
     * @return self
     */
    public function setSubscriberRoles(array $subscriberRoles);

    /**
     * Adds a role to the user.
     *
     * @param string $subscriberRole
     *
     * @return self
     */
    public function addSubscriberRole($subscriberRole);

    /**
     * Removes a role that the subscriber needs to have
     * access to the notificationKey.
     *
     * @param string $subscriberRole
     *
     * @return self
     */
    public function removeSubscriberRole($subscriberRole);


}