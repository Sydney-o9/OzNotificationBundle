```php
<?php

namespace Acme\NotificationBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Acme\NotificationBundle\Entity\Notification as BaseNotification;
use Symfony\Component\Validator\Constraints as Assert;
use Oz\NotificationBundle\Entity\EmailNotificationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="email_notification")
 */
class EmailNotification extends BaseNotification implements EmailNotificationInterface
{

    /**
     * The text part of the message.
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @var string
     */
    protected $bodyText;

    /**
     * The HTML part of the message.
     *
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    protected $bodyHtml;

    /**
     * Name of the receiver
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    protected $recipientName;

    /**
     * Email of the receiver
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    protected $recipientEmail;

    /**
     * @return string
     */
    public function getBodyText()
    {
        return $this->bodyText;
    }

    /**
     * @param string $bodyText
     */
    public function setBodyText($bodyText)
    {
        $this->bodyText = $bodyText;
    }

    /**
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * @param string $bodyHtml
     */
    public function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;
    }

    /**
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * @param string $recipientName
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }


    /**
     * Returns the email of the recipient used for the
     * notification.
     *
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }

    /**
     *
     * @param string $recipientEmail
     */
    public function setRecipientEmail($recipientEmail)
    {
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * This needs to return the same alias
     * as the one set in the config file
     *
     * @return string
     */
    public function getType()
    {
        return 'email';
    }
}
```
