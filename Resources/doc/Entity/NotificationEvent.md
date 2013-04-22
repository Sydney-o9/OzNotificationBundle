```php
<?php

namespace Acme\NotificationBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping AS ORM;
use DateTime;
use Oz\NotificationBundle\Entity\NotificationEvent as BaseNotificationEvent;
use Acme\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification_event")
 * @ORM\HasLifecycleCallbacks
 */
class NotificationEvent extends BaseNotificationEvent
{
    /**
     * @ORM\ManyToOne(targetEntity="Acme\UserBundle\Entity\User")
     * @var \Acme\UserBundle\Entity\User
     */
    protected $actor;

    /**
     * @ORM\OneToMany(targetEntity="Acme\NotificationBundle\Entity\Notification", mappedBy="event")
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $notifications;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\NotificationBundle\Entity\NotificationKey")
     * @ORM\JoinColumn(name="notification_key_id", referencedColumnName="id")
     * @var \Acme\NotificationBundle\Entity\NotificationKey
     */
    protected $notificationKey;

    public function __construct(NotificationKey $key = null, $subject, $verb, UserInterface $actor = null, DateTime $createdAt = null)
    {
        parent::__construct($key, $subject, $verb, $actor, $createdAt);

        $this->notifications = new ArrayCollection;
    }

    /**
     * Sets the actor for the event.
     *
     * @param null|\Symfony\Component\Security\Core\User\UserInterface $actor
     * @throws \InvalidArgumentException when the $actor is not a User object
     */
    protected function setActor(UserInterface $actor = null)
    {
        if (null !== $actor and !$actor instanceof User) {
            throw new \InvalidArgumentException('Actor must be a User');
        }

        $this->actor = $actor;
    }

    /**
     * Returns notifications associated to an event
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}
```
