OzNotificationBundle
====================

## Description:

OzNotificationBundle provides notification services for your application. You
are able to define specific events that will occur in your programs
lifecycle that users will be able to subscribe to, and be notified
when they occur.

[See an exhaustive list of OzNotificationBundle features](Resources/doc/Features.md).

### Note:

OzNotificationBundle is originally a fork from the amazing [merkNotificationBundle](https://github.com/merk/merkNotificationBundle/), initiated by [merk](https://github.com/merk).
See [here](https://github.com/merk/merkNotificationBundle/issues/13) for more infos.

## Install and Use OzNotificationBundle

The bundle is not stable enough to be used yet.

- [Installation](Resources/doc/Installation.md)
- [Full Configuration](Resources/doc/FullConfiguration.md)
- [Contribute to the bundle](Resources/doc/Contribute.md)

## Prerequisites

### Swift Mailer

Notification bundle is capable of sending email notifications and requires Swift Mailer to be set up appropriately.

### Rabbit MQ

Notificationzbundle is capable of using RabbitMq as an AMQP,
so it is recommended to set up [RabbitMqBundle](https://github.com/videlalvaro/rabbitmqbundle) appropriately.

## Basic Usage

OzNotificationBundle will send notifications when they are fired by your code.

To fire an event:

``` php
<?php

    // $actor -- UserInterface object
    // $subject -- An object managed by the Doctrine ORM

    $this->container->get('oz_notification.notifier')->trigger('event.key', $subject, 'viewed', $actor);
```


