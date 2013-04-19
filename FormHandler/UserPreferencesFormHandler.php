<?php

namespace merk\NotificationBundle\FormHandler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use merk\NotificationBundle\ModelManager\UserPreferencesManagerInterface;

/**
 * Handles user preferences forms, from binding request to updating the user preferences
 *
 */
class UserPreferencesFormHandler
{

    /**
     * The request
     */
    protected $request;

    /**
     * The user preferences manager to update user preferences
     */
    protected $userPreferencesManager;

    public function __construct(Request $request, UserPreferencesManagerInterface $userPreferencesManager)
    {
        $this->request = $request;
        $this->userPreferencesManager = $userPreferencesManager;
    }

    /**
     * Processes the form with the request
     *
     * @param Form $form
     * @return Message|false the sent message if the form is bound and valid, false otherwise
     */
    public function process(Form $form)
    {
        if ('POST' !== $this->request->getMethod()) {
            return false;
        }

        $form->bind($this->request);

        if ($form->isValid()) {
            return $this->processValidForm($form);
        }

        return false;
    }

    /**
     * Processes the valid form, saves the user preferences
     *
     * @param Form
     * @return UserPreferencesInterface the user preferences updated
     */
    public function processValidForm(Form $form)
    {
        $userPreferences = $form->getData();

        $this->userPreferencesManager->update($userPreferences);

        return $userPreferences;
    }

}
