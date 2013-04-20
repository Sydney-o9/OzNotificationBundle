<?php

/*
 * This file is part of the OzNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oz\NotificationBundle\Sender\Agent;

use Oz\NotificationBundle\Model\NotificationInterface;


/**
 * An agent that will send notifications through SwiftMailer.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class EmailAgent extends Agent implements AgentInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    /**
     * This is the producer for email notifications
     *
     * @var old_sound_rabbit_mq.notification_email_producer
     */
    private $notificationEmailProducer;

    /**
     * @param \Swift_Mailer $mailer
     * @param $notificationEmailProducer
     */
    public function __construct(\Swift_Mailer $mailer, $notificationEmailProducer)
    {
        $this->mailer = $mailer;

        $this->notificationEmailProducer = $notificationEmailProducer;
    }

    /**
     * {@inheritDoc}
     */
    public function send(NotificationInterface $notification)
    {
        /** @var \Swift_Message $message  */
        $message = $this->mailer->createMessage();
        $message->setSubject($notification->getSubject());
        $message->addPart($notification->getMessage(), 'text/plain', 'UTF8');

        $message->addTo($notification->getRecipientData(), $notification->getRecipientName());
        $message->setFrom('test@test.com', 'Test Sending');

        $this->mailer->send($message);
    }

    /**
     * {@inheritDoc}
     */
    public function sendBulk(array $notifications, $useMessageBroker = true)
    {
        foreach ($notifications as $notification) {

            $message = array(
                //To retrieve the notification in the consumer as
                //serializing the hole entity is a very bad idea
                'class' => get_class($notification),
                'id' => $notification->getId()
            );

            if ($useMessageBroker){
                //Implementation of Message Broker
                $this->notificationEmailProducer->publish(serialize($message));
            }else{
                $this->send($notification);
            }

        }
    }
}