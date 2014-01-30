```php
<?php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Oz\NotificationBundle\Entity\Method as BaseMethod;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification_method")
 */
class Method extends BaseMethod
{
    /**
     * @ORM\OneToMany(targetEntity="MethodMetadata" , mappedBy="method" , cascade={"all"}, orphanRemoval=true)
     */
    protected $methodMetadata;

}
```
