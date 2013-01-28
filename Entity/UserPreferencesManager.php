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
use merk\NotificationBundle\Model\NotificationEventInterface;
use merk\NotificationBundle\Model\UserPreferencesInterface;
use merk\NotificationBundle\Model\UserPreferencesManager as BaseUserPreferencesManager;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Doctrine ORM implementation of the UserPreferencesManager class.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class UserPreferencesManager extends BaseUserPreferencesManager
{
    protected $em;
    protected $repository;
    protected $class;

    public function __construct(EntityManager $em, $class, array $parameters)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

        $this->buildConfig($parameters);
    }

    public function findByUser(UserInterface $user)
    {
        $qb = $this->repository->createQueryBuilder('up');
        $qb->andWhere('up.user = :user');
        $qb->setParameter('user', $user);

        return $qb->getQuery()->getOneOrNullResult();
    }

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
     * Retrieve filters defined in config file.
     * (name, notification_key, default_methods, user_class)
     *
     */
    public function buildConfig(array $filters){

        foreach ($filters as $filter) {

            $notification_key = $filter['notification_key'];
            $default_methods = $filter['default_methods'];
            $user_class = $filter['user_class'];
            $description = $filter['description'];

            ladybug_dump($notification_key, $default_methods, $user_class, $description);

            //TODO: store the filters in the object

        }

    }

    /**
     * Create form with the filters loaded from the config file
     *
     */
    public function createDefaultForm(){
        //TODO: create default form with all the filters loaded with buildConfig

    }


}