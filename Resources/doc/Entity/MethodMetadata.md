```php
<?php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Oz\NotificationBundle\Entity\MethodMetadata as BaseMethodMetadata;
use Acme\NotificationBundle\Entity\Method;
use Acme\NotificationBundle\Entity\NotificationKey;

/**
 * @ORM\Entity
 * @ORM\Table(name="method__notification_key")
 */
class MethodMetadata extends BaseMethodMetadata
{

    /**
     * @ORM\ManyToOne(targetEntity="Method", inversedBy="methodMetadata")
     * @ORM\JoinColumn(name="method_id", referencedColumnName="id")
     * */
    protected $method;

    /**
     * @ORM\ManyToOne(targetEntity="NotificationKey", inversedBy="methodMetadata")
     * @ORM\JoinColumn(name="notification_key_id", referencedColumnName="id")
     * */
    protected $notificationKey;

}
```
