<?php
/**
 * Created by PhpStorm.
 * User: khouloud.belguith
 * Date: 09/05/18
 * Time: 12:25
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

Class mymodule extends Module
{
    const CLASS_NAME = 'mymodule';

    public function __construct($name = null, Context $context = null)
    {
        $this->displayName = $this->l('My module');
        $this->description = $this->l('Description of my module.');
        $this->name = 'mymodule';
//        $this->tab = 'emailing';
        $this->version = '1.0.0';
        $this->author = 'khouloud';
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME'))
            $this->warning = $this->l('No name provided.');

        parent::__construct($name, $context);
    }

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install() ||
            !$this->registerHook('backOfficeFooter') ||
            !$this->registerHook('additionalCustomerFormFields')
        )
            return false;

        return true;
    }
   public function hookAdditionalCustomerFormFields($params)
	{
return [
                    (new FormField)
                    ->setName('professionnal_id')
                    ->setType('text')
                    //->setRequired(true) Décommenter pour rendre obligatoire
                    ->setLabel($this->l('Professionnal id'))   
        ];


}

   
}
