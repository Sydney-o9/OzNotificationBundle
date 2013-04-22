```php
<?php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Acme\NotificationBundle\Entity\Notification as BaseNotification;
use Symfony\Component\Validator\Constraints as Assert;
use Oz\NotificationBundle\Entity\InternalNotificationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="internal_notification")
 */
class InternalNotification extends BaseNotification implements InternalNotificationInterface
{
    /**
     * Message of the notification
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    protected $message;

    /**
     * Returns
     *   -> true if message is read
     *   -> false otherwise
     *
     * @ORM\Column(name="is_read", type="boolean", nullable=false)
     * @var Bool
     */
    protected $isRead = false;

    /**
     * Set if the notification has been read by the owner
     *
     * @param Bool $isRead
     * @throws \Exception
     * @return void
     */
    public function setIsRead($isRead)
    {
        if (!is_bool($isRead)) {
            throw new \Exception(sprintf('Notification isRead state must be set to a boolean, %s given.', gettype($isRead)));
        }
        $this->isRead = $isRead;
    }

    /**
     * Returns if the notification has been read by the owner
     *
     * @return boolean
     */
    public function isRead()
    {
        return $this->isRead;
    }

    /**
     * Returns the message body of the notification.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getType()
    {
        return 'internal';
    }

}
```
