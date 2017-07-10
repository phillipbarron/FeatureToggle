<?php

namespace FeatureToggle;

use FeatureToggle\Interfaces\ApplicationConfigurationServiceInterface;
use FeatureToggle\Interfaces\FeatureToggleInterface;
use FeatureToggle\Interfaces\UserConfigurationServiceInterface;
use FeatureToggle\Interfaces\UserPreferenceInterface;

/**
 * Class FeatureToggleService
 * @package Castaway\FeatureToggle
 */
class FeatureToggleService
{
    const ENABLED = 'enabled';
    const USER_TOGGLES = 'enabledForUser';
    const APPLICATION_TOGGLES = 'enabledForApplication';

    /**
     * @var UserConfigurationServiceInterface
     */
    private $userConfigurationService;
    /**
     * @var ApplicationConfigurationServiceInterface
     */
    private $applicationConfigurationService;

    /**
     * FeatureToggleService constructor.
     * @param UserConfigurationServiceInterface $userConfiguration
     * @param ApplicationConfigurationServiceInterface $applicationConfiguration
     */
    public function __construct(
        UserConfigurationServiceInterface $userConfiguration,
        ApplicationConfigurationServiceInterface $applicationConfiguration
    ) {
        $this->userConfigurationService = $userConfiguration;
        $this->applicationConfigurationService = $applicationConfiguration;
    }

    /**
     * @param UserPreferenceInterface $user
     * @param $feature
     * @return bool
     */
    public function isEnabled(UserPreferenceInterface $user, $feature)
    {
        return $this->isEnabledForApplication($feature) || $this->isEnabledForUser($user, $feature);
    }

    public function clearCache()
    {

    }

    /**
     * @param UserPreferenceInterface $user
     * @param $feature string
     * @param $enabled boolean
     */
    public function setApplicationToggle(UserPreferenceInterface $user, $feature, $enabled)
    {
        $this->applicationConfigurationService->setApplicationToggle($user, $feature, $enabled);
    }

    /**
     * @param UserPreferenceInterface $user
     * @param $feature
     * @param $state
     */
    public function setUserToggle(UserPreferenceInterface $user, $feature, $state)
    {
        $this->userConfigurationService->setUserToggle($user, $feature, $state);
    }


    /**
     * @param $feature
     * @return bool
     */
    public function isEnabledForApplication($feature)
    {
        return $this->applicationConfigurationService->isEnabledForApplication($feature);
    }

    /**
     * @param UserPreferenceInterface $user
     * @param $feature
     * @return bool
     */
    public function isEnabledForUser(UserPreferenceInterface $user, $feature)
    {
        return $this->userConfigurationService->getUserToggle($user, $feature) === true;
    }

    /**
     *
     * @param UserPreferenceInterface $user
     * @return array
     */
    public function getAllToggles(UserPreferenceInterface $user)
    {
        $formattedFeatureToggles = [];

        /** @var FeatureToggleInterface $toggle */
        foreach ($this->applicationConfigurationService->getToggles() as $toggle) {
            $formattedFeatureToggles[$toggle->getFeature()] = [
                self::ENABLED => ($toggle->isEnabled() || $this->isEnabledForUser($user, $toggle->getFeature())),
                self::APPLICATION_TOGGLES => (bool)$toggle->isEnabled(),
                self::USER_TOGGLES => $this->isEnabledForUser($user, $toggle->getFeature())
            ];
        }

        return $formattedFeatureToggles;
    }

    /**
     * @param $feature
     */
    public function deleteApplicationToggle($feature)
    {
        $this->applicationConfigurationService->deleteApplicationToggle($feature);
    }

    /**
     * @param UserPreferenceInterface $user
     * @param $feature
     */
    public function deleteUserToggle(UserPreferenceInterface $user, $feature)
    {
        $this->userConfigurationService->deleteUserToggle($user, $feature);
    }

    /**n
     * @param UserPreferenceInterface $user
     * @param array $toggles
     */
    public function setToggles(UserPreferenceInterface $user, array $toggles)
    {
        foreach ($toggles as $toggle => $value) {
            $this->setApplicationToggle($user, $toggle, $value[self::APPLICATION_TOGGLES]);
            $this->setUserToggle($user, $toggle, $value[self::USER_TOGGLES]);
        }
    }

}
