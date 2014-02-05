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
use Oz\NotificationBundle\Model\FilterInterface;
use Oz\NotificationBundle\Model\NotificationEventInterface;
use Oz\NotificationBundle\Model\NotificationInterface;
use Oz\NotificationBundle\Model\NotifiableInterface;
use Oz\NotificationBundle\ModelManager\NotificationManagerInterface;
use Oz\NotificationBundle\Discriminator\NotificationDiscriminatorInterface;

/**
 * Manages notifications.
 */
class NotificationManager implements NotificationManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var NotificationRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var NotificationDiscriminatorInterface
     */
    protected $notificationDiscriminator;

    public function __construct(EntityManager $em, $class, NotificationDiscriminatorInterface $notificationDiscriminator)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

        $this->notificationDiscriminator = $notificationDiscriminator;

    }

    /**
     * {@inheritDoc}
     */
    public function createForCommittedUser(NotificationEventInterface $event, FilterInterface $filter)
    {
        /** If user hasn't access to that event, no notifications are created */
        if (!$this->CanUserAccessToEvent($event, $filter->getUserPreferences()->getUser())){return array();}

        $notifications = array();

        $votedMethods = $filter->getMethods()->toArray();

        $compulsoryMethods = $event->getNotificationKey()->getCompulsoryMethods()->toArray();

        $allMethods = array_merge($votedMethods,$compulsoryMethods);

        $methods = array();

        foreach($allMethods as $met){
            $methods[] = $met->getName();
        }

        $methods = array_unique($methods);

        $notifications = array();

        /** Iterate through each method and create a notification */
        foreach($methods as $method){

            /** @var NotificationInterface $notification  */
            $notificationFactory = $this->notificationDiscriminator->getNotificationFactory($method);

            $notification = $notificationFactory
                ->createNotificationFromFilter($event, $filter);

            $notifications[] = $notification;

        }

        return $notifications;
    }

    /**
     * {@inheritDoc}
     */
    public function createForCommittedUsers(NotificationEventInterface $event, array $filters)
    {
        $notificationsForEvent = array();

        /** Iterate through each filter */
        foreach ($filters AS $filter) {

            $notifications = $this->createForCommittedUser($event, $filter);

            foreach ($notifications AS $notification){

                $notificationsForEvent[] = $notification;
            }

        }

        return $notificationsForEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function createForUncommittedUser(NotificationEventInterface $event, UserInterface $user)
    {
        /** If user hasn't access to that event, no notifications are created */
        if (!$this->CanUserAccessToEvent($event, $user)){return array();}

        /** Start generating notifications. */
        $defaultMethods = $event->getNotificationKey()->getDefaultMethods()->toArray();

        $compulsoryMethods = $event->getNotificationKey()->getCompulsoryMethods()->toArray();

        $allMethods = array_merge($defaultMethods,$compulsoryMethods);

        $methods = array();

        foreach($allMethods as $met){
            $methods[] = $met->getName();
        }

        $methods = array_unique($methods);

        $notifications = array();

        foreach($methods as $method){

            /** @var NotificationInterface $notification  */
            $notificationFactory = $this->notificationDiscriminator->getNotificationFactory($method);

            $notification = $notificationFactory
                ->createNotificationFromUser($event, $user);

            $notifications[] = $notification;

        }

        return $notifications;
    }


    /**
     * {@inheritDoc}
     */
    public function createForUncommittedUsers(NotificationEventInterface $event, array $users)
    {
        $uncommittedNotifications = array();

        foreach ($users as $user){

            $notifications = $this->createForUncommittedUser($event, $user);

            foreach ($notifications as $notification){

                $uncommittedNotifications[] = $notification;
            }

        }

        return $uncommittedNotifications;
    }


    /**
     * {@inheritDoc}
     */
    public function CanUserAccessToEvent(NotificationEventInterface $event, UserInterface $user){

        /** If notification key is not subscribable, user can not access to that event */
        if (false === $event->getNotificationKey()->isSubscribable() ){
            return false;
        }

        /** If user does not have required roles, user can not access to that event */
        $requiredRoles = $event->getNotificationKey()->getSubscriberRoles();
        $roles = $user->getRoles();

        $canAccess = false;
        foreach ($requiredRoles as $requiredRole){
            if (in_array($requiredRole, $roles)){
                $canAccess = true;
                break;
            }
        }

        return $canAccess;
    }

    /**
     * {@inheritDoc}
     */
    public function update(NotificationInterface $notification, $flush = true)
    {
        $this->em->persist($notification);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function updateBulk(array $notifications, $flush = true)
    {
        foreach ($notifications AS $notification) {

            $this->update($notification, false);
        }

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * @param $id
     * @throws \InvalidArgumentException
     * @return NotificationInterface
     */
    public function find($id)
    {
        $notification =  $this->repository->find($id);

        if(!$notification) {
            throw new \InvalidArgumentException(sprintf('Unable to find Notification "%s" ', $id));
        }

        return $notification;
    }

    /**
     * Remove notification
     *
     * @param NotificationInterface $notification
     */
    public function remove(NotificationInterface $notification)
    {
        $this->em->remove($notification);
        $this->em->flush();
    }

    /**
     * Get Notifications by type
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param string $type
     * @param array $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findNotificationsForUserByType(UserInterface $user, $type, array $order = array("createdAt" => "DESC"),$limit = null, $offset = null)
    {
        $criteria = array(
            "user" => $user->getId()
        );
        return $this->getLocalizedRepository($type)->findBy($criteria, $order, $limit, $offset);
    }

    /**
     * Get all notifications about the subject $subject
     *
     * @param NotifiableInterface $subject
     * @return NotificationInterface[]
     */
    public function findNotificationsWithSubject(NotifiableInterface $subject)
    {

        $identifierValues = $this->em
            ->getClassMetadata(get_class($subject))
            ->getIdentifierValues($subject);

        /** The field name of the subject */
        $subjectIdentifierFieldName = array_keys($identifierValues)[0];

        /** The identifier value of the subject */
        $subjectIdentifierValue = array_values($identifierValues)[0];

        $queryBuilder = $this->repository
            ->createQueryBuilder('n')
            ->select('n')
            ->leftJoin('n.event', 'e')
            ->leftJoin('e.notificationKey', 'nk')

        // the subject has the same class as the notificationKey subjectClass field
            ->andWhere('nk.subjectClass = :subject_class')
            ->setParameter('subject_class', get_class($subject))

        // the subject has the same identifier value as the notificationEvent subjectIdentifier field
            ->andWhere('e.subjectIdentifier = :subject_identifier_value')
            ->setParameter('subject_identifier_value', $subjectIdentifierValue);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    /**
     * Get the number of unread internal notifications
     *
     * @return int
     */
    public function getNbUnreadInternalNotifications($user)
    {
        $queryBuilder = $this->getLocalizedQueryBuilder('internal');

        return (int)$queryBuilder
            ->select($queryBuilder->expr()->count('internal.id'))
            ->innerJoin('internal.user', 'u')

            ->where('u.id = :user_id')
            ->setParameter('user_id',$user->getId())

            ->andWhere('internal.isRead = :isRead')
            ->setParameter('isRead', false, \PDO::PARAM_BOOL)

            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get repository
     *
     * @param string $type
     */
    public function getLocalizedRepository($type){

        $class = $this->notificationDiscriminator->getClass($type);

        $repository = $this->em->getRepository($class);

        return $repository;
    }

    /**
     * Get query builder for child classes
     *
     *  'email'    => EmailQueryBuilder
     *  'sms'      => SMSQueryBuilder
     *  'internal' => InternalQueryBuilder
     *
     * @param string $type
     */
    public function getLocalizedQueryBuilder($type){

        $repository = $this->getLocalizedRepository($type);

        return $repository->createQueryBuilder($type);

    }

    public function markInternalNotificationAsReadByUser(UserInterface $user ){

        $queryBuilder = $this->getLocalizedQueryBuilder('internal');

        $queryBuilder
            ->update()
            ->set("internal.isRead", '?1')
            ->Where("internal.user = {$user->getId()}")
            ->setParameter(1, true);

        $queryBuilder->getQuery()->getResult();
    }

}
