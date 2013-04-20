<?php

namespace Oz\NotificationBundle\ModelManager;


interface NotificationKeyManagerInterface
{

    /**
     * @param $id
     * @throws \Exception
     * @return \Oz\NotificationBundle\Model\notificationKey
     */
    public function find($id);

    /**
     * Fetch all objects
     *
     * @return \Oz\NotificationBundle\Model\notificationKey[]
     */
    public function findAll();

    /**
     * Fetch object by notification key
     *
     * @param string $notificationKey
     * @return \Oz\NotificationBundle\Model\NotificationKey
     */
    public function findByNotificationKey($notificationKey);


    /**
     * Fetch all objects that have the subscriberRoles
     *
     * @param string $subscriberRole
     * @throws \InvalidArgumentException
     * @return \Oz\NotificationBundle\Model\notificationKey[]
     */
    public function findBySubscriberRole($subscriberRole);


    /**
     * Fetch all objects that have the subscriberRoles
     *
     * @param array $subscriberRoles
     * @throws \InvalidArgumentException
     * @return \Oz\NotificationBundle\Model\notificationKey[]
     */
    public function findBySubscriberRoles($subscriberRoles);


}
