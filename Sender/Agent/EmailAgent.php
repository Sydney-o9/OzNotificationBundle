<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 * (c) Sydney_o9 <https://github.com/Sydney-o9>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Sender\Agent;

use Oz\NotificationBundle\Model\NotificationInterface;

/**
 * An agent that will send notifications through SwiftMailer.
 */
class EmailAgent extends Agent implements AgentInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Constructor
     *
     * @param \Swift_Mailer $mailer
     * @param $notificationEmailProducer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritDoc}
     */
    public function send(NotificationInterface $notification)
    {
        /* @var $mailer \Swift_Mailer */
        if(!$this->mailer->getTransport()->isStarted()){
            $this->mailer->getTransport()->start();
        }

        /* @var $message \Swift_Message */
        $message = $this->mailer->createMessage();
        $message->setSubject($notification->getSubject());

        $message->setBody($notification->getBodyHtml(), 'text/html');
        $message->addPart($notification->getBodyText(), 'text/plain', 'UTF8');

        $message->addTo($notification->getRecipientEmail(), $notification->getRecipientName());
        $message->setFrom( array('whatever@whatever.com' => 'Whatever') );

        $this->mailer->send($message);
        $this->mailer->getTransport()->stop();
    }

    /**
     * {@inheritDoc}
     */
    public function sendBulk(array $notifications)
    {
        foreach ($notifications as $notification) {
            $this->send($notification);
        }

        return;
    }
}
