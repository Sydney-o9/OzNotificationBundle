<?php

namespace merk\NotificationBundle\Provider;

/**
 * Provides notifications for the current authenticated user
 *
 */
interface NotificationProviderInterface
{
    /**
     * Gets email notifications of the authenticated user
     *
     * @return array of EmailNotificationInterface
     */
    function getEmailNotifications($limit);

    /**
     * Gets internal notifications of the authenticated user
     *
     * @return array of InternalNotificationInterface
     */
    function getInternalNotifications($limit);

    /**
     * Gets SMS notifications of the authenticated user
     *
     * @return array of SMSNotificationInterface
     */
    function getSMSNotifications($limit);

    /**
     * Tells how many unread notifications the authenticated user has
     *
     * @return int the number of unread notifications
     */
    function getNbUnreadInternalNotifications();

}
