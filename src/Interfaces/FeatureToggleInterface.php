<?php

namespace FeatureToggle\Interfaces;

/**
 * Interface FeatureToggleInterface
 * @package Castaway\FeatureToggle
 */
interface FeatureToggleInterface
{
    /**
     * @return string
     */
    public function getFeature();

    /**
     * @return boolean
     */
    public function isEnabled();

    /**
     * @param $enabled boolean
     */
    public function setEnabled($enabled);

    /**
     * @param UserPreferenceInterface $user
     */
    public function setUpdatedBy(UserPreferenceInterface $user);
}
