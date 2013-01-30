<?php

namespace merk\NotificationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class NotificationEventKeyManager implements NotificationEventKeyManagerInterface
{

    /**
     * @var string NotificationEvent entity name
     */
    protected $class;

//    /**
//     * {@inheritDoc}
//     */
//    public function create($notificationKey, $defaultMethod, $description)
//    {
//
//        $class = $this->class;
//
//        return new $class($notificationKey, $defaultMethod, $description);
//    }


}