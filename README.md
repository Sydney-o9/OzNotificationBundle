OzNotificationBundle
====================

## Description

`OzNotificationBundle` provides notification services for your application. You are able to trigger specific `events` that will occur in your program that users are able to `subscribe` to, and are then notified when they occur.


## Features

Implementation of this bundle allows you to:

- send internal notifications (inside your app) and email notifications

- render your notifications the way you want

- let users choose what notifications they want to subscribe

- let users what their preferred method of communication is.

- plug it onto [RabbitMQ](http://www.rabbitmq.com/) / [Rabbit MQ Bundle](https://github.com/php-amqplib/RabbitMqBundle) to send your notifications asynchronously.

- plug it onto [RMSPushNotificationsBundle](https://github.com/richsage/RMSPushNotificationsBundle) to send push notifications/messages for mobile devices.

- plug it onto anything that sends stuff really

[View Full list of features](Resources/doc/Features.md).

## Install and use OzNotificationBundle

The bundle is now stable enough to be used:

- [Installation](Resources/doc/Installation.md)

- [Full Configuration](Resources/doc/FullConfiguration.md)

## Basic Usage

OzNotificationBundle will send notifications when they are fired by your code.


``` php

<?php

    // Send a notification of type 'event.key' to a $recipient triggered by an $action for a particular $subject
    $this->container->get('oz_notification.notifier')->trigger('event.key', $recipient, $subject, $actor);
```

Here is an example to notify our friend Roger that his friend request has been accepted:

``` php

    /**
     * Notify the user that his friend request has been accepted
     *
     * @param UserInterface $user Recipient of the notification
     * @param FriendRequestInterface $friendRequest This is basically the subject of the notification
     */
    public function notifyUser(UserInterface $user, FriendRequestInterface $friendRequest)
    {

        /** @var string */
        $notificationKey = 'friend.request.accepted';

        /** @var UserInterface */
        $actor = // The user who accepted the connection request

        return $this->container->get('oz_notification.notifier')
            ->trigger($notificationKey, $user, $connectionRequest, $actor);
    }

```

# Contribution

OzNotificationBundle is originally a fork from the amazing [merkNotificationBundle](https://github.com/merk/merkNotificationBundle/), initiated by [merk](https://github.com/merk).
This bundle intend to try to follow up on the great work done by Merk. See [here](https://github.com/merk/merkNotificationBundle/issues/13) for more infos.

Contribution to this bundle is greatly appreciated people!

- [Contribute to the bundle](Resources/doc/Contribute.md)
