 var clients = {
 'getsingle':  function(client_id){
  $('#error').addClass('hidden')
  $.ajax({
   url: 'rest/getClient', 
   type: 'POST', 
   data: {id: client_id},
   dataType: 'json',
   success: function(data){
	var rows = JSON.parse(data);

	var html = '<table class="table table-hovered table-bordered">'+
	'<tr><td>Название</td><td>'+rows.title+'</td></tr>'+
	'<tr><td>Полное название</td><td>'+rows.fullTitle+'</td></tr>'+
	'<tr><td>Адрес</td><td>'+rows.address+'</td></tr>'+
	'<tr><td>Телефон</td><td>'+rows.phone+'</td></tr>'+
	'<tr><td>Email</td><td>'+rows.email+'</td></tr>'+
	'<tr><td>ИНН</td><td>'+rows.inn+'</td></tr>'+
	'<tr><td>КПП</td><td>'+rows.kpp+'</td></tr>'+
	'<tr><td>Юр. адрес</td><td>'+rows.jurAddress+'</td></tr>'+
	'<tr><td>Город</td><td>'+rows.idCity+'</td></tr>'+
	'</table>';
	$('#content').html(html);
	$('#helper').modal();
   }, 
   error: function(data){
    $('.clients-table>tbody').html('<tr><td colspan="8">error: '+data.responseText+'</td></tr>');
   }
 });
  },
   'getcitylist': function(city_id){
    $('#error').addClass('hidden')
	$.ajax({
	 url: 'rest/getCities', 
	 type: 'POST', 
	 dataType: 'json', 
	 success: function(data){
	  var rows = JSON.parse(data);
	  if (rows.count != 0) $('select[name="idCity"]').html('');
	  var selected = '';
	  for(var i = 0; i < rows.count; i++){
	   var option = $("<option></option>")
         .attr("value",rows.cityList[i].id)
         .text(rows.cityList[i].name);
	   if (city_id == rows.cityList[i].id) option.attr('selected', 'selected'); 
	   $('#city_select').append(option);
	  }
	 }, 
	 error: function(data){
	 $('city_select').append($("<option></option>")
         .attr("value",'0')
         .text('Нет данных')); 
	 }
	});	
   },
   'getlist': function(){
  $('#error').addClass('hidden')
  $.ajax({
   url: 'rest/clientList', 
   type: 'GET', 
   dataType: 'json',
   beforeSend: function(){
   $('.clients-table>tbody').html('<tr><td colspan="8" class="text-center"><img src="content/image/ajax-loader.gif"></td></tr>');
   },
   success: function(data){
	$('.clients-table>tbody').html(clients.deploy(data));
   }, 
   error: function(data){
    $('.clients-table>tbody').html('<tr><td colspan="8">error: '+data.responseText+'</td></tr>');
   }
 });
  }, 
   'deploy': function(data){
    $('#error').addClass('hidden')
	var rows = JSON.parse(data);
	var html = '';
	for(var i = 0; i < rows.length; i++){
	 html += '<tr data-client="'+rows[i].id+'"><td>'+rows[i].title+'</td><td>'+rows[i].fullTitle+'</td><td>'+rows[i].inn+'</td><td>'+rows[i].kpp+'</td><td>'+rows[i].address+'</td><td>'+rows[i].jurAddress+'</td><td>'+rows[i].phone+'</td><td class="text-center">'+
	 '<div class="dropdown"><button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><i class="fa fa-bars"></i></button>'+
	 '<ul class="dropdown-menu text-left">'+
            '<li><a href="javascript:void(0)" onclick="clients.getsingle('+rows[i].id+')"><i class="fa fa-eye"></i>&nbsp;&nbsp;Подробно...</a></li>'+
            '<li><a href="javascript:void(0)" onclick="clients.edit('+rows[i].id+')"><i class="fa fa-pencil"></i>&nbsp;&nbsp;Редактировать</a></li>'+
            '<li class="divider"></li>'+
            '<li><a href="javascript:void(0)" onclick="clients.delete('+rows[i].id+')"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;Удалить</a></li>'+
          '</ul></div></td>';
	}
    return html;
   }, 
   'delete': function(client_id){
	$('#error').addClass('hidden')
	$.ajax({
     url: 'rest/deleteClient', 
     type: 'POST', 
     data:{id: client_id},
     success: function(data){
 	//   console.log(data);
	   var d = JSON.parse(data);
	   if (d.code == ''){
		clients.getlist();
	   }
     }, 
     error: function(data){
      $('.clients-table>tbody').html('<tr><td colspan="8" class="text-center">'+data.responseText+'</td></tr>');
     }
    });
   },
   'save' : function(){
     var jsonData = $('#workingArea').serialize();
	 $.ajax({url: 'rest/createUpdateClient', 
	  type: 'POST', 
	  beforeSend: function(){
	   $('#content button').button('load');
	  },
	  complete: function(){
	   $('#save').button('reset');
	  },
	  data:{'client':jsonData}, 
	  error: function(data){
	   console.log(data);
	  },
	  success: function(data){
	   var d = JSON.parse(data);
	   if (d.id){
	    $('#helper').modal('hide');
		clients.getlist();
	   }else{
	    $('#helper').modal('hide');
		$('#error').removeClass('hidden').html(d.message);
	   }
	  }
	 });
   }, 
  'edit': function(client_id){
  //cons
 $.ajax({
   url: 'rest/getClient', 
   type: 'POST', 
   data: {id: client_id},
   dataType: 'json',
   success: function(data){
 	var rows = JSON.parse(data);
    var html = '<form id="workingArea"><table class="table table-hovered table-bordered">'+
	'<tr><td>Название</td><td><input name="title" class="form-control" value="'+rows.title+'"></td></tr>'+
	'<tr><td>Полное название</td><td><input name="fullTitle" class="form-control" value="'+rows.fullTitle+'"></td></tr>'+
	'<tr><td>Адрес</td><td><input name="address" class="form-control" value="'+rows.address+'"></td></tr>'+
	'<tr><td>Телефон</td><td><input name="phone" class="form-control" value="'+rows.phone+'"></td></tr>'+
	'<tr><td>Email</td><td><input name="email" class="form-control" value="'+rows.email+'"></td></tr>'+
	'<tr><td>ИНН</td><td><input name="inn" class="form-control" value="'+rows.inn+'"></td></tr>'+
	'<tr><td>КПП</td><td><input name="kpp" class="form-control" value="'+rows.kpp+'"></td></tr>'+
	'<tr><td>Юр. адрес</td><td><input name="jurAddress" class="form-control" value="'+rows.jurAddress+'"></td></tr>'+
	'<tr><td>Город</td><td><select id="city_select" class="form-control"></select></td></tr>'+
	'</table>'+
	'<input type="hidden" name="operation" value="update"><input name="idCity" type="hidden" value="'+rows.idCity+'"><input name="id" type="hidden" value="'+rows.id+'">'+
	'</form><button id="save" class="btn btn-primary pull-right"  data-loading-text="Loading...">Сохранить</button>';
	$('#content').html(html);
    clients.getcitylist(rows.idCity);
	$('#save').on('click', function(){
	 clients.save();
	});
	$('#helper').modal();
	$('#city_select').on('change', function(){
	 $('input[name="idCity"]').val($('#city_select option:selected').val());
	});

  }
  });
  }
  
 } 
 $('.create').bind('click', function(){
  var html = '<form id="workingArea"><table class="table table-hovered table-bordered">'+
	'<tr><td>Название</td><td><input name="title" class="form-control"></td></tr>'+
	'<tr><td>Полное название</td><td><input name="fullTitle" class="form-control"></td></tr>'+
	'<tr><td>Адрес</td><td><input name="address" class="form-control"></td></tr>'+
	'<tr><td>Телефон</td><td><input name="phone" class="form-control"></td></tr>'+
	'<tr><td>Email</td><td><input name="email" class="form-control"></td></tr>'+
	'<tr><td>ИНН</td><td><input name="inn" class="form-control"></td></tr>'+
	'<tr><td>КПП</td><td><input name="kpp" class="form-control"></td></tr>'+
	'<tr><td>Юр. адрес</td><td><input name="jurAddress" class="form-control"></td></tr>'+
	'<tr><td>Город</td><td><select id="city_select" class="form-control"></select></td></tr>'+
	'</table>'+
	'<input type="hidden" name="operation" value="create"><input name="idCity" type="hidden" value="3902">'+
	'</form><button id="save" class="btn btn-primary pull-right">Сохранить</button>';
	$('#content').html(html);
	clients.getcitylist(0);
	$('#city_select').on('change', function(){
	 $('input[name="idCity"]').val($('#city_select option:selected').val());
	});
	$('#helper').modal();
	$('#save').on('click', function(){
	 clients.save();
	});
 });
