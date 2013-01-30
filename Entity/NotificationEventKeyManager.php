<?php

namespace merk\NotificationBundle\Entity;

use Doctrine\ORM\EntityManager;

use merk\NotificationBundle\Model\NotificationEventKeyManager as BaseNotificationEventKeyManager;

class NotificationEventKeyManager extends BaseNotificationEventKeyManager
{
    /**
     * @var EntityManager.
     */
    protected $em;

    /**
     * @var EntityRepository of the NotificationEventKey class.
     */
    protected $repository;

    /**
     * @var string NotificationEventKey entity name.
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
     * @return notificationEventKey
     */
    public function find($id)
    {
        $notificationEventKey =  $this->repository->find($id);

        if(!$notificationEventKey)
        {
            throw new \Exception('Unable to find Notification Event Key');
        }
        else
        {
            return $notificationEventKey;
        }

    }


    /**
     * Fetch object by notification key
     *
     * @return NotificationEventKey
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
