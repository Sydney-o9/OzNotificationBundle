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
use merk\NotificationBundle\ModelManager\NotificationKeyManagerInterface;
use merk\NotificationBundle\Model\NotificationEventInterface;
use merk\NotificationBundle\Model\NotificationKeyInterface;
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
     * To fetch notification keys for filter generation
     *
     * @var NotificationKeyManager
     */
    protected $notificationKeyManager;


    public function __construct(EntityManager $em, $class, NotificationKeyManagerInterface $notificationKeyManager)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

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
     * @return \merk\NotificationBundle\Model\Filter[]
     */
    public function generateDefaultFilters(){

        $keys = $this->notificationKeyManager->findAll();

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
     *
     * @param FilterInterface $filter
     * @return \merk\NotificationBundle\Model\Method[]
     */
    public function generateDefaultMethods(FilterInterface $filter){

        return new ArrayCollection($filter->getNotificationKey()->getDefaultMethods()->toArray());
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
    public function getFilterForEventOwnedBySingleReceiver(NotificationEventInterface $event, UserInterface $receiver)
    {
        $qb = $this->repository->createQueryBuilder('f')
            ->select(array('f', 'up', 'u'))
            ->leftJoin('f.notificationKey', 'nk')
            ->leftJoin('f.userPreferences', 'up')
            ->leftJoin('up.user', 'u')
            ->andWhere('nk.notificationKey = :key')
            ->andWhere('u.username = :username')
            ->setParameter('key', (string)$event->getNotificationKey())
            ->setParameter('username', $receiver->getUsername());

        return $qb->getQuery()->getResult();
    }


    /**
     * Obtain filter for a particular user that subscribed to a particular notification key
     *
     * @param UserInterface $user
     * @param string | NotificationKeyInterface $notificationKey
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
     * Obtain users that subscribed to a particular notification key
     * TODO: Either move this function somewhere else or change AcmeUserBundle:User
     *
     *
     * @param string|NotificationKeyInterface $notificationKey
     * @return UserInterface[]
     */
    public function getSubscribedUsers($notificationKey)
    {
        $qb = $this->em->createQueryBuilder()
            ->select(array('u'))
            ->from('AcmeUserBundle:User','u')
            ->leftJoin('u.userPreferences', 'up')
            ->leftJoin('up.filters', 'f')
            ->leftJoin('f.notificationKey', 'nk')
            ->andWhere('nk.notificationKey = :key')
            ->setParameter('key', (string)$notificationKey);

        return $qb->getQuery()->getResult();
    }

    /**
     * Obtain users that did not subscribe to a particular notification key
     * TODO: Either move this function somewhere else or change AcmeUserBundle:User
     *
     * @param string|NotificationKeyInterface $notificationKey
     * @return UserInterface[]
     */
    public function getUnsubscribedUsers($notificationKey)
    {
        $qb=$this->em->createQueryBuilder();

        $subscribed = $qb
            ->select(array('u.id'))
            ->from('AcmeUserBundle:User','u')
            ->leftJoin('u.userPreferences', 'up')
            ->leftJoin('up.filters', 'f')
            ->leftJoin('f.notificationKey', 'nk')
            ->andWhere('nk.notificationKey = :key')
            ->getDQL();

        $query = $this->em->createQueryBuilder();

        $unsubscribed = $query
            ->select('user')
            ->from('AcmeUserBundle:User','user')
            ->where($query->expr()->notIn('user.id', $subscribed))
            ->setParameter('key', (string)$notificationKey);

        return $unsubscribed->getQuery()->getResult();
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