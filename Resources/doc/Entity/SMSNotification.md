```php
<?php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Acme\NotificationBundle\Entity\Notification as BaseNotification;
use Symfony\Component\Validator\Constraints as Assert;
use Oz\NotificationBundle\Entity\SMSNotificationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sms_notification")
 */
class SMSNotification extends BaseNotification implements SMSNotificationInterface
{
    /**
     * Phone number of the receiver
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    protected $phoneNumber;

    /**
     * Name of the receiver
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    protected $recipientName;

    /**
     * Message of the notification
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    protected $message;

    /**
     * @param string $recipientName
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    /**
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
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

    public function getType()
    {
        return 'sms';
    }
}
```
