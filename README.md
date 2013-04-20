OzNotificationBundle
====================

### Preliminary Note:

OzNotificationBundle is originally a fork from the amazing [merkNotificationBundle](https://github.com/merk/merkNotificationBundle/), initiated by [merk](https://github.com/merk).
See [here](https://github.com/merk/merkNotificationBundle/issues/13) for more infos.

## Description:

This bundle provides notification services for your application. You
are able to define specific events that will occur in your programs
lifecycle that users will be able to subscribe to, and be notified
when they occur.

Please stop reading here, the README file is not up to date yet.
The bundle is not stable enough to be used yet.

- [Features of the bundle](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Features.md)
- [Installation](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Installation.md)
- [Contribute to the bundle](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Contribute.md)

Please stop reading here, the README file is not up to date yet.

## Basic Usage

Once users have set up their notification preferences and filters,
NotificationBundle will send notifications when they are fired by your code.

To fire an event:

``` php
<?php

    // $actor -- UserInterface object
    // $subject -- An object managed by the Doctrine ORM

    $this->container->get('oz_notification.notifier')->trigger('event.key', $subject, 'viewed', $actor);
```

## Prerequisites

### Swift Mailer

At this point, Notification bundle is only capable of sending email
notifications and requires Swift Mailer to be set up appropriately.

