<?php

namespace merk\NotificationBundle\Consumer;

use Doctrine\ORM\EntityManager;
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

    /**
     * @var EntityManager $entityManager
     */
    private $em;


    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger, $entityManager){

        $this->mailer = $mailer;

        $this->logger = $logger;

        $this->em = $entityManager;
    }


    public function execute(AMQPMessage $msg)
    {

        try
        {
            //Time start
            $time_start = microtime(true);

            /**  1- Decode message */
            $message = unserialize($msg->body);
            $class = $message['class'];
            $id = $message['id'];

            /**  2- Fetch notification */
            $notification = $this->em->find($class, $id);

            /**  3- Send email */
            $this->send($notification);

            /**  4- Update notification */
            $notification->markSent();
            $this->em->flush();

            /**  5- Write into log files */
            $this->logger->info('---->Sending an Email......');
            echo "Email";

            //Time end
            $time_end = microtime(true);
            $time = $time_end - $time_start;

            $this->logger->info("Email to ".$notification->getRecipientData()." in ".$time." seconds.");
            $this->logger->info("Notification id ".$id);
            $this->logger->info("Class is ".$class);

            $this->logger->info("Class of subject is ".$notification->getEvent()->getSubjectClass());
            $this->logger->info("Identifiers of subject is ".$notification->getEvent()->getSubjectIdentifiers());

        }
        catch(\Exception $e)
        {

            $this->logger->err('Message: '.$e->getMessage());

        }
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