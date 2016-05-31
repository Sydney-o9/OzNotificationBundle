OzNotificationBundle - Installation
===================================

### Step 1: Download OzNotificationBundle using composer

```
$ composer require oz/notification-bundle "dev-master"
```

### Step 2: Create your own bundle in your src/ directory

````
php app/console generate:bundle --namespace=Acme/NotificationBundle
````

This bundle will contain all classes to use for your notifications.


### Step 3: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Oz\NotificationBundle\OzNotificationBundle(),
    );
}
```

### Step 4: Create the needed classes in your Application

OzNotificationBundle provides multiple abstract classes that need to be
implemented. Create the following classes in your own notification bundle.


The basic configuration expects the following 3 entities:

- Create [Notification Entity](Entity/Notification.md).
- Create [EmailNotification Entity](Entity/EmailNotification.md).
- Create [InternalNotification Entity](Entity/InternalNotification.md).

Then, add the following entities to your own notification bundle:
- Create [NotificationEvent Entity](Entity/NotificationEvent.md).
- Create [NotificationKey Entity](Entity/NotificationKey.md).
- Create [Method Entity](Entity/Method.md).
- Create [MethodMetadata Entity](Entity/MethodMetadata.md).
- Create [UserPreferences Entity](Entity/UserPreferences.md).
- Create [Filter Entity](Entity/Filter.md).

### Step 5: Mapping to your user entity

Add the relation to your User entity:

```php
    /**
     * @ORM\OneToOne(targetEntity="Acme\NotificationBundle\Entity\UserPreferences", mappedBy="user")
     * @var \Acme\NotificationBundle\Entity\UserPreferences
     */
    protected $userPreferences;

    /**
     * @param UserPreferencesInterface $userPreferences
     */
    public function setUserPreferences(UserPreferencesInterface $userPreferences)
    {
        $this->userPreferences = $userPreferences;
    }

    /**
     * @return \Acme\NotificationBundle\Entity\UserPreferences
     */
    public function getUserPreferences()
    {
        return $this->userPreferences;
    }
```

### Step 6: Configure the OzNotificationBundle

``` yaml
oz_notification:

    #DATABASE DRIVER
    db_driver: orm

    #FQCN OF THE CLASSES
    class:
        user:  Acme\UserBundle\Entity\User
        notification_key: Acme\NotificationBundle\Entity\NotificationKey
        notification_event:  Acme\NotificationBundle\Entity\NotificationEvent
        notification:  Acme\NotificationBundle\Entity\Notification
        user_preferences:  Acme\NotificationBundle\Entity\UserPreferences
        filter: Acme\NotificationBundle\Entity\Filter
        method: Acme\NotificationBundle\Entity\Method

    #NOTIFICATION TYPES
    notification_types:
        email:
            entity: Acme\NotificationBundle\Entity\EmailNotification
            renderer: oz_notification.renderer.email
            notification_factory: oz_notification.notification.factory.email
            sender_agent: oz_notification.sender.agent.email
        sms:
            entity: Acme\NotificationBundle\Entity\SMSNotification
            renderer: oz_notification.renderer.sms
            notification_factory: oz_notification.notification.factory.sms
            sender_agent: oz_notification.sender.agent.sms
        internal:
            entity: Acme\NotificationBundle\Entity\InternalNotification
            notification_factory: oz_notification.notification.factory.internal
            renderer:  oz_notification.renderer.internal
            sender_agent: oz_notification.sender.agent.internal

```

For each notification type that you create, you will have to:
- make sure getType() in the entity returns the alias. (e.g EmailNotification getType() should return 'email').
- make sure you have created the according method in your Method table (e.g create a row 'email' in the Method table via your backend)

If you need to override other parts of the bundle, see [full configuration of the bundle](FullConfiguration.md).

### Step 7: Import OzNotificationBundle routing files

OzNotificationBundle provides routing for a default UserPreferences controller and a default User Notifications controller.

In YAML:

``` yaml
# app/config/routing.yml

# OzNotificationBundle
oz_notification_preferences:
    resource: "@OzNotificationBundle/Resources/config/routing/user_preferences.xml"
    prefix: /notifications/preferences

oz_notification_notifications:
    resource: "@OzNotificationBundle/Resources/config/routing/user_notifications.xml"
    prefix: /notifications
```

### Step 8: Update your database schema

Now that the bundle is configured, the last thing you need to do is update your
database schema.

For ORM run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```

### Step 9: Overwrite templates for specific events

The first line of the rendered output is used as the subject (when used by the
sending agent), with the rest of the output being used in the notification body.

The rendering of each notification follows a specific path while trying to find
which template to use.

The logic used to find the template follows the path outlined below:

```
    some.event.key => some.event.key.email.txt.twig
                   => some.event.email.txt.twig
                   => some.email.txt.twig
                   => base.email.txt.twig
```

The templates should be placed in `app/Resources/merkNotificationBundle/views/Notifications`
and should extend the base template, though they dont have to. There is one variable passed
into the template, `notification` which contains the individual notification sent for a
single user.

The template is rendered separately for each user to be notified for each notification type.


### Behind the scenes

If you're interested to see how entities work behind the scenes: [View more about entities](Entity-More.md)


### Send SMS

- Create [SMSNotification Entity](Entity/SMSNotification.md).
