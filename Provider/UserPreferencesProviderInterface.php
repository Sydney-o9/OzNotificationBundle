<?php

namespace Oz\NotificationBundle\Provider;

/**
 * Provides notification preferences for the current authenticated user
 *
 */
interface UserPreferencesProviderInterface
{
    /**
     * Returns the user preferences object for the authenticated user
     *
     * @param null|\Symfony\Component\Security\Core\User\UserInterface $user
     * @return \Oz\NotificationBundle\Model\UserPreferencesInterface
     */
    function getUserPreferences();

}
