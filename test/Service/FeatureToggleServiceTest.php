<?php

use FeatureToggle\FeatureToggleService;

class FeatureToggleServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    private $userConfigurationService;

    /**
     * @var \Mockery\MockInterface
     */
    private $mockAppConfigService;

    /**
     * @var FeatureToggleService
     */
    private $featureToggleService;

    /**
     * @var \Mockery\MockInterface
     */
    private $mockUser;

    public function setUp()
    {
        $this->userConfigurationService = Mockery::mock('FeatureToggle\Interfaces\UserConfigurationServiceInterface');
        $this->mockAppConfigService     = Mockery::mock('FeatureToggle\Interfaces\ApplicationConfigurationServiceInterface');
        $this->featureToggleService     = new FeatureToggleService($this->userConfigurationService, $this->mockAppConfigService);
        $this->mockUser                 = Mockery::mock('FeatureToggle\Interfaces\UserPreferenceInterface');

    }

    const USER_EMAIL = 'user@domain.com';
    const FEATURE    = "FEATURE";

    /**
     * @param $feature
     * @param $dbEnabled
     * @param $personalisationServiceEnabled
     * @param $expectedState
     * @dataProvider data
     */
    public function testItReturnsExpectedFeatureState($feature, $dbEnabled, $personalisationServiceEnabled, $expectedState)
    {
        $mockUser = Mockery::mock('Castaway\Domain\User');

        $this->mockAppConfigService->shouldReceive('setApplicationToggle')->with($mockUser, $feature, $dbEnabled);
        $this->mockAppConfigService->shouldReceive('isEnabledForApplication')->andReturn($dbEnabled);

        $mockUser->shouldReceive('getEmail')->andReturn(self::USER_EMAIL);
        $this->userConfigurationService->shouldReceive('setUserToggle');
        $this->userConfigurationService->shouldReceive('getUserToggle')->andReturn($personalisationServiceEnabled);

        $this->featureToggleService->setApplicationToggle($mockUser, $feature, $dbEnabled);
        $this->featureToggleService->setUserToggle($mockUser, $feature, $personalisationServiceEnabled);

        $this->assertEquals($expectedState, $this->featureToggleService->isEnabled($mockUser, $feature));

    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            ['foo', false, false, false],
            ['foo', false, true, true],
            ['foo', true, false, true],
            ['foo', true, true, true],
        ];
    }

    public function test_deleteUserToggle_deletes_user_toggle()
    {
        $this->userConfigurationService->shouldReceive('deleteUserToggle')->once()->with($this->mockUser, self::FEATURE);
        $this->featureToggleService->deleteUserToggle($this->mockUser, self::FEATURE);
    }

    public function test_deleteApplicationToggle_deletes_user_toggle()
    {
        $this->mockAppConfigService->shouldReceive('deleteApplicationToggle')->once()->with(self::FEATURE);
        $this->featureToggleService->deleteApplicationToggle(self::FEATURE);
    }

    public function test_isEnabledForApplication_checksAppConfigService()
    {
        $this->mockAppConfigService->shouldReceive('isEnabledForApplication')->once()->with(self::FEATURE);
        $this->featureToggleService->isEnabledForApplication(self::FEATURE);
    }

    public function test_isEnabledForUser_checksUserConfigService()
    {
        $this->userConfigurationService->shouldReceive('getUserToggle')->once()->with($this->mockUser, self::FEATURE);
        $this->featureToggleService->isEnabledForUser($this->mockUser, self::FEATURE);
    }

    /**
     * @param array $toggles
     * @dataProvider getToggles
     */
    public function test_setToggles_sets_toggles_for_both_user_and_app(array $toggles)
    {
        foreach ($toggles as $key => $value) {
            $this->userConfigurationService->shouldReceive('setUserToggle')->once()->with($this->mockUser, $key, $value['enabledForUser']);
            $this->mockUser->shouldReceive('getEmail')->andReturn(self::USER_EMAIL);
            $this->mockAppConfigService->shouldReceive('setApplicationToggle')->once()->with($this->mockUser, $key, $value['enabledForApplication']);;
        }

        $this->featureToggleService->setToggles($this->mockUser, $toggles);
    }

    /**
     * @return array
     */
    public function getToggles()
    {
        return [
            [
                [
                    'ALPHA' => ['enabledForApplication' => true, 'enabledForUser' => false],
                    'BETA'  => ['enabledForApplication' => true, 'enabledForUser' => false]
                ]
            ]
        ];
    }


}
