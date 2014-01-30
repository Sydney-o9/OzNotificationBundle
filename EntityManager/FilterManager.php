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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\ModelManager\FilterManagerInterface;
use Oz\NotificationBundle\Model\FilterInterface;
use Oz\NotificationBundle\ModelManager\NotificationKeyManagerInterface;
use Oz\NotificationBundle\Model\NotificationEventInterface;
use Oz\NotificationBundle\Model\NotificationKeyInterface;

/**
 * Manages filters.
 */
class FilterManager implements FilterManagerInterface
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
     * @var string
     */
    protected $userClass;

    /**
     * To fetch notification keys for filter generation
     *
     * @var NotificationKeyManager
     */
    protected $notificationKeyManager;


    public function __construct(EntityManager $em, $class, $userClass, NotificationKeyManagerInterface $notificationKeyManager)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $em->getClassMetadata($class)->name;
        $this->userClass = $em->getClassMetadata($userClass)->name;
        $this->notificationKeyManager = $notificationKeyManager;
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
     * Generate the default filters available to the user
     * using the notificationKey and the default methods
     * associated to it.
     *
     * 1 filter <----> 1 particular event / 1 notification key
     *
     * @param $user
     * @return \Oz\NotificationBundle\Model\Filter[]
     */
    public function generateDefaultFilters($user){

        $roles = $user->getRoles();

        $keys = $this->notificationKeyManager->findBySubscriberRoles($roles);

        $filters = new ArrayCollection();

        foreach ($keys as $key){
            $filter = $this->create();
            $filter->setNotificationKey($key);

            /** Methods are empty, fill with default methods */
            $defaultMethods = $this->generateDefaultMethods($filter);
            $filter->setMethods($defaultMethods);

            $filters[]= $filter;
        }

        return $filters;
    }

    /**
     * Generate ArrayCollection of the default methods
     * for a particular filter
     *
     * @param FilterInterface $filter
     * @return \Oz\NotificationBundle\Model\Method[]
     */
    public function generateDefaultMethods(FilterInterface $filter){

        return new ArrayCollection($filter->getNotificationKey()->getDefaultMethods()->toArray());
    }


    /**
     * Find Filters By User
     *
     * @param UserInterface $user
     * @return \Oz\NotificationBundle\Model\Filter[]
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
            ->andWhere('nk.key = :key');

        return $qb->getQuery()->execute(array(
            'key' => (string)$event->getNotificationKey()
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getFilterOwnedByUser(NotificationEventInterface $event, UserInterface $user)
    {
        $qb = $this->repository->createQueryBuilder('f')
            ->select(array('f', 'up', 'u'))
            ->leftJoin('f.notificationKey', 'nk')
            ->leftJoin('f.userPreferences', 'up')
            ->leftJoin('up.user', 'u')
            ->andWhere('nk.key = :key')
            ->andWhere('u.username = :username')
            ->setParameter('key', (string)$event->getNotificationKey())
            ->setParameter('username', $user->getUsername());

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Obtain filter for a particular user that subscribed to a particular notification key
     *
     * @param UserInterface $user
     * @param string | NotificationKeyInterface $notificationKey
     * @return \Oz\NotificationBundle\Model\Filter|null
     */
    public function getUserFilterByNotificationKey(UserInterface $user, $notificationKey)
    {
        $qb = $this->repository->createQueryBuilder('f')
            ->select(array('f'))
            ->leftJoin('f.notificationKey', 'nk')
            ->leftJoin('f.userPreferences', 'up')
            ->leftJoin('up.user', 'u')
            ->andWhere('nk.key = :key')
            ->andWhere('u.username = :username')
            ->setParameter('key', (string)$notificationKey)
            ->setParameter('username', $user->getUsername());

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Obtain users that subscribed to a particular notification key
     *
     * (USERS THAT HAVE A FILTER FOR THAT NOTIFICATION KEY AND HAVE
     *  SAVED THEIR PREFERRED METHODS)
     *
     * @param string|NotificationKeyInterface $notificationKey
     * @return UserInterface[]
     */
    public function getSubscribedUsers($notificationKey)
    {
        /** Obtain an array of user_id that subscribed to that notification key */
        $subQueryBuilder = $this->em
            ->createQueryBuilder();

        $subQueryBuilder
            ->select('u.id')
        //from the filter class
            ->from($this->class, 'f')
        //get user preferences
            ->leftJoin('f.userPreferences', 'up')
        //get the users
            ->leftJoin('up.user', 'u')
        //get the notification key
            ->leftJoin('f.notificationKey', 'nk')
        //with the appropriate notification key
            ->andWhere('nk.key = :key')
            ->andWhere('size(f.methods) >= 1')
            ->setParameter('key', (string)$notificationKey);

        /** @var array An array of user ids */
        $userIds = $subQueryBuilder
            ->getQuery()
            ->getResult();

        return $this->getUsers($userIds);
    }

    /**
     * Obtain users that did not subscribe to a particular notification key
     *
     * (USERS THAT HAVE A FILTER FOR THAT NOTIFICATION KEY BUT THAT DO NOT
     *  HAVE ANY METHODS)
     *
     *
     * @param string|NotificationKeyInterface $notificationKey
     * @return UserInterface[]
     */
    public function getUnsubscribedUsers($notificationKey)
    {
        /** Obtain an array of user_id that are not subscribed to that notification key */
        $subQueryBuilder = $this->em
            ->createQueryBuilder();

        $subQueryBuilder
            ->select('u.id')
        //from the filter class
            ->from($this->class, 'f')
        //get user preferences
            ->leftJoin('f.userPreferences', 'up')
        //get the users
            ->leftJoin('up.user', 'u')
        //get the notification key
            ->leftJoin('f.notificationKey', 'nk')
        //with the appropriate notification key
            ->andWhere('nk.key = :key')
            ->andWhere('f.methods IS EMPTY')
            ->setParameter('key', (string)$notificationKey);

        /** @var array An array of user ids */
        $userIds = $subQueryBuilder
            ->getQuery()
            ->getResult();

        return $this->getUsers($userIds);
    }


    /**
     * Obtain users that are committed to a particular notification key
     *
     * (USERS THAT UPDATED THEIR PREFERENCES AND THEREFORE
     *  HAVE A FILTER.)
     *
     * @param string|NotificationKeyInterface $notificationKey
     * @return UserInterface[]
     */
    public function getCommittedUsers($notificationKey)
    {
        /** Obtain an array of user_id that are committed to a notification key */
        $subQueryBuilder = $this->em
            ->createQueryBuilder();

        $subQueryBuilder
            ->select('u.id')
        //from the filter class
            ->from($this->class, 'f')
        //get user preferences
            ->leftJoin('f.userPreferences', 'up')
        //get the users
            ->leftJoin('up.user', 'u')
        //get the notification key
            ->leftJoin('f.notificationKey', 'nk')
        //with the appropriate notification key
            ->andWhere('nk.key = :key')
            ->setParameter('key', (string)$notificationKey);

        /** @var array An array of user ids */
        $userIds = $subQueryBuilder
            ->getQuery()
            ->getResult();

        return $this->getUsers($userIds);
    }

    /**
     * Obtain users that are not committed to a particular notification key
     * They haven't told their preference yet
     *
     * (USERS THAT DID NOT UPDATE THEIR PREFERENCES AND THEREFORE DO NOT
     *  HAVE A FILTER YET.)
     *
     * @param string|NotificationKeyInterface $notificationKey
     * @return UserInterface[]
     */
    public function getUncommittedUsers($notificationKey)
    {
        $subQueryBuilder=$this->em
            ->createQueryBuilder();

        $subQueryBuilder
            ->select('u.id')
        //from the filter class
            ->from($this->class, 'f')
        //get user preferences
            ->leftJoin('f.userPreferences', 'up')
        //get the users
            ->leftJoin('up.user', 'u')
        //get the notification key
            ->leftJoin('f.notificationKey', 'nk')
        //with the appropriate notification key
            ->andWhere('nk.key = :key')
            ->setParameter('key', (string)$notificationKey);

        $userIds = $subQueryBuilder
            ->getQuery()
            ->getResult();

        return $this->getUsers($userIds, false);
    }

    /**
     * Get an array of users that are or are not within array $userIds
     *
     * @param array Array of user ids
     * @param Bool True if we want the users that are in $userIds
     *             False if we want the users that are not in $userIds
     */
    public function getUsers(array $userIds = array(), $inArray = true)
    {
        /** Get all the users that have an id within $userIds array */
        $queryBuilder = $this->em
            ->createQueryBuilder();

        $queryBuilder
            ->select('u')
        //from the user class
            ->from($this->userClass, 'u');

        //users who have an id within $userIds array
        if ($inArray) {
            $queryBuilder
                ->andWhere('u.id IN (:user_ids)');
        } else {
            $queryBuilder
                ->andWhere('u.id NOT IN (:user_ids)');
        }
        //set array of user_ids
        $queryBuilder
            ->setParameter('user_ids', $userIds);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }


    /**
     * Returns true if a particular user is subscribed to a particular notification key
     *
     * @param UserInterface $user
     * @param NotificationKeyInterface $notificationKey
     * @return boolean
     */
    public function isUserSubscribedTo(UserInterface $user, $notificationKey)
    {
        return ($this->getUserFilterByNotificationKey($user, $notificationKey)) ? true :false;
    }

}
