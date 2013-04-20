<?php

namespace Oz\NotificationBundle\NotificationFactory;


class SMSNotificationFactory extends NotificationFactory
{
    //Customize when needed
    /**
     * {@inheritDoc}
     */
    public function createNotificationFromFilter($event, $filter){

        $user = $filter->getUserPreferences()->getUser();

        return $this->createNotificationFromUser($event, $user);

    }


    /**
     * {@inheritDoc}
     */
    public function createNotificationFromUser($event, $user){

        $notification = $this->build($this->class);

        $notification->setEvent($event);

        $notification->setUser($user);
        $notification->setPhoneNumber('04 21 211 211');
        $notification->setRecipientName($user->getUsername());

        $template = $this->renderer->render($notification);
        $notification->setSubject($template['subject']);
        $notification->setMessage($template['subject']);

        return $notification;

    }

}