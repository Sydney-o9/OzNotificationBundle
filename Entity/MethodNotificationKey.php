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

use Doctrine\Common\Collections\ArrayCollection;
use Oz\NotificationBundle\Model\MethodNotificationKeyInterface;
use Oz\NotificationBundle\Model\NotificationKeyInterface;
use Oz\NotificationBundle\Model\MethodInterface;

abstract class MethodNotificationKey implements MethodNotificationKeyInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Method
     */
    protected $method;

    /**
     * Notification key
     */
    protected $notificationKey;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param MethodInterface $method
     */
    public function setMethod(MethodInterface $method)
    {
        $this->method = $method;
    }

    /**
     * @return NotificationKey
     */
    public function getNotificationKey()
    {
        return $this->notificationKey;
    }

    /**
     * @param NotificationKeyInterface $notificationKey
     */
    public function setNotificationKey(NotificationKeyInterface $notificationKey)
    {
        $this->notificationKey = $notificationKey;
    }

    /**
     * Identify this class with the method it contains.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->method;
    }

    /**
     * @var Bool $isDefault
     */
    protected $isDefault = true;

    /**
     * @var Bool $isCompulsory
     */
    protected $isCompulsory = false;

    /**
     * @param Bool $isCompulsory
     */
    public function setIsCompulsory($isCompulsory)
    {
        $this->isCompulsory = $isCompulsory;
    }

    /**
     * @return Bool
     */
    public function isCompulsory()
    {
        return $this->isCompulsory;
    }

    /**
     * @return Bool
     */
    public function getIsCompulsory()
    {
        return $this->isCompulsory;
    }

    /**
     * @param Bool $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return Bool
     */
    public function isDefault()
    {
        return $this->isDefault;
    }

    /**
     * @return Bool
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }
}
