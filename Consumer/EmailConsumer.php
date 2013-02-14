<?php

namespace merk\NotificationBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

use merk\NotificationBundle\Model\NotificationInterface;


class EmailConsumer implements ConsumerInterface
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;


    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger){

        $this->mailer = $mailer;

        $this->logger = $logger;
    }


    public function execute(AMQPMessage $msg)
    {

        $this->logger->info('---->Sending an Email......');

        echo "EmailTo";

        $notification = unserialize($msg->body);
        echo $notification->getRecipientData();
        $this->send($notification);



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



    /**
     * Sends a single notification.
     *
     * @param \merk\NotificationBundle\Model\NotificationInterface $notification
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


}