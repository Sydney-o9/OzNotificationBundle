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
     * Returns the Default Method of the event.
     *
     * @return string
     */
    public function getDefaultMethod();

    /**
     * Returns the description of the event.
     *
     * @return string
     */
    public function getDescription();

}