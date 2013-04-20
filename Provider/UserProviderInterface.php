<?php

namespace Oz\NotificationBundle\Provider;

/**
 * Provides the authenticated participant
 *
 */
interface UserProviderInterface
{
    /**
     * Gets the current authenticated user
     *
     * @return ParticipantInterface
     */
    function getAuthenticatedUser();
}
