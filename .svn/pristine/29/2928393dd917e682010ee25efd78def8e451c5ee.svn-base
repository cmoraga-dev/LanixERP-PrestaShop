<?php


namespace cl\lanixerp\pos\modelo\genericas;

use Lanix\LxUtilDB;
use Lanix\Text;
use PrestaShop\PrestaShop\Adapter\Entity\Category;
use PrestaShop\PrestaShop\Adapter\Entity\Configuration;
use PrestaShop\PrestaShop\Adapter\Entity\PrestaShopLogger;
use JMS\Serializer\Annotation as Serializer;
/** @Serializer\XmlRoot ("tablaGenerica") */
class TablaGenerica
{

    private $tblnombre, $apiName;

    /** @Serializer\Type ("integer") */
    private $codigoTabla;

    /** @Serializer\Type ("string") */
    private $codigo;

    /** @Serializer\Type ("string") */
    private $descripcionTabla;

    /** @Serializer\Type ("string") */
    private $descripcion;

    /** @Serializer\Type ("string") */
    private $texto1;

    /** @Serializer\Type ("string") */
    private $texto2;

    /** @Serializer\Type ("string") */
    private $texto3;

    /** @Serializer\Type ("string") */
    private $texto4;

    /** @Serializer\Type ("string") */
    private $texto5;

    public function getTexto5()
    {
        return $this->texto5;
    }

    public function setTexto5($texto5)
    {
        $this->texto5 = $texto5;
    }

    public function setCodigoTabla($codigoTabla)
    {
        $this->codigoTabla = $codigoTabla;
    }

    public function setDescripcionTabla($descripcionTabla)
    {
        $this->descripcionTabla = $descripcionTabla;
    }


    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setTexto1($texto1)
    {
        $this->texto1 = $texto1;
    }

    public function setTexto2($texto2)
    {
        $this->texto2 = $texto2;
    }

    public function setTexto3($texto3)
    {
        $this->texto3 = $texto3;
    }

