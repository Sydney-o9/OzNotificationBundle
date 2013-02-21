<?php

/*
 * This file is part of the merkNotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace merk\NotificationBundle\Sender\Agent;

use merk\NotificationBundle\Model\NotificationInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * A stub class for sending notifications via SMS.
 *
 * TODO: Implementation.. External PHP sms 'library'?
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class SMSAgent extends Agent implements AgentInterface
{

    /**
     * This is the producer for email notifications
     *
     * @var old_sound_rabbit_mq.notification_sms_producer
     */
    private $notificationSMSProducer;

    /**
     * @param $notificationSMSProducer
     */
    public function __construct($notificationSMSProducer)
    {
        $this->notificationSMSProducer = $notificationSMSProducer;
    }

    /**
     * {@inheritDoc}
     */
    public function send(NotificationInterface $notification)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function sendBulk(array $notifications, $useMessageBroker = true)
    {
        foreach ($notifications as $notification) {

            $message = array('class'=> get_class($notification),'id'=> $notification->getId());

            if ($useMessageBroker){
                //Implementation of Message Broker
                $this->notificationSMSProducer->publish(serialize($message));
            }
            else{
                $this->send($notification);
            }

        }

    }

}