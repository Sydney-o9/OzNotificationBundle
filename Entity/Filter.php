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

use Oz\NotificationBundle\Model\Filter as BaseFilter;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM implementation of the Filter class
 */
abstract class Filter extends BaseFilter
{

    /**
     * @var MethodInterface[]
     */
    protected $methods;


    public function __construct()
    {
        $this->methods = new ArrayCollection;
    }

    /**
     * @param Method $method
     */
    public function addMethod($method)
    {
        $this->methods[] = $method;
    }

    /**
     * @param Method $method
     * @return bool
     */
    public function removeMethod($method)
    {
        return $this->methods->removeElement($method);
    }

    /**
     * @param ArrayCollection $methods
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;
    }

    /**
     * @return \Jbh\NotificationBundle\Entity\Method[]|\Doctrine\Common\Collections\ArrayCollection
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
