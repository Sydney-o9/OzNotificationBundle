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

}