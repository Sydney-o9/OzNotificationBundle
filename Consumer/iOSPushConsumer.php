<?php

namespace Oz\NotificationBundle\Consumer;

use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Oz\NotificationBundle\Model\iOSPushNotificationInterface;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use Oz\NotificationBundle\Event\iOSMessageEvent;
use Oz\NotificationBundle\OzNotificationEvents;

class iOSPushConsumer
{

    /**
     * @var LoggerInterface $logger
     */
    private $rmsPushNotifications;

    /**
     * @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * Constructor
     *
     * @param $rmsPushNotifications
     */
    public function __construct($rmsPushNotifications, EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $this->rmsPushNotifications = $rmsPushNotifications;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    /**
     *
     * @param AMQPMessage $message
     */
    public function execute(AMQPMessage $message)
    {

        try{
            $this->logger->info('-> Processing iOS Push Notification.');

            /** Unserialize message */
            $notification = unserialize($message->body);

            /** Build ios push notification */
            $iOSMessage = $this->compose($notification);


            /** Send Push Notification */
            $this->send($iOSMessage);
            $this->logger->info('-> Push Notification sent.');

        } catch( \Exception $e ) {
            $this->logger->err('Cannot send iOS Push Notification');
            $this->logger->err('Message: '.$e->getMessage());

            /**  Don't resend the message back to the queue if we can't unserialize it */
            return true;
        }

    }

    /**
     * Compose iOS Push Notifications
     *
     * @param $message
     * @param $sender
     * @return array of messages
     */
    public function compose(iOSPushNotificationInterface $notification)
    {
        $iOSMessage = new iOSMessage();
        $iOSMessage->setMessage($notification->getMessage());
        $iOSMessage->setDeviceIdentifier($notification->getDeviceToken());

        /** Dispatch event before sending notification */
        if ($this->dispatcher) {
            $event = new iOSMessageEvent($notification, $iOSMessage);
            $this->dispatcher->dispatch(OzNotificationEvents::IOS_PUSH_POST_COMPOSE, $event);
            $iOSMessage = $event->getIOSMessage();
        }

        return $iOSMessage;

    }


    /**
     * {@inheritDoc}
     */
    public function send($iOSMessage)
    {
        return $this->rmsPushNotifications->send($iOSMessage);
    }

}
