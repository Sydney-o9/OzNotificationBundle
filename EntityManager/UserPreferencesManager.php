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

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Oz\NotificationBundle\ModelManager\FilterManagerInterface;
use Oz\NotificationBundle\Model\UserPreferencesInterface;
use Oz\NotificationBundle\ModelManager\UserPreferencesManagerInterface;

/**
 * Manages User Preferences
 */
class UserPreferencesManager implements UserPreferencesManagerInterface
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
     * Class to use when initialising a new UserPreferences object.
     *
     * @var string
     */
    protected $class;

    /**
     * @var FilterManagerInterface
     */
    protected $filterManager;


    public function __construct(EntityManager $em, $class, FilterManagerInterface $filterManager)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $em->getClassMetadata($class)->name;
        $this->filterManager = $filterManager;
    }

    /**
     * Creates a new UserPreferences object.
     *
     * @return UserPreferencesInterface
     */
    public function create()
    {
        return new $this->class;
    }

    /**
     * Find User Preferences By User
     *
     * @param UserInterface $user
     */
    public function findByUser(UserInterface $user)
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('up');

        $queryBuilder
            ->andWhere('up.user = :user')
            ->setParameter('user', $user);

        return $queryBuilder->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Update User Preferences
     *
     * @param UserPreferencesInterface $preferences
     * @param Bool $andFlush
     */
    public function update(UserPreferencesInterface $preferences, $andFlush = true)
    {
        $this->em->persist($preferences);

        /** Persist new filters when they exist */
        foreach($preferences->getFilters() as $filter) {
            $this->em->persist($filter);
        }

        if ($andFlush) {
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
     * @return \Oz\NotificationBundle\Model\UserPreferencesInterface|mixed
     */
    public function getUserPreferences($user){

        if (!$userPreferences = $this->findByUser($user)){
            $userPreferences = $this->create()
                ->setUser($user);
        }

        /** @var ArrayCollection $defaultFilters */
        $defaultFilters = $this->filterManager
            ->generateDefaultFilters($user);

        $filters = new ArrayCollection();

        foreach ($defaultFilters as $defaultFilter){

            /** If user has already subscribed to it */
            if ($filter = $this->filterManager->getUserFilterByNotificationKey($user, $defaultFilter->getNotificationKey())){

                $filters[]= $filter;
            }
            /** otherwise, create new filter */
            else{

                $defaultFilter->setUserPreferences($userPreferences);
                $filters[] = $defaultFilter;
            }
        }
        $userPreferences->setFilters($filters);

        return $userPreferences;
    }

}
