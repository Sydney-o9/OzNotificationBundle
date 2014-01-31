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
use Oz\NotificationBundle\Model\FilterInterface;
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
     * Filters loaded from config file
     *
     * @return FilterInterface $filter
     */
    public function create()
    {
        $class = $this->class;

        return new $class;
    }
}
