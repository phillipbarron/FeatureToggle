<?php

namespace FeatureToggle\Interfaces;

/**
 * Interface UserConfigurationServiceInterface
 * @package FeatureToggle\Interfaces
 */
interface UserConfigurationServiceInterface
{
    /**
     * @param UserPreferenceInterface $user
     * @param $feature
     * @param $state
     * @return mixed
     */
    public function setUserToggle(UserPreferenceInterface $user, $feature, $state);

    /**
     * @param UserPreferenceInterface $user
     * @param $item
     * @return mixed
     */
    public function getUserToggle(UserPreferenceInterface $user, $item);

    /**
     * @param UserPreferenceInterface $user
     * @param $item
     * @return mixed
     */
    public function deleteUserToggle(UserPreferenceInterface $user, $item);
}
