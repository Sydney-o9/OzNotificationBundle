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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
