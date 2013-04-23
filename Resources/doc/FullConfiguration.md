OzNotificationBundle - Full Configuration
=========================================

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

    #ENTITY MANAGERS
    notification_manager: oz_notification.notification.manager.default
    notification_event_manager: oz_notification.notification_event.manager.default
    notification_key_manager: oz_notification.notification_key.manager.default
    filter_manager: oz_notification.filter.manager.default
    method_manager: oz_notification.method.manager.default

    #PROVIDERS
    user_provider: oz_notification.user.provider.default
    user_preferences_provider: oz_notification.user_preferences.provider.default
    notification_provider: oz_notification.notification.provider.default

    #USER PREFERENCES FORM
    user_preferences_form:
        factory: oz_notification.user_preferences.form.factory.default
        type: oz_notification.user_preferences.form.type.default
        name: user_preferences
        handler: oz_notification.user_preferences.form.handler.default

    #NOTIFICATION TYPES
    #For each notification type that you create, 
    #don't forget to add a getType function in entity that returns the alias;
    #don't forget to create a row in methods with the alias;
    notification_types:
        email:
            entity: Acme\NotificationBundle\Entity\EmailNotification
            renderer: oz_notification.renderer.email #service_name
            notification_factory: oz_notification.notification.factory.email #service_name
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

