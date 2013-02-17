<?php

namespace merk\NotificationBundle\NotificationFactory;

use merk\NotificationBundle\NotificationFactory\NotificationFactoryInterface;


class NotificationFactory implements NotificationFactoryInterface
{
    /**
     *
     * @param string $class
     * @return \merk\NotificationBundle\Model\class
     */
    public static function build($class)
    {
        $notification = new $class;
        return $notification;
    }
}