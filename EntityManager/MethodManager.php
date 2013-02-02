<?php

namespace merk\NotificationBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use merk\NotificationBundle\Model\FilterInterface;
use merk\NotificationBundle\ModelManager\MethodManager as BaseMethodManager;


/**
 * Doctrine ORM implementation of the MethodManager class.
 *
 *
 */
class MethodManager extends BaseMethodManager
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