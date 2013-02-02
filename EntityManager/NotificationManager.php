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
use Doctrine\Common\Collections\ArrayCollection;
use merk\NotificationBundle\Model\FilterInterface;
use merk\NotificationBundle\Model\NotificationEventInterface;
use merk\NotificationBundle\Model\NotificationInterface;
use merk\NotificationBundle\ModelManager\NotificationManager as BaseNotificationManager;
use merk\NotificationBundle\Renderer\RendererInterface;


/**
 * Doctrine ORM implementation of the NotificationManager class.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class NotificationManager extends BaseNotificationManager
{
    protected $em;
    protected $repository;
    protected $class;
    protected $renderer;

    public function __construct(EntityManager $em, $class, RendererInterface $renderer)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $this->renderer = $renderer;

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
    }

    /**
     * Persists and flushes a notification to persistent storage.
     *
     * @param \merk\NotificationBundle\Model\NotificationInterface $notification
     * @param bool $flush
     */
    public function update(NotificationInterface $notification, $flush = true)
    {
        $this->em->persist($notification);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Persists and flushes multiple notifications to persistent storage.
     *
     * @param array $notifications
     * @param bool $flush
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
     * Create a single notification using the event triggered
     * and a unique filter selected for that event
     *
     * @param NotificationEventInterface $event
     * @param FilterInterface $filter
     * @return NotificationInterface
     */
    public function create(NotificationEventInterface $event, FilterInterface $filter)
    {
        $class = $this->class;

        /** @var NotificationInterface $notification  */
        $notification = new $class;
        $notification->setEvent($event);
        $notification->setMethod($filter->getMethods()->first()->getName());

        $notification->setUser($filter->getUserPreferences()->getUser());
        $notification->setRecipientName($filter->getRecipientName());
        $notification->setRecipientData($filter->getRecipientData());

        $template = $this->renderer->render($notification);
        $notification->setSubject($template['subject']);
        $notification->setMessage($template['body']);

        return $notification;
    }

    /**
     * Create all notifications using the event triggered
     * and the array of filters selected for that event
     *
     * @param NotificationEventInterface $event
     * @param array $filters
     * @return array Notification[]
     */
    public function createForEvent(NotificationEventInterface $event, array $filters)
    {
        $notifications = array();

        /** Iterate through each filter */
        foreach ($filters AS $filter) {


            $methods = $filter->getMethods();
            $iterator = $methods->getIterator();

            /** Iterate through each method to create an individual filter for each method */
            while($method = $iterator->current()){

                $currentMethod = new ArrayCollection();
                $currentMethod->add($method);

                $filter->setMethods($currentMethod);

                $notifications[] = $this->create($event, $filter);

                $iterator->next();

            }

        }

        return $notifications;
    }
}