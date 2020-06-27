<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Pv_Middlename extends Module
{
    public function __construct()
    {
        $this->name = 'pv_middlename';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Rodrigo Laurindo';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Middle Name module');
        $this->description = $this->l('A super advanced middle name module xD');

        $this->ps_versions_compliancy = array('min' => '1.7.6.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include __DIR__ .'/sql/install.php';

        return parent::install() &&
            $this->registerHook('additionalCustomerFormFields') &&
            $this->registerHook('actionObjectCustomerAddAfter') &&
            $this->registerHook('actionObjectCustomerUpdateAfter');
    }

    public function uninstall()
    {
        include __DIR__ .'/sql/uninstall.php';

        return parent::uninstall();
    }

    public function hookadditionalCustomerFormFields($params)
    {
        $format = [
            'middlename' => (new FormField())
                ->setName('middlename')
                ->setType('text')
                ->setLabel($this->l('Middle Name'))
            ];

        if (isset($params['cookie']->id_customer)) {
            $middleName = Db::getInstance()->getValue(
                'select `middlename` from `' . _DB_PREFIX_. 'pv_middlename` where id_customer = '
                . (int) $params['cookie']->id_customer
            );
            $format['middlename']->setValue($middleName);
        }

        // We want to insert it after First Name

        // Where is First Name?
        $position = (int) array_search('firstname', array_keys($params['fields']), null) + 1;

        // How many fields do we have?
        $fieldcount = count($params['fields']);

        // Lets insert it...
        $result = array_merge(
            array_slice($params['fields'], 0, $position),
            $format,
            array_slice($params['fields'], $position - $fieldcount)
        );

        // And set $params with the new updated array
        $params['fields'] = $result;
    }

    /**
     * When adding customers, we have to save middlename anywhere...
     *
     * @param $params
     * @throws PrestaShopDatabaseException
     */
    public function hookActionObjectCustomerAddAfter($params)
    {
        $this->saveMiddleName($params['object']->id);
    }

    /**
     *  When updating customers too...
     *
     * @param $params
     * @throws PrestaShopDatabaseException
     */
    public function hookActionObjectCustomerUpdateAfter($params)
    {
        $this->saveMiddleName($params['object']->id);
    }

    /**
     * And here we save it!
     *
     * @param $id_customer
     * @return bool
     * @throws PrestaShopDatabaseException
     */
    private function saveMiddleName($id_customer)
    {
        if ($middlename = Tools::getValue('middlename')) {
            return Db::getInstance()->insert(
                'pv_middlename',
                [
                    'id_customer' => (int) $id_customer,
                    'middlename' => pSQL($middlename),
                ],
                false,
                true,
                Db::REPLACE
            );
        }

        return true;
    }
}
