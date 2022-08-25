<h1 class="m-4">{{mode_desc}}</h1>
<section>
  <form action="index.php?page=admin-cliente" method="post">
    <input type="hidden" name="mode" value="{{mode}}" />
    <input type="hidden" name="customer_id" value="{{customer_id}}" />
    <div class="m-4">
      <label for="customer_name" class="form-label">Nombre: </label>
      <input {{if readonly}}readonly{{endif readonly}} type="text" id="customer_name" name="customer_name" class="form-control" value="{{customer_name}}"/>
      {{if error_customer_name}}
        {{foreach error_customer_name}}
          <div>{{this}}</div>
        {{endfor error_customer_name}}
      {{endif error_customer_name}}
    </div>
    <div class="m-4">
      <label for="customer_lastname" class="form-label">Apellido: </label>
      <input {{if readonly}}readonly{{endif readonly}} type="text" id="customer_lastname" name="customer_lastname" class="form-control" value="{{customer_lastname}}"/>
      {{if error_customer_lastname}}
        {{foreach error_customer_lastname}}
          <div>{{this}}</div>
        {{endfor error_customer_lastname}}
      {{endif error_customer_lastname}}
    </div>
    <div class="m-4">
      <label for="customer_country" class="form-label">Pais: </label>
      <select class="form-control" name="customer_country" id="customer_country" {{if readonly}}readonly disabled{{endif readonly}}>
        {{foreach arr_customer_country}}
          <option value="{{value}}" {{selected}}>{{text}}</option>
        {{endfor arr_customer_country}}
      </select>
    </div>
    <div class="m-4">
      <label for="customer_city" class="form-label">Ciudad: </label>
      <input {{if readonly}}readonly{{endif readonly}} type="text" id="customer_city" name="customer_city" class="form-control" value="{{customer_city}}"/>
      {{if error_customer_city}}
        {{foreach error_customer_city}}
          <div>{{this}}</div>
        {{endfor error_customer_city}}
      {{endif error_customer_city}}
    </div>
    <div class="m-4">
      <label for="customer_address" class="form-label">Dirección: </label>
      <input {{if readonly}}readonly{{endif readonly}} type="text" id="customer_address" name="customer_address" class="form-control" value="{{customer_address}}"/>
      {{if error_customer_address}}
        {{foreach error_customer_address}}
          <div>{{this}}</div>
        {{endfor error_customer_address}}
      {{endif error_customer_address}}
    </div>
    <div class="m-4">
      <label for="customer_postal_code" class="form-label">Codigo postal: </label>
      <input {{if readonly}}readonly{{endif readonly}} type="text" id="customer_postal_code" name="customer_postal_code" class="form-control" value="{{customer_postal_code}}"/>
      {{if error_customer_postal_code}}
        {{foreach error_customer_postal_code}}
          <div>{{this}}</div>
        {{endfor error_customer_postal_code}}
      {{endif error_customer_postal_code}}
    </div>
    <div class="m-4">
      <label for="customer_phone_number" class="form-label">Teléfono: </label>
      <input {{if readonly}}readonly{{endif readonly}} type="text" id="customer_phone_number" name="customer_phone_number" class="form-control" value="{{customer_phone_number}}"/>
      {{if error_customer_phone_number}}
        {{foreach error_customer_phone_number}}
          <div>{{this}}</div>
        {{endfor error_customer_phone_number}}
      {{endif error_customer_phone_number}}
    </div>
    <div>
      {{if showBtn}}
        <button class="btn btn-primary fw-bold float-end m-4" type="submit" name="btnEnviar">{{btnEnviarText}}</button>
        &nbsp;
      {{endif showBtn}}
      <button class="btn btn-warning fw-bold float-end m-4" name="btnCancelar" id="btnCancelar">Cancelar</button>
    </div>
  </form>
</section>
<script>
  document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('btnCancelar').addEventListener('click', function(e){
      e.preventDefault();
      e.stopPropagation();
      window.location.href = 'index.php?page=admin-clientes';
    });
  });
</script>
