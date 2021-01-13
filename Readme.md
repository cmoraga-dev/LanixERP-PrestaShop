# Lanix Módulo para PrestaShop

**ES**

## Introducción

Este es un módulo diseñado para integrar la tienda PrestaShop a un software específico, Lanix ERP.

La integración consiste en importar y sincronizar los siguientes datos: **productos, su precio, foto, stock y otras características; información de clientes y sus direcciones**.

Adicionalmente, el módulo importa el **listado de comunas de Chile**, y modifica el formulario de cliente a fin de guardar el dato **rut**. Estos datos son necesarios para poder emitir boletas electrónicas.

En el otro sentido, una vez finalizada una compra en PrestaShop (**una vez esté el pago aceptado**), el sistema envía al software ERP los datos necesarios para que éste emita una boleta electrónica.

## Funcionamiento del módulo

### Cliente REST
1. La comunicación de datos entre el sofware ERP y PrestaShop se realiza a través del **Http Client de PHP Symfony [https://symfony.com/doc/current/http_client.html]**, el cual recibe y envía datos a los métodos expuestos por el web service de Lanix ERP (ws/LxRestClient.php).

### XML Parser
2. Para importar grandes volúmenes de datos en XML y separarlos en forma eficiente se usó el **xml parser de PHP [https://www.php.net/manual/es/book.xml.php]** (xmlParser/LxDeserializer.php).

3. Para guardar los datos provenientes de LanixERP y posteriormente transformarlos en objetos de PrestaShop, se crearon clases idénticas a las del ERP, pero con un método save que finalmente almacena los datos en la tienda web (véase /classes).

### SQL
4. Se usaron tablas de extensión en MySQL para mantener equivalencias de datos, así como un control de sincronización de información (véase directorio /sql).

### EvTimer / Cron
5. Para mantener la sincronización de la información se usó el siguiente algoritmo: 

1. Se consulta tabla de actualización, se obtiene fecha de última actualización.
2. Se obtienen los registros con fecha de modificación posterior a dicha fecha (si no hay fecha, se cargan todos los registros).
3. Se importan los datos a través del XML Parser y la gestión que cada clase haga de la entidad correspondiente (importar, actualizar, manejo de excepciones).
3. Se actualiza la fecha de actualización.
4. Esta operación se repite cada 2 minutos.

Para repetir esta operación se usaron 2 técnicas. La primera, a través de la extensión **EvTimer[https://www.php.net/manual/es/class.evtimer.php]**.

Como alternativa más simple, se optó también por dar la posibilidad de usar Cron jobs. El comando se crea a través del componente **Process de PHP Symfony[https://symfony.com/doc/current/components/process.html]**.

El módulo es capaz de determinar si existe EvTimer en el sistema. En caso de no exisir, procede a crear el trabajo en Cron a través de Process.

**Este módulo aún no se considera un trabajo terminado**.


Por cmoraga-dev.