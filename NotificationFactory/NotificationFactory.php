<?php

namespace merk\NotificationBundle\NotificationFactory;

use merk\NotificationBundle\NotificationFactory\NotificationFactoryInterface;
use \merk\NotificationBundle\Renderer\RendererInterface;
use merk\NotificationBundle\Model\NotificationInterface;

class NotificationFactory implements NotificationFactoryInterface
{

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $class;

    public function __construct(RendererInterface $renderer){

        $this->renderer = $renderer;

    }

    /**
     * {@inheritDoc}
     */
    public function setClass($class){

        $this->class = $class;
    }


    /**
     * {@inheritDoc}
     */
    public function build($class)
    {
        $notification = new $class;
        if (!$notification instanceof NotificationInterface){
            throw new \Exception('Should be an instance of NotificationInterface');
        }

        return $notification;
    }


    /**
     * {@inheritDoc}
     */
    public function createNotificationFromFilter($event, $filter){

        $notification = $this->build($this->class);

        $notification->setEvent($event);

        $notification->setUser($filter->getUserPreferences()->getUser());
        $notification->setRecipientName($filter->getRecipientName());
        $notification->setRecipientData($filter->getRecipientData());

        $template = $this->renderer
            ->render($notification);

        $notification->setSubject($template['subject']);

        $notification->setMessage($template['body']);

        return $notification;

    }


    /**
     * {@inheritDoc}
     */
    public function createNotificationFromUser($event, $user){

        $notification = $this->build($this->class);

        $notification->setEvent($event);

        $notification->setUser($user);
        $notification->setRecipientName($user->getUsername());
        $notification->setRecipientData($user->getEmail());

        $template = $this->renderer->render($notification);
        $notification->setSubject($template['subject']);
        $notification->setMessage($template['body']);

        return $notification;

    }

}