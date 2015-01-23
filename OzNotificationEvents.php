<?php

namespace Oz\NotificationBundle;
/**
 * Contains all events thrown in the OzNotificationBundle
 */
final class OzNotificationEvents
{
    /**
     * The IOS_PUSH_NOTIFICATION_POST_COMPOSE event occurs when the ios push notification composition is finished.
     *
     * This event allows you to modify the default values of the ios message before it is sent.
     * The event listener method receives a Oz\NotificationBundle\Event\iOSMessageEvent instance.
     */
    const IOS_PUSH_POST_COMPOSE = 'oz_notification.ios_push.post_compose';
    /**
     * The IOS_PUSH_POST_SEND event occurs after saving the user in the profile edit process.
     *
     * This event allows you to access the response which will be sent.
     * The event listener method receives a Oz\NotificationBundle\Event\iOSMessageEvent instance.
     */
    const IOS_PUSH_POST_SEND = 'oz_notification.ios_push.post_send';
}
