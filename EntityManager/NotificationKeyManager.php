<?php

namespace Oz\NotificationBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use Oz\NotificationBundle\ModelManager\NotificationKeyManagerInterface;
use Oz\NotificationBundle\Model\NotificationKeyInterface;

class NotificationKeyManager implements NotificationKeyManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository of the NotificationKey class
     */
    protected $repository;

    /**
     * @var string NotificationKey entity name
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
     * Find notification key
     *
     * @param int $id
     * @throws \Exception
     * @return NotificationKeyInterface
     */
    public function find($id)
    {
        $notificationKey =  $this->repository
            ->find($id);

        if(!$notificationKey) {
            throw new \Exception( sprintf('Unable to find NotificationKey with id %s.', strval($id)) );
        }

        return $notificationKey;
    }

    /**
     * Find all notification keys
     *
     * @return NotificationKeyInterface[]
     */
    public function findAll()
    {
        return  $this->repository->findAll();
    }


    /**
     * Find a NotificationKey by its unique key phrase
     *
     * @param string $key
     * @throws \InvalidArgumentException
     * @return NotificationKeyInterface
     */
    public function findByNotificationKey($notificationKey)
    {
        if(!is_string($notificationKey)){
            throw new \InvalidArgumentException( sprintf('notificationKey should be a string, %s given.', gettype($notificationKey)) );
        }

        $queryBuilder = $this->repository
            ->createQueryBuilder('nk');

        $queryBuilder
            ->select(array('nk'))
            ->andWhere('nk.notificationKey = :notificationKey')
            ->setParameter('notificationKey', $notificationKey);

        return $queryBuilder->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find all NotificationKey objects that are subscribable and have
     * the subscriberRole $subscriberRole
     *
     * @param string $subscriberRole
     * @throws \InvalidArgumentException
     * @return NotificationKeyInterface[]
     */
    public function findBySubscriberRole($subscriberRole)
    {
        if(!is_string($subscriberRole)){
            throw new \InvalidArgumentException(sprintf('subscriberRole should be a string, %s given.', gettype($subscriberRole)));
        }

        $queryBuilder = $this->repository
            ->createQueryBuilder('nk');

        $queryBuilder
            ->select(array('nk'))
            ->where('nk.isSubscribable = :isSubscribable')
            ->andWhere($queryBuilder->expr()->like('nk.subscriberRoles', ':subscriberRole'))
            ->setParameter('isSubscribable', true)
            ->setParameter('subscriberRole',"%".$subscriberRole."%");

        return $queryBuilder->getQuery()
            ->getResult();
    }

    /**
     * Find all NotificationKey objects that are subscribable and have
     * the subscriberRoles $subscriberRoles
     *
     * @param array $subscriberRoles
     * @throws \InvalidArgumentException
     * @return NotificationKeyInterface[]
     */
    public function findBySubscriberRoles(array $subscriberRoles = array())
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('nk');

        $queryBuilder
            ->select(array('nk'));

        $i = 0;
        foreach ($subscriberRoles as $subscriberRole){
            $identifier = ':subscriberRole'.$i;
            $queryBuilder->orWhere($queryBuilder->expr()->like('nk.subscriberRoles', $identifier))
               ->setParameter($identifier,"%".$subscriberRole."%");
            $i++;
        }

        $queryBuilder
            ->andWhere('nk.isSubscribable = :isSubscribable')
            ->setParameter('isSubscribable', true);

        return $queryBuilder->getQuery()
            ->getResult();
    }

}
