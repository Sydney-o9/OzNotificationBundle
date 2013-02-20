<?php

namespace merk\NotificationBundle\NotificationFactory;

interface NotificationFactoryInterface
{
    /**
     *
     * @param string $class
     * @throws \Exception
     * @return \merk\NotificationBundle\Model\Notification (a child of)
     */
    public function build($class);


    /**
     * Set class for notification
     * TODO: is this the best strategy?
     * @param $class
     */
    public function setClass($class);

    /**
     * Create notification using the filter used by the user
     *
     * @param $event
     * @param $filter
     * @return \merk\NotificationBundle\Model\Notification
     */
    public function createNotificationFromFilter($event, $filter);

    /**
     * Create notification using the user
     *
     * @param $event
     * @param $user
     * @return \merk\NotificationBundle\Model\Notification
     */
    public function createNotificationFromUser($event, $user);

}