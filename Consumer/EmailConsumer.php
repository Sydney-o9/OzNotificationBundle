<?php

namespace Oz\NotificationBundle\Consumer;

use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Oz\NotificationBundle\Model\NotificationInterface;

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
    private $entityManager;

    /**
     * Constructor
     *
     * @param \Swift_Mailer $mailer
     * @param LoggerInterface $logger
     * @param EntityManager $entityManager
     */
    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger,  EntityManager $entityManager)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->entityManager     = $entityManager;
    }

    /**
     *
     * @param AMQPMessage $message
     */
    public function execute(AMQPMessage $message)
    {
        /** 1. Unserialize message */
        try{
            $message = unserialize($message->body);
            $class = $message['class'];
            $id = $message['id'];
        } catch( \Exception $e ) {
            $this->logger->err('Cannot unserialize the message.');
            $this->logger->err('Message: '.$e->getMessage());
            /**  Don't resend the message back to the queue if we can't unserialize it */
            return true;
        }

        /** 2. Find notification */
        try{
            $notification = $this->entityManager
                ->find($class, $id);

            if (!$notification) {
                $this->logger->err('Notification implementing class '.$class.' with id '.$id.' was not found -> The Notification has been removed from the queue.');
                return true;
            }
            if (!$notification->isOngoing() ) {
                $this->logger->err('Notification implementing class '.$class.' with id '.$id.' was not ongoing -> The Notification has been removed from the queue.');
                return true;
            }
            if ($notification->isSent() ) {
                $this->logger->err('Notification implementing class '.$class.' with id '.$id.' was already sent -> The Notification has been removed from the queue.');
                return true;
            }

        } catch(\Doctrine\ORM\NoResultException $e){
            $this->logger->err('Notification implementing class '.$class.' with id '.$id.' was not found -> The Notification has been removed from the queue.');
            /**  Don't resend the message back to the queue if we can't find the notification */
            return true;
        } catch(\Doctrine\ORM\ORMException $e){
            $this->logger->err('Message: '.$e->getMessage());
            /**  Resend the message back to the queue if it's a different error (database connection, etc..) */
            return false;
        }

        /** 3. Send notification - use timer to evaluate performance*/
        try{
            $time_start = microtime(true);
            /** Send Notification */
            $this->send($notification);
            $time = microtime(true) - $time_start;
            $this->logger->info("Notification sent via email to ".$notification->getRecipientEmail()." in ".$time." seconds.");
        }catch(\Exception $e){
            $this->logger->err('Message: '.$e->getMessage());
            /** Resend the message back to the queue if there is something wrong with the mailer */
            return false;
        }

        /** 4. Update notification */
        try{
            $notification->markSent();
            $this->entityManager->flush();
            return true;
        }catch(\Exception $e){
            $this->logger->err('Message: '.$e->getMessage());
            /** We don't want to resend the email */
            return true;
        }
    }

    /**
     * Sends the email
     *
     * @param NotificationInterface $notification
     */
    public function send(NotificationInterface $notification)
    {
        /* @var $mailer \Swift_Mailer */
        if(!$this->mailer->getTransport()->isStarted()){
            $this->mailer->getTransport()->start();
        }

        /** 1- Prepare email */

        /* @var $message \Swift_Message */
        $message = $this->mailer->createMessage();
        $message->setSubject($notification->getSubject());

        $message->setBody($notification->getBodyHtml(), 'text/html');
        $message->addPart($notification->getBodyText(), 'text/plain', 'UTF8');

        $message->addTo($notification->getRecipientEmail(), $notification->getRecipientName());
        $message->setFrom( array('info@jobinhood.com' => 'Jobinhood') );

        /** 2- Close database connection to send email */
        $this->entityManager->getConnection()->close();

        /** 3- Send email */
        $this->mailer->send($message);
        $this->mailer->getTransport()->stop();

        /** 4- Reopen database connection to send email */
        $this->entityManager->getConnection()->connect();
    }

}
