<?php

namespace Oz\NotificationBundle\ModelManager;

use Oz\NotificationBundle\Model\NotificationKeyInterface;


interface NotificationKeyManagerInterface
{
    /**
     * @param int $id
     * @throws \Exception
     * @return NotificationKeyInterface
     */
    public function find($id);

    /**
     * Find all notification keys
     *
     * @return NotificationKeyInterface[]
     */
    public function findAll();

    /**
     * Find a NotificationKey by its unique key
     *
     * @param string $key
     * @return NotificationKeyInterface
     */
    public function findByNotificationKey($key);

    /**
     * Find all NotificationKey objects that are subscribable and have
     * the subscriberRole $subscriberRole
     *
     * @param string $subscriberRole
     * @throws \InvalidArgumentException
     * @return NotificationKeyInterface[]
     */
    public function findBySubscriberRole($subscriberRole);

    /**
     * Find all NotificationKey objects that are subscribable and have
     * the subscriberRoles $subscriberRoles
     *
     * @param array $subscriberRoles
     * @throws \InvalidArgumentException
     * @return NotificationKeyInterface[]
     */
    public function findBySubscriberRoles(array $subscriberRoles = array());
}
