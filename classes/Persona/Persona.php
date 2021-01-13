<?php


namespace cl\lanixerp\pos\modelo\persona;
use JMS\Serializer\Annotation as Serializer;
use Lanix\LxUtilDB;
use Lanix\LxUtilRut;
use Lanix\Text;
use PrestaShop\PrestaShop\Adapter\Entity\Configuration;
use PrestaShop\PrestaShop\Adapter\Entity\Customer;
use PrestaShop\PrestaShop\Adapter\Entity\CustomerAddress;
use PrestaShop\PrestaShop\Adapter\Entity\PrestaShopLogger;

/** @Serializer\XmlRoot("persona") */
class Persona
{
    private $psCustomer;


    /**
     * @Serializer\XmlKeyValuePairs
     * @Serializer\Type("Lanix\Key")
     **/
    private $key;

    /** @Serializer\Type("Lanix\CodigoLegal") */
    private $codigoLegal;

    /** @Serializer\Type ("string") */
    private $razonSocial;

    /** @Serializer\Type ("string") */
    private $giro;

    /** @Serializer\Type ("string") */
    private $email;

    /** @Serializer\XmlKeyValuePairs
     * @Serializer\Type ("Lanix\Direccion")
     */
    private $direccion;

    /** @Serializer\Type ("string") */
    private $telefono;

    /**
     * Persona constructor.
     * @param $key
     * @param $codigoLegal
     * @param $razonSocial
     * @param $giro
     * @param $email
     * @param $direccion
     * @param $telefono
     */
    public function __construct($key = null, $codigoLegal = null, $razonSocial = null, $giro = null, $email = null, $direccion = null, $telefono = null)
    {
        $this->key = $key;
        $this->codigoLegal = $codigoLegal;
        $this->razonSocial = $razonSocial;
        $this->giro = $giro;
        $this->email = $email;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
    }


    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getGiro() {
        return $this->giro;
    }

    public function setGiro($giro) {
        $this->giro = $giro;
    }

    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = ucwords(strtolower($razonSocial));
    }

    public function getKey(){
        return $this->key;
    }

    public function setKey($key){
        $this->key = $key;
    }

    public function getCodigoLegal() {
        return $this->codigoLegal;
    }


    public function setCodigoLegal($codigoLegal) {
        $this->codigoLegal =$codigoLegal;
    }

    public function getEmail() {
        return $this->email;
    }


    public function setEmail($email) {
        $this->email = $email;
    }

    public function save () {
        $codigoKey = $this->key->getCodigo();

        //chequeos de validez de datos
        if ($codigoKey === null || $codigoKey == null || empty($codigoKey))
        {
            PrestaShopLogger::addLog('Sin key','1','','Persona','',true);
            return;
        }

        if ($this->razonSocial == null || $this->razonSocial === null || empty($this->razonSocial)) {
            PrestaShopLogger::addLog('Sin razonsocial','1','','Persona',$codigoKey,true);
            return;
        }

        if ($this->email == null || $this->email === null || empty($this->email)) {
            PrestaShopLogger::addLog('Sin correo','1','','Persona',$codigoKey,true);
            return;
        }
        //fin chequeos de validez de datos


        //búsqueda de key existente en sistema
        $searchID = LxUtilDB::getIdPersonaByLxCodigo($codigoKey);

        //búsqueda de mail existente en sistema
        $searchEmail = Customer::getCustomersByEmail($this->email);
        // generalmente la búsqueda debería devolver solo una (1) ocurrencia
        // en caso de ser más, solo se tomará en cuenta la primera

        $this->psCustomer = new Customer();
        $codlegal = $this->codigoLegal->value;
        $digver = $this->codigoLegal->digver;

        $cost = 4;

        // caso 1: email & id no encontrados, se crea usuario
        if ( $searchID == false && count($searchEmail) == 0 ){

            $this->psCustomer->firstname = Text::replaceForbiddenChars($this->razonSocial);
            $this->psCustomer->lastname = ".";
            $this->psCustomer->email = $this->email;
            $this->psCustomer->passwd = password_hash($codlegal, PASSWORD_BCRYPT,['cost' => $cost]);
            try {
                $this->psCustomer->save();
            }catch (\PrestaShopException $exception){
                dump($exception->getMessage());
                dump($this->psCustomer->firstname);
            }

            $this->saveAdress($this->psCustomer);
            LxUtilDB::saveLxCustomers($this->psCustomer->id,$codigoKey, LxUtilRut::agregaPuntos($codlegal,$digver),
                $codlegal,$digver);

        }
        // caso 2: id Lanix encontrado en sistema, se asume que email también existe si está
        // este dato.
        // En este caso se puede actualizar el correo o la direccion
        elseif (is_string($searchID)){
            $searchCustomer = new Customer((int)$searchID);
            $searchCustomer->email = $this->email;
            $searchCustomer->update();
            $this->saveAdress($searchCustomer);
            LxUtilDB::saveLxCustomers($searchCustomer->id,$codigoKey,LxUtilRut::agregaPuntos($codlegal,$digver),
                $codlegal,$digver);

        }
        // caso 3: no existe id, pero sí está correo en sistema
        // en este caso, solo se asocia agregando Key y CodigoLegal
        elseif ($searchID == false && count($searchEmail) >0 ){

            PrestaShopLogger::addLog('Correo ya existente en sistema','1','','Persona',$codigoKey,true);
            LxUtilDB::saveLxCustomers($searchEmail[0]['id_customer'],$codigoKey,LxUtilRut::agregaPuntos($codlegal,$digver),
                $codlegal,$digver);

        }
        LxUtilDB::updateTblSync('clientes');
    }


    private function saveAdress ($customer) {
        /** @var  $customer Customer */
        if ($this->direccion == null || empty($calleNumero)) {
            PrestaShopLogger::addLog("No se encontró dirección válida para cliente ".$this->psCustomer->id,'1');
            return;
        }

        $comuna = $this->direccion->getComuna()->getDescripcion();
        $calleNumero = $this->direccion->getCalleNumero();
        $codPostal = $this->direccion->getCodPostal();

        $arrayDirections = $customer->getAddresses(Configuration::get('PS_LANG_DEFAULT'));

        if (count($arrayDirections)>0){
           $address = new CustomerAddress($arrayDirections[0]['id_address']);
        }else{
            $address = new CustomerAddress();
        }

        $address->id_customer = $customer->id;
        $address->id_country = 68;
        $address->firstname = $customer->firstname;
        $address->lastname = $customer->lastname;
        $address->alias = "Dirección";
        $address->city = $comuna;
        $address->address1 = $calleNumero;
        if ($codPostal != null){
            $address->postcode = $codPostal;
        }else{
            $address->postcode = "000-0000";
        }
        $address->save();
    }

}

