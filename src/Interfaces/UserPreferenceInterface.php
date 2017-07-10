<?php

namespace FeatureToggle\Interfaces;

/**
 * Interface UserPreferenceInterface
 * @package Castaway\FeatureToggle
 */
interface UserPreferenceInterface
{
    /**
     * @return string
     */
    public function getEmail();
    public function deleteUserPreference($preference);

}
