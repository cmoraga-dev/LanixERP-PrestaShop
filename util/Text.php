<?php


namespace Lanix;


class Text
{

    public static function  replaceAccents ($string) {
        $notAllowed= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","�");
        $allowed= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $text = str_replace($notAllowed, $allowed ,$string);
        return $text;
    }

    public static function  replaceForbiddenChars ($string) {
        $notAllowed= array ("1","2","3","4","5","6","7","8","9","0","-","<",">",",",";","¿","?","=","+¨","(",")","@","#","_","{","}","$","%","�",".");
        $allowed= array ("");
        $text = str_replace($notAllowed, $allowed ,$string);
        return $text;
    }

}
