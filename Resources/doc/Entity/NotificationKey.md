``` php
<?php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Oz\NotificationBundle\Entity\NotificationKey as BaseNotificationKey;


/**
 * @ORM\Entity
 * @ORM\Table(name="notification_key")
 */
class NotificationKey extends BaseNotificationKey
{
    /**
     *
     * @var Array
     * @ORM\OneToMany(targetEntity="MethodNotificationKey", mappedBy="notificationKey", cascade={"all"}, orphanRemoval=true)
     */
    protected $methodNotificationKey;
}
```
