<?php

namespace merk\NotificationBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpKernel\Log\LoggerInterface;


class SMSConsumer implements ConsumerInterface
{

    /**
     * @var LoggerInterface $logger
     */
    private $logger;


    public function __construct(LoggerInterface $logger){

        $this->logger = $logger;
    }


    public function execute(AMQPMessage $msg)
    {

        $this->logger->info('We are just about to send an SMS.');

        echo "SMS";

        //Process notification.
        //$msg will be an instance of `PhpAmqpLib\Message\AMQPMessage` with the $msg->body being the data sent over RabbitMQ.

//        $isUploadSuccess = someUploadPictureMethod();
//        if (!$isUploadSuccess) {
        // If your image upload failed due to a temporary error you can return false
        // from your callback so the message will be rejected by the consumer and
        // requeued by RabbitMQ.
        // Any other value not equal to false will acknowledge the message and remove it
        // from the queue
//            return false;
//        }
    }
}