<?php

namespace merk\NotificationBundle\NotificationFactory;

interface NotificationFactoryInterface
{
    /**
     * @param string $class
     */
    static function build($class);
}