OzNotificationBundle - Entities
===============================


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


You can arrange the discriminator map as you wish, but as you can see, the basic configuration expects 3 entities: EmailNotification, InternalNotification and SMSNotification.

View example of [Notification Entity](Entity/Notification.md).
View example of [EmailNotification Entity](Entity/EmailNotification.md).
View example of [InternalNotification Entity](Entity/InternalNotification.md).
View example of [SMSNotification Entity](Entity/SMSNotification.md).

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

View example of [NotificationEvent Entity](Entity/NotificationEvent.md).

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

View example of [NotificationKey Entity](Entity/NotificationKey.md).

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

View example of [Method Entity](Entity/Method.md).

#### MethodMetadata Entity

Most of the time, you want to have default methods and compulsory methods for a notificationKey. For example, for a particular NotificationKey, you might want your users to always receive an internal notification (compulsoryMethod) but
choose email, and internal notifications by default (defaultMethod)  To do that, the relation between NotificationKey and Method is OneToMany <---> ManyToOne.

<pre>
                                                                             OneToMany                          ManyToOne
                         NotificationEvent ================ NotificationKey =========== MethodMetadata ============ Method
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

View example of [MethodMetadata Entity](Entity/MethodMetadata.md).

#### UserPreferences entity

An object to hold default preferences for each user.

<pre>
            User ================ UserPreferences
                   (e.g notification preferences via filters)
</pre>

View example of [UserPreferences Entity](Entity/UserPreferences.md).

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
                            |      sms       |                       |              |
</pre>


View example of [Filter Entity](Entity/Filter.md).
