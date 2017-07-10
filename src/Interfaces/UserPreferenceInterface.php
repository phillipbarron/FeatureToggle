<?php

namespace FeatureToggle\Interfaces;

/**
 * Interface UserPreferenceInterface
 * @package FeatureToggle\Interfaces
 */
interface UserPreferenceInterface
{
    /**
     * @return string
     */
    public function getEmail();
    public function deleteUserPreference($preference);

}
