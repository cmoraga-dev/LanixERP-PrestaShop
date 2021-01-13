<?php

namespace Lanix;


use DOMElement;
use DOMDocument;
use DOMNode;
use DOMDocumentFragment;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;

class LxDeserializer
{
    protected $document, $currentElement,$rootElement, $rootName ,
        $currentParent, $currentAttrs, $domDocument, $className, $objectName, $numElements;
    protected $elements   = null;



    function parse  ($data) {

        $parser = xml_parser_create();
        xml_parser_set_option ($parser,XML_OPTION_CASE_FOLDING,false );
        xml_set_element_handler($parser, array($this, 'startElement'), array($this, 'endElement'));
        xml_set_character_data_handler($parser, array($this,"characters"));

        xml_parse($parser, $data);
        xml_parser_free($parser); // deletes the parser
    }


    /**
     * @param $parser
     * @param $name
     * @param $attrs
     */
    function startElement($parser, $name, $attrs)
    {

        if ($name == 'datos') {
            foreach ($attrs as $key => $attr) {
                switch ($key) {
                    case "objeto":
                        $this->className = str_replace(".","\\",$attr) ;
                        $pieces = explode(".",$attr);
                        $this->objectName = lcfirst(array_pop($pieces));
                        break;
                    case 'cantidad':
                        //cantidad de elementos
                        $this->numElements = $attr;
                        break;
                    case 'fecha':
                        //...
                    case 'hora':
                        //....
                }
            }
        }elseif ($name == $this->objectName){

            $this->document = new DOMDocument('1.0', 'UTF-8');
            $this->rootName = $name;
            $this->rootElement = $this->document->createElement($name, null);
            $this->document->appendChild($this->rootElement);
            $this->currentElement = $this->rootElement;
            $this->currentParent = null;
        } else {

            $newElement = $this->document->createElement($name);
            foreach ($attrs as $key => $attr) $newElement->setAttribute($key,$attr);

            //si no hay current element - cuando pasaria?
            if ($this->currentElement == null){
                $this->currentElement = $newElement;
                $this->rootElement->appendChild($this->currentElement);
                $this->currentParent = $this->rootElement;

            }else{
                $this->currentElement->appendChild($newElement);
                $this->currentParent = $this->currentElement;
                $this->currentElement = $newElement;
            }
        }
    }

    /**
     * @param $parser
     * @param $data
     */
    function characters($parser, $data) {
        if ($this->currentElement != null && !empty(trim($data)))  {
            $this->currentElement->appendChild($this->document->createTextNode($data));
        }

    }


    /**
     * @param $parser
     * @param $name
     */
    function endElement($parser, $name)
    {

        if ($name == $this->rootName){

            $serializer = SerializerBuilder::create()
                ->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
                ->build();

            $object = $serializer->deserialize($this->document->saveXML(),$this->className,'xml');
            $object->save();

        }
        elseif ($this->currentElement != null
            && $this->currentElement->localName == $name ) {

            $this->currentElement = $this->currentParent;
            $this->currentParent = $this->rootElement;
            $parentNode = $this->currentElement->parentNode;


            if ($parentNode instanceof DOMElement) {
                $this->currentParent = $parentNode;
            }else {
                $this->currentParent = $this->rootElement;
             }
        }
    }

    public function getCountElements (){
        return $this->numElements;
    }

}
