<?=$header?>
<div class='wrapper'>
 <div class='head-top'></div>
 <div class='pre-top'></div>
 <div class='menu'></div>
 <div class='content container'>
  <div class='col-sm-12 alert alert-danger hidden' id='error'></div>
  <table class='table table-hover table-bordered  table-striped clients-table'>
   <thead>
    <tr><td>Имя</td><td>Полное имя</td><td>ИНН</td><td>КПП</td><td>Адрес</td><td>Юр. адрес</td><td>Телефон</td><td>Операция</td></tr>
   </thead>
   <tbody>
   </tbody>
  </table>
 <button class='btn btn-primary pull-right create'>Создать клиента</button>
 </div>
</div>
<?=$footer?>

<div id="helper" class="modal fade">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Клиент</h4>
      </div>
      <div class="modal-body" id='content'></div>  
      <div class="modal-footer"></div>
    </div>
  </div>
</div> 
<style>footer{position: absolute; width: 100%; bottom: 0}</style>
<script language='javascript' src='content/js/common.js'></script>
<script language='javascript'>clients.getlist();</script>