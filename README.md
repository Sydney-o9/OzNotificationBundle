OzNotificationBundle
====================

## Description

OzNotificationBundle provides notification services for your application. You are able to trigger specific events that will occur in your program that users are able to subscribe to, and are then notified when they occur.


## Features

Implementation of this bundle allows you to:

- send internal notifications (inside your app) and customise them as you wish
- send email notifications and customise them as you wish
- let users choose what notifications they want to subscribe
- let users what their preferred method of communication is.
- You can plug it onto [RabbitMQ](http://www.rabbitmq.com/) / [Rabbit MQ Bundle](https://github.com/php-amqplib/RabbitMqBundle) to send your notifications asynchronously.
- You can plug it onto [RMSPushNotificationsBundle](https://github.com/richsage/RMSPushNotificationsBundle) to send push notifications/messages for mobile devices.
- You can plug it onto anything that sends stuff really
- [View Full list of features](Resources/doc/Features.md).

## Install and use OzNotificationBundle

The bundle is not stable enough to be used yet.

- [Installation](Resources/doc/Installation.md)

- [Full Configuration](Resources/doc/FullConfiguration.md)

- [Contribute to the bundle](Resources/doc/Contribute.md)


## Basic Usage

OzNotificationBundle will send notifications when they are fired by your code.

Here is an example to send an email as well as an internal notification (inside your app) to `Roger`:

``` php
<?php

    $actor = // get Patrick
    $subject = // get 

    $this->container->get('oz_notification.notifier')->trigger('event.key', $subject, 'viewed', $actor);
```

# Origin

OzNotificationBundle is originally a fork from the amazing [merkNotificationBundle](https://github.com/merk/merkNotificationBundle/), initiated by [merk](https://github.com/merk).
This bundle intend to try to follow up on the great work done by Merk. See [here](https://github.com/merk/merkNotificationBundle/issues/13) for more infos.

