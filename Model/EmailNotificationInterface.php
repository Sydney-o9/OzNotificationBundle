<?php

namespace Oz\NotificationBundle\Model;

/**
 * Email notification interface
 */
interface EmailNotificationInterface
{

    /**
     * @return string
     */
    public function getBodyText();

    /**
     * @param string $bodyText
     */
    public function setBodyText($bodyText);

    /**
     * @return string
     */
    public function getBodyHtml();

    /**
     * @param string $bodyHtml
     */
    public function setBodyHtml($bodyHtml);

    /**
     * @return string
     */
    public function getRecipientName();

    /**
     * @param string $recipientName
     */
    public function setRecipientName($recipientName);


    /**
     * Returns the email of the recipient used for the
     * notification.
     *
     * @return string
     */
    public function getRecipientEmail();

    /**
     *
     * @param string $recipientEmail
     */
    public function setRecipientEmail($recipientEmail);

    /**
     * This needs to return the same alias
     * as the one set in the config file
     *
     * @return string
     */
    public function getType();



}
