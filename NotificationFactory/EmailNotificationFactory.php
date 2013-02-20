<?php

namespace merk\NotificationBundle\NotificationFactory;



class EmailNotificationFactory extends NotificationFactory
{

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

        $notification->setMessage($template['body_html']);

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
        $notification->setMessage($template['body_html']);

        return $notification;

    }


}