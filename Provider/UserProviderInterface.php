<?php

namespace merk\NotificationBundle\Provider;

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
