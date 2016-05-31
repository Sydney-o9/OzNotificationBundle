```php
<?php

// src/Acme/NotificationBundle/Entity/Notification.php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Acme\UserBundle\Entity\User;
use Oz\NotificationBundle\Entity\Notification as BaseNotification;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"internal" = "InternalNotification","email" = "EmailNotification"})
 */
abstract class Notification extends BaseNotification
{
    /**
     * @ORM\ManyToOne(targetEntity="Acme\UserBundle\Entity\User")
     * @var \Acme\UserBundle\Entity\User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\NotificationBundle\Entity\NotificationEvent", inversedBy="notifications")
     * @var NotificationEvent
     */
    protected $event;

}
```
