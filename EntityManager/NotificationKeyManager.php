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
     * @throws \InvalidArgumentException
     * @return \merk\NotificationBundle\Model\NotificationKey
     */
    public function findByNotificationKey($notificationKey)
    {
        if(!is_string($notificationKey)){
            throw new \InvalidArgumentException(sprintf('notificationKey should be a string, %s given.', gettype($notificationKey)));
        }

        $qb = $this->repository->createQueryBuilder('nek')
            ->select(array('nek'))
            ->andWhere('nek.notificationKey = :key')
            ->setParameter('key',$notificationKey);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Fetch all objects that are subscribable and have
     * the subscriberRole $subscriberRole
     *
     * @param string $subscriberRole
     * @throws \InvalidArgumentException
     * @return \merk\NotificationBundle\Model\notificationKey[]
     */
    public function findBySubscriberRole($subscriberRole)
    {
        if(!is_string($subscriberRole)){
            throw new \InvalidArgumentException(sprintf('subscriberRole should be a string, %s given.', gettype($subscriberRole)));
        }

        $qb = $this->repository->createQueryBuilder('nek');

        $qb ->select(array('nek'))
            ->where('nek.isSubscribable = :isSubscribable')
            ->andWhere($qb->expr()->like('nek.subscriberRoles', ':subscriberRole'))
            ->setParameter('isSubscribable', true)
            ->setParameter('subscriberRole',"%".$subscriberRole."%");

        return $qb->getQuery()->getResult();

    }

    /**
     * Fetch all objects that are subscribable and have
     * the subscriberRoles $subscriberRoles
     *
     * @param array $subscriberRoles
     * @throws \InvalidArgumentException
     * @return \merk\NotificationBundle\Model\notificationKey[]
     */
    public function findBySubscriberRoles($subscriberRoles)
    {
        if(!is_array($subscriberRoles)){
            throw new \InvalidArgumentException(sprintf('SubscriberRoles should be an array, %s given.', gettype($subscriberRoles)));
        }

        $qb = $this->repository->createQueryBuilder('nek');

        $qb->select(array('nek'));

        $i = 0;
        foreach ($subscriberRoles as $subscriberRole){

            $identifier = ':subscriberRole'.$i;

            $qb ->andWhere('nek.isSubscribable = :isSubscribable')
                ->orWhere($qb->expr()->like('nek.subscriberRoles', $identifier))
                ->setParameter('isSubscribable', true)
                ->setParameter($identifier,"%".$subscriberRole."%");

            $i++;

        }

        return $qb->getQuery()->getResult();

    }

}
