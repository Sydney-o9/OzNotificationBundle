<?php

namespace Oz\NotificationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Oz\NotificationBundle\Model\NotificationKey as BaseNotificationKey;

/**
 * {@inheritDoc}
 */
abstract class NotificationKey extends BaseNotificationKey
{

    /**
     *   Intermediate Association with Method
     *      via "methodNotificationKey"
     */

    /**
     * @var ArrayCollection
     */
    protected $methodNotificationKey;

    /**
     * @return ArrayCollection methodNotificationKey
     */
    public function getMethodNotificationKey()
    {
        return $this->methodNotificationKey;
    }

    /**
     * @param ArrayCollection $methodNotificationKey
     */
    public function setMethodNotificationKey($methodNotificationKey)
    {
        $this->methodNotificationKey= $methodNotificationKey;
    }

    /**
     * @param MethodNotificationKey $methodNotificationKey
     */
    public function addMethodNotificationKey($methodNotificationKey)
    {
        $this->methodNotificationKey[] = $methodNotificationKey;
    }

    /**
     * @param MethodNotificationKey $methodNotificationKey
     * @return bool
     */
    public function removeMethodNotificationKey($methodNotificationKey)
    {
        return $this->methodNotificationKey->removeElement($methodNotificationKey);
    }

    /**
     *   Many-Many Relation with  Method
     *      through "methodNotificationKey"
     */

    /**
     *
     * @var ArrayCollection
     */
    protected $methods;

    /**
     * @return ArrayCollection Method[]
     */
    public function getMethods()
    {
        $methods = new ArrayCollection();

        foreach($this->methodNotificationKey as $mN)
        {
            $methods[] = $mN->getMethod();
        }

        return $methods;
    }

    /**
     * @param ArrayCollection Method[]
     */
    public function setMethods($methods)
    {

        foreach($methods as $m)
        {
            $methodNotificationKey = new MethodNotificationKey();

            $methodNotificationKey->setNotificationKey($this);
            $methodNotificationKey->setMethod($m);

            $this->addMethodNotificationKey($methodNotificationKey);
        }

    }


    /**
     * @return ArrayCollection Method[]
     */
    public function getDefaultMethods()
    {
        $methods = new ArrayCollection();

        foreach($this->methodNotificationKey as $mN)
        {
            if($mN->isDefault()){
                $methods[] = $mN->getMethod();
            }
        }

        return $methods;
    }

    /**
     * @return ArrayCollection Method[]
     */
    public function getCompulsoryMethods()
    {
        $methods = new ArrayCollection();

        foreach($this->methodNotificationKey as $mN)
        {
            if($mN->isCompulsory()){
                $methods[] = $mN->getMethod();
            }
        }

        return $methods;
    }

    public function __construct()
    {
        parent::__construct();

        $this->methodNotificationKey = new ArrayCollection();
        $this->methods = new ArrayCollection();
    }

}
