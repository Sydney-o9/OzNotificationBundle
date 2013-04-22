<?php

namespace Oz\NotificationBundle\Entity;

use Oz\NotificationBundle\Model\Method as BaseMethod;


abstract class Method extends BaseMethod
{

    /**
     * Intermediate Association with NotificationKey
     */
    protected $methodNotificationKey;

    /**
     * Add association methodNotificationKey
     *
     * @param MethodNotificationKey $methodNotificationKey
     * @return Method
     */
    public function addMethodNotificationKey(MethodNotificationKey $methodNotificationKey)
    {
        $this->methodNotificationKey[] = $methodNotificationKey;
        return $this;
    }

    /**
     * Remove association methodNotificationKey
     *
     * @param MethodNotificationKey $methodNotificationKey
     */
    public function removeMethodNotificationKey(MethodNotificationKey $methodNotificationKey)
    {
        $this->methodNotificationKey->removeElement($methodNotificationKey);
    }

    /**
     * Get methodNotificationKey
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMethodNotificationKey()
    {
        return $this->methodNotificationKey;
    }


    /**
     * Identify the object with the name of the method
     *
     * @return string
     */
    public function __toString(){

        return (string)$this->name;

    }

}
