<?php

/**
 * Class AdminGamificationsDailyRewardsController
 */
class AdminGamificationsDailyRewardsController extends GamificationsAdminController
{
    /**
     * AdminGamificationsDailyRewardsController constructor.
     */
    public function __construct()
    {
        $this->className = 'GamificationsDailyReward';
        $this->table = GamificationsDailyReward::$definition['table'];
        $this->identifier = GamificationsDailyReward::$definition['primary'];
        Shop::addTableAssociation(GamificationsDailyReward::$definition['table'], ['type' => 'shop']);

        parent::__construct();
    }

    /**
     * Add custom links in page header
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_daily_reward'] = [
                'href' => self::$currentIndex.'&addgamifications_daily_reward&token='.$this->token,
                'desc' => $this->trans('Add new Daily Reward'),
                'icon' => 'process-icon-new',
            ];
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Add addition query to list
     *
     * @return false|string
     */
    public function renderList()
    {
        $this->_select = 'grl.`name`, gr.`reward_type`';

        $this->_join = '
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward` gr
                ON gr.`id_gamifications_reward` = a.`id_reward`
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_lang` grl
                ON grl.`id_gamifications_reward` = gr.`id_gamifications_reward`
                    AND grl.`id_lang` = '.(int)$this->context->language->id.'
            LEFT JOIN `'._DB_PREFIX_.'gamifications_reward_shop` grs
                ON grs.`id_gamifications_reward` = gr.`id_gamifications_reward`
                    AND grs.`id_shop` = '.(int)$this->context->shop->id.'
        ';

        return parent::renderList();
    }

    /**
     * Customize list
     *
     * @param int $idLang
     * @param null $orderBy
     * @param null $orderWay
     * @param int $start
     * @param null $limit
     * @param bool $idLangShop
     */
    public function getList($idLang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $idLangShop = false)
    {
        parent::getList($idLang, $orderBy, $orderWay, $start, $limit, $idLangShop);

        if (empty($this->_list)) {
            return;
        }

        $rewardTypeTranslations = GamificationsReward::getRewardsTranslations();

        foreach ($this->_list as &$listItem) {
            $listItem['reward_type'] = $rewardTypeTranslations[$listItem['reward_type']];
        }
    }

    /**
     * Update status via AJAX
     */
    public function ajaxProcessStatusGamificationsDailyReward()
    {
        $idDailyReward = (int) Tools::getValue('id_gamifications_daily_reward');

        $dailyReward = new GamificationsDailyReward($idDailyReward, null, $this->context->shop->id);
        $dailyReward->active =  !((bool) $dailyReward->active);

        $response = [];
        $response['success'] = false;

        if (!Validate::isLoadedObject($dailyReward) || !$dailyReward->save()) {
            $response['error'] = true;
            $response['text'] = $this->trans('Failed to update');

            die(json_encode($response));
        }

        $response['success'] = true;
        $response['text'] = $this->trans('Successful update');

        die(json_encode($response));
    }

    /**
     * Initialize list
     */
    protected function initList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->fields_list = [
            'id_gamifications_daily_reward' => [
                'title' => $this->trans('ID'),
                'type' => 'text',
                'width' => 20,
            ],
            'name' => [
                'title' => $this->trans('Reward name'),
                'type' => 'text',
            ],
            'reward_type' => [
                'title' => $this->trans('Reward type'),
                'type' => 'select',
                'list' => GamificationsReward::getRewardsTranslations(),
                'filter_key' => 'gr!reward_type',
            ],
            'boost' => [
                'title' => $this->trans('Boost'),
                'type' => 'text',
                'align' => 'center',
            ],
            'active' => [
                'title' => $this->trans('Enabled'),
                'active' => 'status',
                'type' => 'bool',
                'ajax' => true,
                'orderby' => false,
                'align' => 'center',
            ],
            'times_won' => [
                'title' => $this->trans('Times won'),
                'type' => 'text',
                'align' => 'center',
            ],
        ];
    }

    /**
     * Initialize form
     */
    protected function initForm()
    {
        /** @var GamificationsRewardRepository $rewardRepository */
        $rewardRepository = $this->module->getEntityManager()->getRepository('GamificationsReward');
        $availableRewards =
            $rewardRepository->findAllNamesAndIds($this->context->language->id, $this->context->shop->id);

        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Daily reward'),
            ],
            'input' => [
                [
                    'label' => $this->trans('Choose reward'),
                    'type' => 'select',
                    'name' => 'id_reward',
                    'required' => true,
                    'options' => [
                        'id' => 'id_gamifications_reward',
                        'name' => 'name',
                        'query' => $availableRewards,
                    ],
                ],
                [
                    'label' => $this->trans('Boost'),
                    'type' => 'text',
                    'name' => 'boost',
                    'hint' => $this->trans('The chance of getting this reward.').' '.
                        $this->trans('The higher the boost the bigger chance of getting this reward.'),
                    'class' => 'fixed-width-xl'
                ],
                [
                    'label' => $this->trans('Enabled'),
                    'type' => 'switch',
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global'),
                        ],
                    ],
                    'name' => 'active',
                    'hint' => $this->trans('If disabled then no one will be able to get this reward at Daily Rewards'),
                ],
                [
                    'label' => $this->trans('For groups', [], 'Admin.Catalog.Feature'),
                    'type' => 'group',
                    'name' => 'groupBox',
                    'values' => Group::getGroups($this->context->language->id, $this->context->shop->id),
                    'hint' => $this->trans('Which customer groups can get this Daily Reward'),
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save'),
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->trans('Shop association'),
                'name' => 'checkBoxShopAsso',
            ];
        }
    }

    /**
     * Init fields form
     */
    protected function initFieldsValue()
    {
        if (!in_array($this->display, ['add', 'edit'])) {
            return;
        }

        $groups = Group::getGroups($this->context->language->id, $this->context->shop->id);
        $groupIds = [];

        if ('edit' == $this->display) {
            /** @var GamificationsDailyRewardRepository $dailyRewardRepository */
            $dailyRewardRepository = $this->module->getEntityManager()->getRepository('GamificationsDailyReward');
            $groupIds = $dailyRewardRepository->findAllGroupIds($this->object->id, $this->context->shop->id);
        }

        foreach ($groups as $group) {
            $this->fields_value['groupBox_'.$group['id_group']] = in_array($group['id_group'], $groupIds);
        }
    }

    /**
     * Display help message if setting is enabled
     */
    protected function displayHelp()
    {
        return $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/daily_rewards_info.tpl'
        );
    }
}