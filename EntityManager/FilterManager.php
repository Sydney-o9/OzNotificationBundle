<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use merk\NotificationBundle\Model\FilterInterface;
use merk\NotificationBundle\ModelManager\FilterManager as BaseFilterManager;
use merk\NotificationBundle\ModelManager\NotificationEventKeyManagerInterface;
use merk\NotificationBundle\Model\NotificationEventInterface;
use merk\NotificationBundle\Model\NotificationEventKeyInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

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

    /**
     * To fetch notification keys for filter generation
     *
     * @var NotificationEventKeyManager
     */
    protected $notificationEventKeyManager;


    public function __construct(EntityManager $em, $class, NotificationEventKeyManagerInterface $notificationEventKeyManager, $filterParameters)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

        $this->notificationEventKeyManager = $notificationEventKeyManager;

        $this->buildConfigFilters($filterParameters);

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

    /**
     * Generate all filters available to the user
     * by using the notificationKey
     *
     * 1 filter <----> 1 particular event / 1 notification key
     *
     * @return \merk\NotificationBundle\Model\Filter[]
     */
    public function generateAllEmptyFilters(){

        $keys = $this->notificationEventKeyManager->findAll();

        $filters = new ArrayCollection();
        foreach ($keys as $key){
            $filter = $this->create();
            $filter->setNotificationKey($key);
            $filters[]= $filter;
        }

        return $filters;
    }




    /**
     * Find Filters By User
     *
     * @param UserInterface $user
     * @return \merk\NotificationBundle\Model\Filter[]
     */
    public function findByUser(UserInterface $user)
    {
        $qb = $this->repository->createQueryBuilder('f')
        ->select(array('f'))
        ->leftJoin('f.userPreferences', 'up')
        ->andWhere('up.user = :user')
        ->setParameter('user', $user);

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    /**
     * {@inheritDoc}
     */
    public function getFiltersForEvent(NotificationEventInterface $event)
    {
        $qb = $this->repository->createQueryBuilder('f')
            ->select(array('f', 'up', 'u'))
            ->leftJoin('f.notificationKey', 'nk')
            ->leftJoin('f.userPreferences', 'up')
            ->leftJoin('up.user', 'u')
            ->andWhere('nk.notificationKey = :key');

        return $qb->getQuery()->execute(array(
            'key' => (string)$event->getNotificationKey()
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getFiltersForEventOwnedBySingleReceiver(NotificationEventInterface $event, UserInterface $receiver)
    {
        $qb = $this->repository->createQueryBuilder('f')
            ->select(array('f', 'up', 'u'))
            ->leftJoin('f.notificationKey', 'nk')
            ->leftJoin('f.userPreferences', 'up')
            ->leftJoin('up.user', 'u')
            ->andWhere('nk.notificationKey = :key')
            ->andWhere('u.username = :username');

        return $qb->getQuery()->execute(array(
            'key' => (string)$event->getNotificationKey(),
            'username' => $receiver->getUsername()
        ));
    }


    /**
     * Obtain filter for a particular user that subscribed to a particular notification key
     *
     * @param UserInterface $user
     * @param string | NotificationEventKeyInterface $notificationKey
     * @return \merk\NotificationBundle\Model\Filter|null
     */
    public function getUserFilterByNotificationKey(UserInterface $user, $notificationKey)
    {
        $qb = $this->repository->createQueryBuilder('f')
            ->select(array('f'))
            ->leftJoin('f.notificationKey', 'nk')
            ->leftJoin('f.userPreferences', 'up')
            ->leftJoin('up.user', 'u')
            ->andWhere('nk.notificationKey = :key')
            ->andWhere('u.username = :username')
            ->setParameter('key', (string)$notificationKey)
            ->setParameter('username', $user->getUsername());

        return $qb->getQuery()->getOneOrNullResult();
    }


    /**
     * Returns true if a particular user is subscribed to a particular notification key
     *
     * @param UserInterface $user
     * @param NotificationEventKeyInterface $notificationKey
     * @return boolean
     */
    public function isUserSubscribedTo(UserInterface $user, NotificationEventKeyInterface $notificationKey)
    {
        return ($this->getUserFilterByNotificationKey($user, $notificationKey)) ? true :false;
    }


    /**
     * {@inheritDoc}
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
     *
     * Obtain all default filters defined in config file
     *
     * @return array|\Doctrine\Common\Collections\Collection
     */
    public function getConfigFilters()
    {
        return $this->configFilters;
    }
}