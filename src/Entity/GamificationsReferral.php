<?php

/**
 * Class GamificationsReferral
 */
class GamificationsReferral extends ObjectModel
{
    /**
     * @var int
     */
    public $id_invited_customer;

    /**
     * @var int
     */
    public $id_referral_customer;

    /**
     * @var int
     */
    public $id_shop;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var string
     */
    public $date_add;

    /**
     * @var string
     */
    public $date_upd;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'gamifications_referral',
        'primary' => 'id_gamifications_referal',
        'fields' => [
            'id_invited_customer' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'id_referral_customer' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'id_shop' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_BOOL, 'required' => false, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDate'],
        ],
    ];
}