<?php

namespace merk\NotificationBundle\EntityManager;

use Doctrine\ORM\EntityManager;

use merk\NotificationBundle\ModelManager\NotificationKeyManager as BaseNotificationKeyManager;

class NotificationKeyManager extends BaseNotificationKeyManager
{
    /**
     * @var EntityManager.
     */
    protected $em;

    /**
     * @var EntityRepository of the NotificationKey class.
     */
    protected $repository;

    /**
     * @var string NotificationKey entity name.
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
     * @param $id
     * @throws \Exception
     * @return \merk\NotificationBundle\Model\notificationKey
     */
    public function find($id)
    {
        $notificationKey =  $this->repository->find($id);

        if(!$notificationKey)
        {
            throw new \Exception('Unable to find Notification Event Key');
        }
        else
        {
            return $notificationKey;
        }

    }

    /**
     * Fetch all objects
     *
     * @return \merk\NotificationBundle\Model\notificationKey[]
     */
    public function findAll()
    {
        return  $this->repository->findAll();
    }


    /**
     * Fetch object by notification key
     *
     * @param string $notificationKey
     * @return \merk\NotificationBundle\Model\NotificationKey
     */
    public function findByNotificationKey($notificationKey)
    {

        $qb = $this->repository->createQueryBuilder('nek')
            ->select(array('nek'))
            ->andWhere('nek.notificationKey = :key')
            ->setParameter('key',$notificationKey);

        return $qb->getQuery()->getOneOrNullResult();
    }



}
