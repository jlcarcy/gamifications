<?php

/**
 * Class GamificationsConfig
 */
class GamificationsConfig
{
    /**
     * Available module configurations
     */
    const CHALLANGES_STATUS = 'GAMIFICATIONS_CHALLANGES_STATUS';
    const CHALLANGES_DISPLAY_REWARDS = 'GAMIFICATIONS_CHALLANGES_DISPLAY_REWARDS';
    const DAILY_REWARDS_STATUS = 'GAMIFICATIONS_DAILY_REWARDS_STATUS';

    const DISPLAY_EXPLANATIONS = 'GAMIFICATIONS_DISPLAY_EXPLANATIONS';
    const FRONT_OFFICE_TITLE = 'GAMIFICATIONS_FRONT_OFFICE_TITLE';

    /**
     * Get default configuration
     *
     * @return array
     */
    public static function getDefaultConfiguration()
    {
        return [
            self::CHALLANGES_STATUS => 1,
            self::CHALLANGES_DISPLAY_REWARDS => 0,
            self::DAILY_REWARDS_STATUS => 1,
            self::DISPLAY_EXPLANATIONS => 1,
            self::FRONT_OFFICE_TITLE => 'Loyality program',
        ];
    }
}