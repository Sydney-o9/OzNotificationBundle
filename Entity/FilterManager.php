<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\Entity;

use Doctrine\ORM\EntityManager;
use merk\NotificationBundle\Model\FilterInterface;
use merk\NotificationBundle\Model\FilterManager as BaseFilterManager;
use merk\NotificationBundle\Model\NotificationEventInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Doctrine ORM implementation of the FilterManager class.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class FilterManager extends BaseFilterManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FilterRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Filters loaded from config file
     *
     * @var array|\Doctrine\Common\Collections\Collection FilterInterface[]
     */
    protected $configFilters;


    public function __construct(EntityManager $em, $class, array $filterParameters)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

        $this->buildConfigFilters($filterParameters);

    }

    public function create()
    {
        $class = $this->class;

        return new $class;
    }

    public function getFiltersForEvent(NotificationEventInterface $event)
    {
        $qb = $this->repository->createQueryBuilder('f')
            ->select(array('f', 'up', 'u'))
            ->leftJoin('f.userPreferences', 'up')
            ->leftJoin('up.user', 'u')
            ->andWhere('f.notificationKey = :key');

        return $qb->getQuery()->execute(array(
            'key' => $event->getNotificationKey()
        ));
    }


    /**
     * Build the filters from the following parameters
     * defined in config file
     *
     * @param Array $filtersParams
     * @return array|\Doctrine\Common\Collections\Collection FilterInterface[]
     */
    public function buildConfigFilters(array $filterParameters){

        $configFilters = new ArrayCollection();

        foreach ($filterParameters as $filterParam) {

            $filter = $this->create();
            $filter->setNotificationKey($filterParam['notification_key']);

            //TODO: Does not support array at the moment
            $default_methods = $filterParam['default_methods'];
            $filter->setMethod($default_methods[0]);

            //TODO: Filter are not configured to support the user class at the moment.
            //$user_class = $filterParam['user_class'];

            $configFilters[] = $filter;

        }

        $this->setConfigFilters($configFilters);
    }



    /**
     * @param array|\Doctrine\Common\Collections\Collection $configFilters
     */
    public function setConfigFilters($configFilters)
    {
        $this->configFilters = $configFilters;
    }

    /**
     * @return array|\Doctrine\Common\Collections\Collection
     */
    public function getConfigFilters()
    {
        return $this->configFilters;
    }



}