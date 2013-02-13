<?php

namespace merk\NotificationBundle\ModelManager;


interface NotificationKeyManagerInterface
{

    /**
     * @param $id
     * @throws \Exception
     * @return \merk\NotificationBundle\Model\notificationKey
     */
    public function find($id);

    /**
     * Fetch all objects
     *
     * @return \merk\NotificationBundle\Model\notificationKey[]
     */
    public function findAll();

    /**
     * Fetch object by notification key
     *
     * @param string $notificationKey
     * @return \merk\NotificationBundle\Model\NotificationKey
     */
    public function findByNotificationKey($notificationKey);


    /**
     * Fetch all objects that have the subscriberRoles
     *
     * @param string $subscriberRole
     * @throws \InvalidArgumentException
     * @return \merk\NotificationBundle\Model\notificationKey[]
     */
    public function findBySubscriberRole($subscriberRole);


    /**
     * Fetch all objects that have the subscriberRoles
     *
     * @param array $subscriberRoles
     * @throws \InvalidArgumentException
     * @return \merk\NotificationBundle\Model\notificationKey[]
     */
    public function findBySubscriberRoles($subscriberRoles);


}
