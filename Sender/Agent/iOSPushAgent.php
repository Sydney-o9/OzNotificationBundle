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
 * An agent that will send iOS notifications through RMSPushNotification.
 */
class iOSPushAgent extends Agent implements AgentInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $rmsPushNotifications;

    /**
     * The producer for ios push notifications
     *
     * @var old_sound_rabbit_mq.notification_ios_push_producer
     */
    private $iOSPushNotificationProducer;

    /**
     * Constructor
     */
    public function __construct($rmsPushNotifications, $iOSPushNotificationProducer)
    {
        $this->rmsPushNotifications = $rmsPushNotifications;
        $this->iOSPushNotificationProducer = $iOSPushNotificationProducer;
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
        $message->setFrom( array('info@jobinhood.com' => 'Jobinhood') );

        $this->mailer->send($message);
        $this->mailer->getTransport()->stop();
    }

    /**
     * {@inheritDoc}
     */
    public function sendBulk(array $notifications, $useMessageBroker = true)
    {

        foreach ($notifications as $notification) {

            if (!$useMessageBroker) {
                return $this->send($notification);
            }

            /** Implementation of Message Broker */
            $this->iOSPushNotificationProducer->publish(serialize($notification));
        }
    }
}
