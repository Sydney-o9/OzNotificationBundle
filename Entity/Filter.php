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
use Oz\NotificationBundle\Model\FilterInterface;
use Oz\NotificationBundle\Model\UserPreferencesInterface;
use Oz\NotificationBundle\Model\NotificationKeyInterface;

/**
 * Filter class
 */
abstract class Filter implements FilterInterface
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var NotificationKeyInterface
     */
    protected $notificationKey;

    /**
     * @var string
     */
    protected $recipientData;

    /**
     * @var string
     */
    protected $recipientName;

    /**
     * @var UserPreferencesInterface
     */
    protected $userPreferences;

    /**
     * @var MethodInterface[]
     */
    protected $methods;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->methods = new ArrayCollection;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @param UserPreferencesInterface|null $userPreferences
     */
    public function setUserPreferences(UserPreferencesInterface $userPreferences = null)
    {
        $this->userPreferences = $userPreferences;
    }

    /**
     * @return string
     */
    public function getRecipientData()
    {
        if ($this->recipientData) {
            return $this->recipientData;
        }

        if ($this->getUserPreferences()) {
            return $this->getUserPreferences()->getUser()->getEmail();
        }

        return null;
    }

    /**
     * @param string $recipientData
     */
    public function setRecipientData($recipientData)
    {
        $this->recipientData = $recipientData;
    }

    /**
     * @return null|string
     */
    public function getRecipientName()
    {
        if ($this->recipientName) {
            return $this->recipientName;
        }

        if ($this->getUserPreferences()) {
            return $this->getUserPreferences()->getUser()->getUsername();
        }

        return null;
    }

    /**
     * @param string $recipientName
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    /**
     * @return UserPreferencesInterface
     */
    public function getUserPreferences()
    {
        return $this->userPreferences;
    }

    /**
     * @param MethodInterface $method
     */
    public function addMethod(MethodInterface $method)
    {
        $this->methods[] = $method;
    }

    /**
     * @param MethodInterface $method
     * @return bool
     */
    public function removeMethod(MethodInterface $method)
    {
        return $this->methods->removeElement($method);
    }

    /**
     * @param ArrayCollection $methods
     */
    public function setMethods(ArrayCollection $methods)
    {
        $this->methods = $methods;
    }

    /**
     * @return ArrayCollection \Jbh\NotificationBundle\Entity\MethodInterface
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /*
     * A filter can be applied to one and only one notification key
     * It is therefore
     */
    public function __toString(){

        return (string)$this->notificationKey.".filter";

    }

}
