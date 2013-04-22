```php
<?php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Oz\NotificationBundle\Entity\UserPreferences as BaseUserPreferences;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification_userprefs")
 */
class UserPreferences extends BaseUserPreferences
{
    /**
     * @ORM\OneToOne(targetEntity="Acme\UserBundle\Entity\User", inversedBy="userPreferences")
     * @var \Acme\UserBundle\Entity\User
     *
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Acme\NotificationBundle\Entity\Filter", mappedBy="userPreferences")
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $filters;
}
```
