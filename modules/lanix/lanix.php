<?php
/**
* 2007-2020 PrestaShop
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
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
require_once(dirname(__FILE__) .'/PSWebServiceLibrary.php');
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Domain\Customer\Exception\CustomerException;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Search\Filters\CustomerFilters;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


if (!defined('_PS_VERSION_')) {
    exit;
}

class Lanix extends Module {
    protected $config_form = false;

    public function __construct() {

        $this->name = 'lanix';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Carlos M - LanixERP';
        $this->need_instance = 0;

         /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('PSLanix');
        $this->description = $this->l('Modulo prueba para lanix');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('LANIX_LIVE_MODE', false);

        $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'lanix_config` (
                  `RUT_EMPRESA` varchar(25),
                  PRIMARY KEY  (`RUT_EMPRESA`)
                  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $query2 = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extiende_clientes` (
                   `id_cliente` varchar(25),
                  `correo_prestashop` varchar(200),
                  `rut_cliente` varchar(25),

                  PRIMARY KEY  (`id_cliente`)
                  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        //      generateWebService();

        //create token

        $apiAccess = new WebserviceKey();
        $apiAccess->key = 'WYQ69RTZCPCG2DS4KNHB4FWMRDK6QZHT';
        $apiAccess->save();


        //activate web service

        //grant access

        $permissions = [        'customers' => ['GET' => 1, 'POST' => 1, 'PUT' => 1, 'DELETE' => 1, 'HEAD' => 1],
                                'orders' => ['GET' => 1, 'POST' => 1, 'PUT' => 1, 'DELETE' => 1, 'HEAD' => 1],
                                'products' => ['GET' => 1, 'POST' => 1, 'PUT' => 1, 'DELETE' => 1, 'HEAD' => 1]
                    ];

        WebserviceKey::setPermissionForAccount($apiAccess->id, $permissions);


        return parent::install() &&
            //sql
            Db::getInstance()->execute($query) &&
            Db::getInstance()->execute($query2) &&

            $this->registerHook('header') &&
            $this->registerHook('backOfficeFooter') &&

            // Modificar formulario cliente - front office
            $this->registerHook('ActionObjectCustomerAddAfter') &&
            $this->registerHook('ActionObjectCustomerUpdateAfter') &&
            $this->registerHook('additionalCustomerFormFields') &&

            // Modificar formulario cliente - back office
            $this->registerHook('ActionAdminCustomersFormModifier') &&
            $this->registerHook('ActionCustomerGridDefinitionModifier') &&
            $this->registerHook('actionCustomerGridQueryBuilderModifier') &&
            $this->registerHook('actionCustomerFormBuilderModifier') &&

            $this->registerHook('actionAfterCreateCustomerFormHandler') &&
            $this->registerHook('actionAfterUpdateCustomerFormHandler') &&

            $this->registerHook('backOfficeHeader');

    }

    //agrega rut - back office

    /**
     * Hook allows to modify Customers grid definition.
     * This hook is a right place to add/remove columns or actions (bulk, grid).
     *
     * @param array $params
     */
    public function hookActionCustomerGridDefinitionModifier(array $params)
    {
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];

        $definition
            ->getColumns()
            ->addAfter(
                'lastname',
                (new DataColumn('rut'))
                    ->setName('RUT')
                    ->setOptions([
                        'field' => 'rut'])
            );

        $definition->getFilters()->add(
            (new Filter('rut', TextType::class))
                ->setAssociatedColumn('rut')

        );

    }

    /**
     * Hook allows to modify Customers query builder and add custom sql statements.
     *
     * @param array $params
     */
    public function hookActionCustomerGridQueryBuilderModifier(array $params)
    {
        /** @var QueryBuilder $searchQueryBuilder */
        $searchQueryBuilder = $params['search_query_builder'];

        /** @var CustomerFilters $searchCriteria */
        $searchCriteria = $params['search_criteria'];

        $searchQueryBuilder->addSelect('z.rut_cliente as rut');

        $searchQueryBuilder->leftJoin(
            'c',
            '`' . pSQL(_DB_PREFIX_) . 'extiende_clientes`',
            'z',
            'c.id_customer = z.id_cliente'
        );

    }



    /** hookActionCustomerFormBuilderModifier
     * Agrega campo RUT al momento de editar cliente
     */
    public function hookActionCustomerFormBuilderModifier($params){
        // agregar campo usando FormBuilder

        /** @var FormBuilderInterface $formBuilder */

        $formBuilder = $params['form_builder'];
        $formBuilder->add('rut', TextType::class, [
            'label' => 'RUT',
            'required' => true,
        ]);

        //cargar dato en campo rut

        $params['data']['rut'] = $this->searchRut($params['id']);
        $formBuilder->setData($params['data']);
    }

    public function hookActionAfterUpdateCustomerFormHandler(array $params){
        $this->saveRutBackOffice($params['form_data'], $params);
    }

    public function hookActionAfterCreateCustomerFormHandler(array $params){
        $this->saveRutBackOffice($params['form_data'], $params);
    }



    //funciones para agregar campo RUT -  desde la tienda (Front)

    /** hookAdditionalCustomerFormFields
     *
     * Se crea el campo de formulario y se agrega en posición indicada
     * @params $params (array con los FormField que componen el formulario)
     */
    public function hookAdditionalCustomerFormFields($params){

        //creando FormField de rut
        $format = [   (new FormField)
            ->setRequired(true)
            ->setName('rut')
            ->setType('text')
            ->setLabel($this->l('RUT')
            )];

        //si ya tiene data, cargar valor en el FormField
        if (isset($params['cookie']->id_customer)) {
            $rut = $this->searchRut( $params['cookie']->id_customer);
            $format[0]->setValue($rut);
        }

        // definir posición de nuevo FormField dentro del array
        //en este caso, después de 'lastname'
        $position = (int) array_search('lastname', array_keys($params['fields']), null) + 1;
        $fieldcount = count($params['fields']);

        $result = array_merge(
            array_slice($params['fields'], 0, $position),
            $format,
            array_slice($params['fields'], $position - $fieldcount)
        );

        // Cargar nuevo FormField en el formulario
        $params['fields'] = $result;

    }

    /** hookActionObjectCustomerAddAfter
     * Se activa después de registro exitoso de nuevo cliente
     */
    public function hookActionObjectCustomerAddAfter($params)
    {
        $this->saveRut($params['object']->id);
    }

    /** hookActionObjectCustomerUpdateAfter
     * Se activa después de actualización de datos por parte del cliente
     */
    public function hookActionObjectCustomerUpdateAfter($params)
    {
        $this->saveRut($params['object']->id);
    }


    private function saveRutBackOffice ($data, $params) {
        return Db::getInstance()->insert(
            'extiende_clientes',
            [
                'id_cliente' => (int)$params['id'],
                'correo_prestashop' =>$data['email'],
                'rut_cliente' => $data['rut']
                ],
                false,
                true,
                Db::REPLACE
        );
    }




    /** saveRut
     * @params id_customer
     * guarda el rut ingresado en la tabla propia del módulo 'extiende_clientes'
     */
    private function saveRut ($id) {
        if ($rut = Tools::getValue('rut')) {
            return Db::getInstance()->insert(
                'extiende_clientes',
                [
                    'id_cliente' => (int)$id,
                    'correo_prestashop' => pSQL(Tools::getValue('email')),
                    'rut_cliente' => pSQL($rut),
                ],
                false,
                true,
                Db::REPLACE
            );
        }
        return true;
    }

    private function searchRut ($idCustomer){
        return Db::getInstance()->getValue(
            'select `rut_cliente` from `' . _DB_PREFIX_. 'extiende_clientes` where id_cliente = '
            . (int) $idCustomer);
    }

//*fin agregar rut



    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submit'.$this->name)) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitLanixModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    /*array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'LANIX_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'LANIX_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ), */
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'LANIX_RUT_EMPRESA',
                        'label' => $this->l('RUT Empresa'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues(){
        return array(
//            'LANIX_LIVE_MODE' => Configuration::get('LANIX_LIVE_MODE', true),
//            'LANIX_ACCOUNT_EMAIL' => Configuration::get('LANIX_ACCOUNT_EMAIL', 'contact@prestashop.com'),
//            'LANIX_ACCOUNT_PASSWORD' => Configuration::get('LANIX_ACCOUNT_PASSWORD', null),
              'LANIX_RUT_EMPRESA' => Configuration::get('LANIX_RUT_EMPRESA', null)
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess(){
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader(){
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader(){
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function uninstall() {
        Configuration::deleteByName('LANIX_RUT_EMPRESA');

        return
            $this->uninstallDb('extiende_clientes') &&
            $this->uninstallDb('lanix') &&
            parent::uninstall();
    }

    public function uninstallDb($name) {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.bqSQL($name));
    }
}
