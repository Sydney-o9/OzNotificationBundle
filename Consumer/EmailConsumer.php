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
        /**  1- Decode message */
        try{
            $message = unserialize($msg->body);
            $class = $message['class'];
            $id = $message['id'];
        }catch(\Exception $e){
            $this->logger->err('Message: '.$e->getMessage());
            /**  If we can't unserialize the message, resending the message won't change anything. */
            return true;
        }

        /**  2- Fetch notification */
        try{
            $notification = $this->em->find($class, $id);
        }
        //--->not found exception: there is no point sending a notification if the $subject is not found.
        catch(\Doctrine\ORM\NoResultException $e){
            $this->logger->info('Object of id'.$id.'and class'.$class.'was not found.');
            return true;
        }
        //If we can't retrieve the message from the database, it depends on the error
        //--->database connection: we can do something about that
        catch(\Doctrine\ORM\ORMException $e){
            $this->logger->err('Message: '.$e->getMessage());
            return false;
        }

        /**  3- Send notification - use timer to evaluate performance*/
        try{
            $time_start = microtime(true);
            $this->send($notification);
            //Temporary echo in the console to view if the email has been sent.
            echo "Email";
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            $this->logger->info("Email to ".$notification->getRecipientData()." in ".$time." seconds.");
        }catch(\Exception $e){
            $this->logger->err('Message: '.$e->getMessage());
            //If we can't send the message, we send the message back in the queue for later processing
            return false;
        }

        /**  4- Update notification */
        try{
            $notification->markSent();
            $this->em->flush();
            return true;
        }catch(\Exception $e){
            $this->logger->err('Message: '.$e->getMessage());
            //We don't want to resend the email
            return true;
        }


    }



    /**
     * Sends a single notification.
     *
     * @param \merk\NotificationBundle\Model\NotificationInterface $notification
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
//        $message->addPart($notification->getMessage(), 'text/plain', 'UTF8');
        $message->addPart($notification->getMessage(), 'text/html');

        $message->addTo($notification->getRecipientData(), $notification->getRecipientName());
        $message->setFrom('test@test.com', 'Test Sending');

        $this->mailer->send($message);
        $this->mailer->getTransport()->stop();


    }


}