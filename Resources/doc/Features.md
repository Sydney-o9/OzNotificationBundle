OzNotificationBundle - Features
==============================

As an ADMIN, you can:
----------------------

- Create the notification keys a user of your app is able to subscribe to.
  (e.g newsletter.of.the.week, ordered.processed, ordered.cancelled, etc..)

- Choose the methods a user of your app is able to use for each notification key.
  (e.g email notification, internal notification, sms notification). 

- Choose the roles the user needs to have to receive a particular notification.
  (e.g you might want your admin people with [ROLE_ADMIN] to receive notifications in
  a different way to your normal users with [ROLE_USER])

- Create a new method of notification from the config file if you wish to:
  (Allowing you to create multiple email notif types if you want, or create your own
  sms notification system)

````yml
notification_types:
    sms:
        entity: Acme\NotificationBundle\Entity\SMSNotification
        renderer: oz_notification.renderer.sms
        notification_factory: oz_notification.notification.factory.sms
        sender_agent: oz_notification.sender.agent.sms
````

- Decide whether you would like to use a queuing system (e.g RABBIT MQ) or not.

- Send notifications from the backend of your application to a particular user.

- Send notifications to many users at a time (Bulk notifications)
  (e.g Send this notification to all users that subscribed to newsletter.of.the.week)

As a USER of the application, you can:
--------------------------------------

- Manage all your notifications in your preferences
  (e.g decide whether you would like to receive an email, an internal notification or
  nothing at all for a particular notification, like a newsletter, or an event order.processed)

- View your internal notifications

- View number of unread notifications
