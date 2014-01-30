<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney-o9 <https://github.com/Sydney-o9/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Entity;

use Oz\NotificationBundle\Model\MethodInterface;
use Oz\NotificationBundle\Model\MethodNotificationKeyInterface;


abstract class Method implements MethodInterface
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * Intermediate Association with NotificationKey
     *
     * @var MethodNotificationKeyInterface
     */
    protected $methodNotificationKey;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add association MethodNotificationKey
     *
     * @param MethodNotificationKeyInterface $methodNotificationKey
     * @return Method
     */
    public function addMethodNotificationKey(MethodNotificationKeyInterface $methodNotificationKey)
    {
        $this->methodNotificationKey[] = $methodNotificationKey;
        return $this;
    }

    /**
     * Remove association MethodNotificationKey
     *
     * @param MethodNotificationKeyInterface $methodNotificationKey
     */
    public function removeMethodNotificationKey(MethodNotificationKeyInterface $methodNotificationKey)
    {
        $this->methodNotificationKey->removeElement($methodNotificationKey);
    }

    /**
     * Get MethodNotificationKey
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
