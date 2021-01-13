
$(document).ready( function(){

  //selector btns
  selectBtns = $('[id^=LX_GROUP_]');

  if(selectBtns.size() > 0) {

    var $table = $('<table>');

    //css
    $table.css('width', "75%");
    $table.css('margin', "0 auto");
    $table.css('text-align','left');

// caption
    $table.append('<caption style="text-align:left" class="control-label col-lg-3">Equivalencias de Grupos de Clientes</caption>')
      // thead
      .append('<thead>').children('thead')
      .append('<tr/>').children('tr')
      .append('<th>PrestaShop</th><th>Lanix ERP</th>');

//tbody
    var $tbody = $table.append('<tbody/>').children('tbody');

// add rows in a loop

    selectBtns.each(function (){
      var desc = $(this).attr('id');
      desc = desc.replace('LX_GROUP_','')
      $tbody.append('<tr/>').children('tr:last')
        .append("<td >" + desc + "</td>")
        .append("<td >").children('td:last').append(this)
    });

// add table to dom
    $table.appendTo('.form-wrapper');
  }

  //btn sync

  c = $("#module_sync");
  $(".panel-footer").append(c);
  c.hide();

  var request = new XMLHttpRequest();
  request.open('GET','../modules/lanix/checktimer.php',false);
  request.send(null);

  d = $("#LX_COD_LOCAL");

  if (request.response === "not running"){
    if (d.length>0 && d.is(':disabled')) c.show();
  }
});
