$(document).ready( function() {

  var city = $("[name='city']");

  var request = new XMLHttpRequest();
  request.open('GET', 'modules/lanix/getComunas.php',false);
  request.send();

  var cities = request.responseText;
  var cityArr = cities.split(',');
  city.autocomplete({
    source: cityArr
  });

  city.on("change",function (){
    if (!cityArr.includes(city.val())){
//      console.log('entramos al if');
      city.val('');
      //city.placeholder('Comuna no encontrada');
    }
  })

});
