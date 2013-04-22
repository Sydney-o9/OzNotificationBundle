```php
<?php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Oz\NotificationBundle\Entity\Filter as BaseFilter;
use Acme\NotificationBundle\Entity\UserPreferences;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification_userfilter")
 */
class Filter extends BaseFilter
{
    /**
     * @ORM\ManyToOne(targetEntity="Acme\NotificationBundle\Entity\UserPreferences", inversedBy="filters")
     * @var UserPreferences
     */
    protected $userPreferences;


    /**
     * @ORM\ManyToOne(targetEntity="Acme\NotificationBundle\Entity\NotificationKey")
     * @ORM\JoinColumn(name="notification_key_id", referencedColumnName="id")
     * @var \Acme\NotificationBundle\Entity\NotificationKey
     */
    protected $notificationKey;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\NotificationBundle\Entity\Method")
     * @ORM\JoinTable(name="notification_userfilter_methods",
     *      joinColumns={@ORM\JoinColumn(name="filter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="method_id", referencedColumnName="id")}
     *      )
     * @var Method[]
     **/
    protected $methods;
}

```
