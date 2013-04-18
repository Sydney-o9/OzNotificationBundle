<?php

namespace merk\NotificationBundle\Provider;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Core\User\UserInterface;
use merk\NotificationBundle\Provider\UserProviderInterface;
use merk\NotificationBundle\ModelManager\UserPreferencesManagerInterface;

/**
 * Provides notification preferences for the current authenticated user
 *
 */
class UserPreferencesProvider implements UserPreferencesProviderInterface
{
    /**
     * The UserPreferences manager
     *
     * @var UserPreferencesManagerInterface
     */
    protected $userPreferencesManager;

    /**
     * The user provider instance
     *
     * @var UserProviderInterface
     */
    protected $userProvider;

    public function __construct(UserPreferencesManagerInterface $userPreferencesManager, UserProviderInterface $userProvider)
    {
        $this->userPreferencesManager = $userPreferencesManager;
        $this->userProvider = $userProvider;
    }

    /**
     * Returns the user preferences object for the authenticated user
     *
     * @param null|\Symfony\Component\Security\Core\User\UserInterface $user
     * @return \merk\NotificationBundle\Model\UserPreferencesInterface
     */
    public function getUserPreferences()
    {
        $user = $this->getAuthenticatedUser();

        $userPreferences = $this->userPreferencesManager->getUserPreferences($user);

        return $userPreferences;
    }

    /**
     * Gets the current authenticated user
     *
     * @return UserInterface
     */
    protected function getAuthenticatedUser()
    {
        return $this->userProvider->getAuthenticatedUser();
    }

}
