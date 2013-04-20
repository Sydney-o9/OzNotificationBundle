<?php

namespace Oz\NotificationBundle\FormFactory;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Oz\NotificationBundle\Provider\UserPreferencesProviderInterface;

/**
 * Instanciates user preferences form
 *
 */
class UserPreferencesFormFactory
{

    /**
     * The symfony form factory
     *
     * @param FormFactoryInterface
     */
    protected $formFactory;

    /**
     * The User Preferences form type
     *
     * @param UserPreferencesFormType
     */
    protected $formType;

    /**
     * The name of the form
     *
     * @param string
     */
    protected $formName;

    /**
     * Provides User Preferences of the authenticated user
     *
     * @param UserPreferencesProviderInterface
     */
    protected $userPreferencesProvider;

    /**
     *
     * @param FormFactoryInterface
     * @param UserPreferencesProviderInterface
     */
    public function __construct(FormFactoryInterface $formFactory, AbstractType $formType, $formName, UserPreferencesProviderInterface $userPreferencesProvider) {

        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->userPreferencesProvider = $userPreferencesProvider;
        $this->formName = $formName;
    }

    /**
     * Creates a user preferences form
     *
     * @return Form
     */
    public function create() {

        return $this->formFactory
            ->createNamed($this->formName, $this->formType, $this->getUserPreferences());

    }

    /**
     * Get UserPreferences for the authenticated user
     *
     * @return UserPreferencesInterface
     */
    public function getUserPreferences() {

        return $this->userPreferencesProvider->getUserPreferences();

    }

}
