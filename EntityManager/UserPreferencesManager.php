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
use merk\NotificationBundle\ModelManager\FilterManagerInterface;
use merk\NotificationBundle\Model\UserPreferencesInterface;
use merk\NotificationBundle\ModelManager\UserPreferencesManager as BaseUserPreferencesManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM implementation of the UserPreferencesManager class.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class UserPreferencesManager extends BaseUserPreferencesManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserPreferencesRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /*
     * FilterManager
     */
    protected $filterManager;


    public function __construct(EntityManager $em, $class, FilterManagerInterface $filterManager)
    {

        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
        $this->filterManager = $filterManager;

    }

    /*
     * Find User Preferences By User
     */
    public function findByUser(UserInterface $user)
    {
        $qb = $this->repository->createQueryBuilder('up');
        $qb->andWhere('up.user = :user');
        $qb->setParameter('user', $user);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /*
     * Update User Preferences
     */
    public function update(UserPreferencesInterface $preferences, $flush = true)
    {
        $this->em->persist($preferences);

        //Persist new filters when they exist
        foreach($preferences->getFilters() as $filter)
        {
            $this->em->persist($filter);
        }

        if ($flush) {
            $this->em->flush();
        }

    }

    /**
     *
     *
     * Get User Preferences in 2 steps:
     *  - generate all empty/default filters
     *  - if the user has one of these filters already, replace it
     *
     * @param UserInterface $user
     * @return \merk\NotificationBundle\Model\UserPreferencesInterface|mixed
     */
    public function getUserPreferences($user){

        if (!$userPreferences = $this->findByUser($user)){
            $userPreferences = $this->create();
            $userPreferences->setUser($user);
        }
        /** @var ArrayCollection $emptyFilters */
        $emptyFilters = $this->filterManager->generateAllEmptyFilters();

        $filters = array();

        foreach ($emptyFilters as $emptyFilter){

            /** If user has already subscribed to it */
            if ($filter = $this->filterManager->getUserFilterByNotificationKey($user, $emptyFilter->getNotificationKey())){

                $filters[]= $filter;
            }
            /** otherwise, create new filter */
            else{

                $emptyFilter->setUserPreferences($userPreferences);
                $filters[] = $emptyFilter;
            }
        }
        $userPreferences->setFilters($filters);

        return $userPreferences;
    }

}