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

namespace Oz\NotificationBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use Oz\NotificationBundle\Model\MethodInterface;
use Oz\NotificationBundle\ModelManager\MethodManagerInterface;

/**
 * Manages methods.
 */
class MethodManager implements MethodManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var MethodRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;


    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

    }

    /**
     * Create a new method
     *
     * @return MethodInterface $method
     */
    public function create()
    {
        $class = $this->class;

        return new $class;
    }

    /**
     * {@inheritDoc}
     */
    public function update(MethodInterface $method, $flush = true)
    {
        $this->em->persist($method);

        if ($flush) {
            $this->em->flush();
        }
    }
}
