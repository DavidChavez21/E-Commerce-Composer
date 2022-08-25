<section class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="card my-5 w-100">
    <div class="card-header">
      <h3 class="text-center">{{mode_dsc}}</h3>
    </div>
    
    <div class="card-body"> 
      <form class="form" method="post" action="index.php?page=admin_funcion&mode={{mode}}&fncod={{fncod}}">
        <input type="hidden" class="form-control" id="mode" name="mode" value="{{mode}}"/>
        <div class="form-group col-md-4">
          <label for="fncod">Código</label>
          <input  type="text" class="form-control" name="fncod" value="{{fncod}}" {{if readonly}}disabled{{endif readonly}}/>
        </div>


        <div class="form-group col-md-4">
          <label for="fnddc">Descripción</label>
          <input type="text" class="form-control" {{readonly}} id="fnddc" name="fnddc" value="{{fnddc}}" maxlength="45" placeholder="Ingrese la descripción del rol">
        </div>

 
        <div class="form-group col-md-4">
          <label for="fnest">Estado</label>
          <br/>
          <select class="form-control" id="fnest" name="fnest" {{if readonly}}disabled{{endif readonly}}>
              <option value="ACT" {{fnest_ACT}}>Activo</option>
              <option value="INA" {{fnest_INA}}>Inactivo</option>
          </select>
        </div>

        <div class="form-group col-md-4">
            <section class="md:flex md:items-center mb-6">
        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for ="fntyp"> Tipo</label>
        {{if readonly}}
      <input type="hidden" id="funtypdummy" name="fntyp" value=""/>
      {{endif readonly}}
      <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" id="fntyp" name="fntyp" {{if readonly}}disabled{{endif readonly}}>
        <option value="CRT" {{fntyp_CRT}}>CRT</option>
        <option value="PCR" {{fntyp_PCR}}>PCR</option>
      </select>
        </div>

        {{if hasErrors}}
        <section>
            <ul>
              {{foreach aErrors}}
                <li class="text-danger my-2">{{this}}</li>
              {{endfor aErrors}}
            </ul>
        </section>
        {{endif hasErrors}}

        <button type="button" class="btn btn-warning mt-2 ml-3 mr-2" id="btnCancelar" name="btnCancelar">Cancelar</button>
        <button type="submit" class="btn btn-primary mt-2 mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
        
      </form>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function(){
      document.getElementById("btnCancelar").addEventListener("click", function(e){
        e.preventDefault();
        e.stopPropagation();
        window.location.assign("index.php?page=admin_roles");
      });
  });
</script>

<script>
  $(document).ready(function(){
    $(".mul-select").select2({
      placeholder: " Seleccione las funciones",
      tags: true,
      tokenSeparators: ['/',',',';'," "] 
    });
  })
</script>