<?php

/**
 * Class GamificationsConfig
 */
class GamificationsConfig
{
    /**
     * Available module configurations
     */
    const REFERRAL_PROGRAM_STATUS = 'GAMIFICATIONS_REFERRAL_STATUS';
    const REFERRAL_REWARD_TIME = 'GAMIFICATIONS_REFERRAL_REWARD_TIME';
    const REFERRAL_REWARD = 'GAMIFICATIONS_REFERRAL_REWARD';
    const REFERRAL_NEW_CUSTOMER_REWARD_ENABLED = 'GAMIFICATIONS_REFERRAL_NEW_CUSTOMER_REWARD_ENABLED';
    const REFERRAL_NEW_CUSTOMER_ORDER_STATES = 'GAMIFICATIONS_REFERRAL_ORDER_STATES';
    const REFERRAL_NEW_CUSTOMER_REWARD = 'GAMIFICATIONS_REFERRAL_NEW_CUSTOMER_REWARD';
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
        $frontOfficeTitle = [];

        foreach (Language::getIDs() as $idLang) {
            $frontOfficeTitle[$idLang] = 'Loyality program';
        }

        return [
            self::DAILY_REWARDS_STATUS => 0,
            self::DISPLAY_EXPLANATIONS => 1,
            self::FRONT_OFFICE_TITLE => $frontOfficeTitle,
            self::REFERRAL_PROGRAM_STATUS => 0,
            self::REFERRAL_REWARD_TIME => GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION,
            self::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED => 0,
            self::REFERRAL_NEW_CUSTOMER_ORDER_STATES => json_encode([]),
        ];
    }
}
