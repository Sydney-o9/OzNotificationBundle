<?php

namespace Oz\NotificationBundle\NotificationFactory;


class InternalNotificationFactory extends NotificationFactory
{

    /**
     * {@inheritDoc}
     */
    public function createFromUser($event, $user){

        $notification = $this->build($this->class);

        $notification->setEvent($event);
        $notification->setUser($user);

        $template = $this->renderer->render($notification);
        $notification->setSubject($template['subject']);
        $notification->setMessage($template['message']);

        return array($notification);

    }


}
