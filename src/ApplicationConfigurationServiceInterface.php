<?php

namespace Castaway\FeatureToggle;

/**
 * Interface ApplicationConfigurationServiceInterface
 * @package Castaway\FeatureToggle
 */
interface ApplicationConfigurationServiceInterface
{
    /**
     * @param $feature
     * @return bool
     */
    public function isEnabledForApplication($feature);

    /**
     * @param UserPreferenceInterface $user
     * @param $feature
     * @param $enabled
     * @return mixed
     */
    public function setApplicationToggle(UserPreferenceInterface $user, $feature, $enabled);

    /**
     * @return array
     */
    public function getToggles();

    /**
     * @param $item
     * @return mixed
     */
    public function deleteApplicationToggle($item);
}
