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
use Oz\NotificationBundle\Model\MethodInterface;
use Oz\NotificationBundle\Model\MethodMetadataInterface;


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
     * @var MethodMetadataInterface
     */
    protected $methodMetadata;

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
     * Add association MethodMetadata
     *
     * @param MethodMetadataInterface $methodMetadata
     * @return Method
     */
    public function addMethodMetadata(MethodMetadataInterface $methodMetadata)
    {
        $this->methodMetadata[] = $methodMetadata;
        return $this;
    }

    /**
     * Remove association MethodMetadata
     *
     * @param MethodMetadataInterface $methodMetadata
     */
    public function removeMethodMetadata(MethodMetadataInterface $methodMetadata)
    {
        $this->methodMetadata->removeElement($methodMetadata);
    }

    /**
     * Get MethodMetadata
     *
     * @return ArrayCollection
     */
    public function getMethodMetadata()
    {
        return $this->methodMetadata;
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
