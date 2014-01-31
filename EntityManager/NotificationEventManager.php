<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney_o9 <https://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Oz\NotificationBundle\Exception\NoSubjectLoadedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Oz\NotificationBundle\Model\NotificationEventInterface;
use Oz\NotificationBundle\Entity\NotificationEvent;
use Oz\NotificationBundle\Model\NotificationKeyInterface;
use Oz\NotificationBundle\ModelManager\NotificationEventManagerInterface;

/**
 * Manage notification events.
 */
class NotificationEventManager implements NotificationEventManagerInterface
{

    /**
     * @var EntityManager.
     */
    protected $em;

    /**
     * @var EntityRepository of the NotificationEvent class.
     */
    protected $repository;

    /**
     * @var string NotificationEvent entity name.
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
     * {@inheritDoc}
     */
    public function create(NotificationKeyInterface $notificationKey, $subject, UserInterface $actor = null)
    {
        $class = $this->class;

        return new $class($notificationKey, $subject, $actor);
    }

    /**
     * @param $id
     * @throws \Exception
     * @return notificationEvent
     */
    public function find($id)
    {
        $notificationEvent =  $this->repository->find($id);

        if(!$notificationEvent) {
            throw new \Exception('Unable to find Notification Event');
        } else {
            return $notificationEvent;
        }

    }

    /**
     * Persists and flushes the event to the persistent storage.
     *
     * @param \Oz\NotificationBundle\Model\NotificationEventInterface $event
     * @param bool $flush
     */
    public function update(NotificationEventInterface $event, $flush = true)
    {
        $this->em->persist($event);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Converts the subject object into a persistable string
     *
     * @param NotificationEvent $event
     */
    public function updateSubject(NotificationEvent $event)
    {
        $subject = $event->getSubject();

        if (null === $subject) {
            $event->setSubjectIdentifiers(null);

            return;
        }

        $metadata = $this->em->getClassMetadata(get_class($subject));

        $event->setSubjectIdentifiers($metadata->getIdentifierValues($subject));
    }

    /**
     * Converts the persisted subject string into a reference
     * object, or queries for the appropriate object.
     *
     * @param NotificationEvent $event
     * @param bool $reference
     * @return null
     */
    public function replaceSubject(NotificationEvent $event)
    {
        try {
            $subject = $this->em->find(
                $event->getSubjectClass(),
                $event->getSubjectIdentifiers()
            );
            $event->setSubject($subject);

        } catch (\Exception $e) {
            throw new NoSubjectLoadedException($event);
        }
   }

    /**
     * Check whether the subject exists in database or not
     *
     * TODO: Use the identifier properly (using id field only at the moment)
     *
     * @param NotificationEvent $event
     * @return Bool Whether the subject was found or not
     */
    public function subjectExists(NotificationEvent $event)
    {

        $subjectClass = $event->getNotificationKey()
            ->getSubjectClass();

        $subjectIdentifiers = $event
            ->getSubjectIdentifiers();

        reset($subjectIdentifiers);
        $identifier = key($subjectIdentifiers);
        $identifierValue = $subjectIdentifiers[$identifier];

        $queryBuilder = $this->em
            ->createQueryBuilder();

        $queryBuilder
            ->select('COUNT(s.id) as cnt')
            ->from($subjectClass, 's')
            ->andWhere('s.id = :identifier_value')
            ->setParameter('identifier_value',$identifierValue);

        $count = $queryBuilder
            ->getQuery()
            ->execute(array(), (Query::HYDRATE_SINGLE_SCALAR));

        return (bool)$count >0;
   }


}
