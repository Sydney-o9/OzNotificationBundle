<?php

namespace Oz\NotificationBundle\NotificationFactory;

use Oz\NotificationBundle\NotificationFactory\NotificationFactoryInterface;
use \Oz\NotificationBundle\Renderer\RendererInterface;
use Oz\NotificationBundle\Model\NotificationInterface;

class NotificationFactory implements NotificationFactoryInterface
{

    /**
     * The renderer to use for the notification
     *
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * Notification class
     *
     * @var string
     */
    protected $class;


    /**
     * @param \Oz\NotificationBundle\Renderer\RendererInterface $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return \Oz\NotificationBundle\Renderer\RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function setClass($class){

        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
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
    public function createFromFilter($event, $filter){

        $user = $filter->getUserPreferences()->getUser();

        return $this->createFromUser($event, $user);

    }


    /**
     * {@inheritDoc}
     */
    public function createFromUser($event, $user){

        $notification = $this->build($this->class);

        $notification->setEvent($event);

        $notification->setUser($user);

        $template = $this->renderer->render($notification);
        $notification->setSubject($template['subject']);

        return array($notification);

    }

}
