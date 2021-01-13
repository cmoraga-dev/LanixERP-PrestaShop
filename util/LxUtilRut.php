<?php

namespace Lanix;
use Lanix\LxUtilDB;

class LxUtilRut{

    public static function rutExists($rut){
        return LxUtilDB::checkRutExists($rut);
    }

    /** Elimina puntos de rut y devuelve array con numero y DV separados
     * @param $rut . en formato XX.XXX.XXX-X
     * @return array (0=> numero sin DV, 1 =>DV)
     */
    public static function quitarPuntos ($rut) {

        $arrayRut = explode("-", $rut);
        $num = str_replace(".","",$arrayRut[0]);
        $dv = $arrayRut[1];

        return array($num,$dv);

    }



    /** Función hecha para los rut importados
     * desde el WebService de Lanix
     * Recibe el número del rut, sin DV ni guion
     * Retorna número con puntos concatenados
     * @param $num
     */
    public static function agregaPuntos ($num, $dv) {

        if (strlen($num) == 8){

           return
               substr($num,0,2).'.'.substr($num,2,3).'.'.substr($num,5,3) .'-'.$dv;
        }elseif (strlen($num) == 7){

            return
                substr($num,0,1).'.'.substr($num,1,3).'.'.substr($num,4,3) .'-'.$dv;

        }else
            return null;
    }


    /** Chequea primero el formato de RUT
     * con expresión regular
     * Luego, chequea dígito verificador
     * @param $rut
     */
    public static function validateRut($rut){

        return self::validateFormat($rut) &&
            self::validateDV($rut);
    }

    /** Chequea formato de texto rut usando RegEX
     * @param $rut
     * @return boolean
     */
    private static function validateFormat ($rut){

        $pattern= "/^\d{1,2}+\.{1}+\d{3}+\.{1}+\d{3}+-+(\d{1}|k{1}|K{1})$/";
        return preg_match($pattern,$rut) == 1;
    }



    /** Realiza operaciones numéricas para encontrar dígito verificador de rut
     * @param $inputNumRut rut
     * @return boolean
     */
    private static function validateDV ($inputNumRut){

        $arrayRut = self::procesaTextoRut($inputNumRut);
        $inputNumRut = str_split($arrayRut[0]);
        $inputDV = $arrayRut[1];

        //multiplicar los números que componen rut
        //de derecha a izq
        //por la serie 2-7
        $a = 2;
        $sumRut = 0;

        foreach ($inputNumRut as $digit) {
            $operationResult = intval($digit) * $a;
            $sumRut += $operationResult;

            if ($a == 7) $a = 1;
            $a++;
        }

        //obtener dígito verificador
        $mod = $sumRut % 11;
        $result = 11 - $mod;


        if ($result == 11)  $validDV = "0";
        else if ($result ==10)   $validDV = "K";
        else $validDV = strval($result);

        return strcmp($validDV,strtoupper($inputDV)) == 0;

    }


    /** Recibe rut como texto
     * Elimina puntos y guion
     * Invierte numero de rut
     * @param $rut
     * @return array
     * [0] String con numero de rut invertido
     * [1] digito verificador
     */
    private static function procesaTextoRut ($rut){

        //eliminar digito verificador
        $arrayRut = explode("-",$rut);
        $dv = $arrayRut[1];

        //eliminar puntos
        $rutTextoSinPunto = str_replace(".","",$arrayRut[0]);

        //voltear número
        $rutInvertido = strrev ($rutTextoSinPunto);

        return array($rutInvertido,$dv);
    }

}
