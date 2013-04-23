OzNotificationBundle - Installation
===================================

## Steps

1. Download OzNotificationBundle
2. Configure the Autoloader
3. Enable the Bundle
4. Create the needed classes in your Application
5. Configure the OzNotificationBundle
6. Import OzNotificationBundle routing
7. Update your database schema

### Step 1: Download merkNotificationBundle

Ultimately, the OzNotificationBundle files should be downloaded to the
`vendor/bundles/Oz/NotificationBundle` directory.

This can be done in several ways, depending on your preference. The first
method is the standard Symfony2 method.

**Using composer**

**Using the vendors script**

Add the following lines in your `deps` file:

```
[OzNotificationBundle]
    git=git://github.com/Oz/OzNotificationBundle.git
    target=bundles/Oz/NotificationBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

**Using submodules**

If you prefer instead to use git submodules, the run the following:

``` bash
$ git submodule add git://github.com/Sydney-o9/OzNotificationBundle.git vendor/bundles/Oz/NotificationBundle
$ git submodule update --init
```

### Step 2: Configure the Autoloader

Add the `Oz` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Oz' => __DIR__.'/../vendor/bundles',
));
```

### Step 3: Enable the bundle

Finally, enable the bundle in the kernel:

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

NotificationBundle provides multiple abstract classes that need to be
implemented. At this time, only a Doctrine ORM implementation is
provided.

#### Notification Entity

Represent a notification that is sent to a user. You can then extend this class
to create different notification types. We will show an example with 3 types of notifications inherited from that class:
<pre>
                                      Notification
                                           |
                   ========================|========================
                  |                        |                        |
                  |                        |                        |
           EmailNotification       InternalNotification       SMSNotification   (Add custom notifications if needed)
</pre>


Create a [Notification Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/Notification.md).

You can arrange the discriminator map as you wish, but as you can see, the basic configuration expects 3 entities: EmailNotification, InternalNotification and SMSNotification.

- Create [EmailNotification Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/EmailNotification.md).
- Create [InternalNotification Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/InternalNotification.md).
- Create [SMSNotification Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/SMSNotification.md).

#### NotificationEvent Entity

A NotificationEvent represents an event that occurs in your application. Once triggered, that event will trigger the notifications.
<pre>
                                     NotificationEvent
                                           |
                                           |
                                           |
                                      Notification
                                           |
                  =========================|=========================
                  |                        |                        |
                  |                        |                        |
           EmailNotification       InternalNotification       SMSNotification
</pre>

Create a [NotificationEvent Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/NotificationEvent.md).

#### NotificationKey Entity

A NotificationEvent contains a particular NotificationKey. The NotificationKey identifies the event. It contains a NotificationKey identifier (e.g newsletter.of.the.week, order.processed, order.created...)
as well as relevent information about the NotificationKey (whether the user can subscribe to that NotificationKey or not, etc...).

<pre>
                                     NotificationEvent ======================== NotificationKey
                                           |                     (e.g order.processed, newsletter.of.the.week)
                                           |
                                           |
                                    Notification.php
                                           |
                  =========================|=========================
                  |                        |                        |
                  |                        |                        |
           EmailNotification       InternalNotification       SMSNotification
</pre>

Create [NotificationKey Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/NotificationKey.md).

#### Method Entity

A NotificationKey also contains the methods that can be used. For example, the NotificationKey identified by `newsletter.of.the week` will very likely be tied to the email method.

<pre>
                         NotificationEvent ================ NotificationKey ====================== Method
                               |          (e.g order.processed, newsletter.of.the.week)  (e.g email, sms, internal)
                               |
                               |
                        Notification.php
                               |
      =========================|=========================
      |                        |                        |
      |                        |                        |
EmailNotification       InternalNotification       SMSNotification
</pre>

Create [Method Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/Method.md).

#### MethodNotificationKey Entity

Most of the time, you want to have default methods and compulsory methods for a notificationKey. For example, for a particular NotificationKey, you might want your users to always receive an internal notification (compulsoryMethod) but
choose email, and internal notifications by default (defaultMethod)  To do that, the relation between NotificationKey and Method is OneToMany <---> ManyToOne.

<pre>
                                                                             OneToMany                          ManyToOne
                         NotificationEvent ================ NotificationKey =========== MethodNotificationKey ============ Method
                               |           e.g order.processed, newsletter.of.the.week)                         (e.g email, sms, internal)
                               |
                               |
                        Notification.php
                               |
       ________________________|________________________
      |                        |                        |
      |                        |                        |
EmailNotification       InternalNotification       SMSNotification
</pre>
Create [MethodNotificationKey Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/MethodNotificationKey.md).

#### UserPreferences entity

An object to hold default preferences for each user.

<pre>
            User ================ UserPreferences
                   (e.g notification preferences via filters)
</pre>
Create [UserPreferences Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/UserPreferences.md).

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

#### Filter Entity

The filters allow the users to set their preferences for each NotificationKey.

<pre>
                                                     OneToMany
            User ================ UserPreferences ================== Filter
</pre>

Therefore, each user can have one filter/notificationKey that will save his settings.
For example, if you wish to create an order.processed NotificationKey:

<pre>
NotificationKey | defaultMethods |   compulsoryMethods   |
==========================================================
order.processed |     email      |         internal      |
                |    intenal     |                       |
                |      sms       |                       |

</pre>

The user "Georgio" can now modify his preferences via the filter containing order.processed:

<pre>
                                                             "Georgio"
NotificationKey | defaultMethods |   compulsoryMethods   |     filter   |
=========================================================================
order.processed |     email      |         internal      |    email     |
                |    intenal     |                       |   internal
                |      sms       |                       |
</pre>
Create [Filter Entity](https://github.com/Sydney-o9/OzNotificationBundle/tree/master/Resources/doc/Entity/Filter.md).


### Step 5: Configure the merkNotificationBundle

Notification bundle does not contain much configuration, at the current time
the only configuration is database driver and FQCN of the classes you defined
above.

``` yaml
oz_notification:
    db_driver: orm
    class:
        filter: Company\AppBundle\Entity\Filter
        notification: Company\AppBundle\Entity\Notification
        notification_event: Company\AppBundle\Entity\NotificationEvent
        user_preferences: Company\AppBundle\Entity\UserPreferences
```

### Step 6: Import merkNotificationBundle routing files

NotificationBundle provides routing for a default UserPreferences controller.

In YAML:

``` yaml
# app/config/routing.yml
oz_notification:
    resource: "@merkNotificationBundle/Resources/config/routing/user_preferences.xml"
```

### Step 7: Update your database schema

Now that the bundle is configured, the last thing you need to do is update your
database schema because you have added a new entity, the `User` class which you
created in Step 2.

For ORM run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```

### Step 8: Overwrite templates for specific events

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