    public function setTexto4($texto4)
    {
        $this->texto4 = $texto4;
    }


    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

    }

    public function save (){
        if ($this->descripcionTabla === null || $this->descripcionTabla == null || empty($this->descripcionTabla))
        {
            PrestaShopLogger::addLog('Sin descripcion','1','','TablaGenerica','',true);
            return;
        }

        switch ($this->codigoTabla){

            case '63':
                $this->apiName = 'comunas';
                $this->saveComuna();
                break;
            case '73':
                $this->apiName = 'familias';
                $this->saveFamilia();
                break;
            case '74':
                $this->apiName = 'subfamilias';
                 $this->saveFamilia();
                break;
        }

    }

    private function saveFamilia()
    {

        $homeCat = Configuration::get('PS_HOME_CATEGORY');
        $categoryExistsInPshop = Category::searchByName((int)Configuration::get('PS_LANG_DEFAULT'), $this->descripcion);
        $foundCategoryName = array();
        //el siguiente if descarta los casos en que hay match pero no corresponde al tipo de objeto
        //por ej: queremos insertar una subfamilia, y se ha encontrado una familia de mismo nombre
        //el descarte se hace analizando el id_parent

        $this->deleteNonAssociatedCategories($categoryExistsInPshop);
        $categoryExistsInPshop = Category::searchByName((int)Configuration::get('PS_LANG_DEFAULT'), $this->descripcion);


        foreach ($categoryExistsInPshop as $cat) {
            if ($this->apiName == 'familias') {
                if ($cat['id_parent'] == $homeCat) $foundCategoryName = $cat;
            } else {
                if ($cat['id_parent'] != $homeCat) $foundCategoryName = $cat;
            }
        }

        //se analiza si existe registro en tabla de extension
        $codigoLxExists = LxUtilDB::selectFromTblGenericaWhere('codigo',$this->codigoTabla,'codigo = "'.$this->codigo.'"');
        //se hace un get al id parent en caso de existir
        $idParent = LxUtilDB::selectFromTblGenericaWhere('id_pshop','73','codigo = "'.$this->texto2.'"');

        if ($idParent == false) $idParent = $homeCat;

        //1er caso: sin registro en tbl lanix ni en PS, se crea nuevo objeto

        if ($codigoLxExists == false && count($foundCategoryName) == 0 ) {
            $cat = new Category();
            $cat->id_parent = $homeCat;
            if ($this->codigoTabla == '74') $cat->id_parent = (int)$idParent;
            $cat->is_root_category = false;
            $cat->name = array((int)Configuration::get('PS_LANG_DEFAULT') => $this->descripcion);
            $cat->link_rewrite = array((int)Configuration::get('PS_LANG_DEFAULT') =>  str_replace(" ","-",Text::replaceAccents($this->descripcion))) ;

            try {
                if ($cat->add()) {
                    $data = array(
                        'codigo_tabla' => $this->codigoTabla,
                        'codigo' => $this->codigo,
                        'id_pshop' => $cat->id,
                        'descripcion' => $this->descripcion
                    );
                    LxUtilDB::saveData('lx_tabla_generica', $data);
                    LxUtilDB::updateTblSync($this->apiName);
                }
            } catch (\PrestaShopDatabaseException $e) {
                dump($e);
                \PrestaShopLogger::addLog('Error al insertar familia o subfamilia: '.$e,3);
            }
        //2do caso: existe en registro ya en la tabla de extension
        //actualizar data en Pshop solamente
        }elseif ($codigoLxExists != false){

            $getIdPshopByLxCodigo = LxUtilDB::selectFromTblGenericaWhere('id_pshop',$this->codigoTabla,'codigo = "'.$this->codigo.'"');
            $cat = new Category((int)$getIdPshopByLxCodigo);
            $cat->id_parent = $homeCat;
            if ($this->codigoTabla == '74') $cat->id_parent = (int)$idParent;
            $cat->name = array((int)Configuration::get('PS_LANG_DEFAULT') => $this->descripcion);
            try {
                $cat->update();
            }catch (\PrestaShopException $e){
                dump($this->descripcion);
                dump($this->texto2);
                dump($idParent);
                dump($e->getMessage());
            }

            LxUtilDB::updateTblSync($this->apiName);

        //3er caso: no existe en tbl extension
        //pero si existe en PShop
        //solo enganchar
        }elseif ($codigoLxExists == false && count($foundCategoryName) == 1) {

            $data = array(
                'codigo_tabla' => $this->codigoTabla,
                'codigo' => $this->codigo,
                'id_pshop' => $foundCategoryName[0]['id_category'],
                'descripcion' => $this->descripcion
            );

            LxUtilDB::saveData('lx_tabla_generica', $data);
            LxUtilDB::updateTblSync($this->apiName);

        }
        //19 = 1 solo array con todos los datos. Es decir, solo 1 resultado
        elseif ($codigoLxExists == false && count($foundCategoryName) == 19){
            $data = array(
                'codigo_tabla' => $this->codigoTabla,
                'codigo' => $this->codigo,
                'id_pshop' => $foundCategoryName['id_category'],
                'descripcion' => $this->descripcion
            );

            LxUtilDB::saveData('lx_tabla_generica', $data);
            LxUtilDB::updateTblSync($this->apiName);

        }

    }

    private function deleteNonAssociatedCategories (array $arrayResults){
        if (count($arrayResults)>1){
            foreach ($arrayResults as $cat){
                $cat = new Category($cat['id_category']);
                if($cat->id_parent == 0) $cat->delete();
            }
        }

    }

    private function saveComuna()
    {

        $data = array(
            'codigo_tabla' => $this->codigoTabla,
            'codigo' => $this->codigo,
            'id_pshop' => '-1',
            'descripcion' => $this->descripcion
        );

        LxUtilDB::saveData('lx_tabla_generica', $data);
        LxUtilDB::updateTblSync($this->apiName);

    }



}
