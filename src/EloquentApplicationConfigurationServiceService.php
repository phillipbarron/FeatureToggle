<?php

namespace Castaway\FeatureToggle;

use App\FeatureToggle;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class EloquentApplicationConfigurationService
 * @package Castaway\FeatureToggle
 */
class EloquentApplicationConfigurationServiceService implements ApplicationConfigurationServiceInterface
{
    /**
     * @param $feature
     * @return bool
     */
    public function isEnabledForApplication($feature)
    {
        try {
            /** @var FeatureToggle $feature */
            $feature = FeatureToggle::where(FeatureToggle::FEATURE, $feature)->firstOrFail();

            return (bool)$feature->isEnabled();
        } catch (ModelNotFoundException $ex) {
            return false;
        }
    }

    /**
     * @param UserPreferenceInterface $user
     * @param $feature
     * @param $enabled
     */
    public function setApplicationToggle(UserPreferenceInterface $user, $feature, $enabled)
    {
        try {
            /** @var FeatureToggle $feature */
            $feature = FeatureToggle::where(FeatureToggle::FEATURE, $feature)->firstOrFail();
            $feature->setEnabled($enabled);
            $feature->setUpdatedBy($user);
            $feature->save();
        } catch (ModelNotFoundException $ex) {
            $newFeature = new FeatureToggle(
                [
                    FeatureToggle::FEATURE    => $feature,
                    FeatureToggle::ENABLED    => $enabled,
                    FeatureToggle::UPDATED_BY => $user->getEmail()
                ]
            );

            $newFeature->save();
        }
    }

    /**
     * @return array
     */
    public function getToggles()
    {
        return FeatureToggle::all();
    }

    /**
     * @param $item
     */
    public function deleteApplicationToggle($item)
    {
        $featureToggle = FeatureToggle::where(FeatureToggle::FEATURE, $item)->firstOrFail();
        $featureToggle->delete();
    }
}
