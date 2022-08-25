<section class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="card my-5 w-100">
    <div class="card-header">
      <h3 class="text-center">{{mode_dsc}}</h3>
    </div>
    <div class="card-body"> 
      <form class="form" method="post" action="index.php?page=admin_rol&mode={{mode}}&rolescod={{rolescod}}">
        <input type="hidden" name="mode" value="{{mode}}"/>
        <div class="form-group col-md-4">
          <label for="rolescod">Código</label>
          <input type="text" class="form-control" id="rolescod" name="rolescod" value="{{rolescod}}"/>
          
        </div>


        <div class="form-group col-md-4">
          <label for="rolesdsc">Descripción</label>
          <input type="text" class="form-control" {{readonly}} id="rolesdsc" name="rolesdsc" value="{{rolesdsc}}" maxlength="45" placeholder="Ingrese la descripción del rol">
        </div>

 
        <div class="form-group col-md-4">
          <label for="rolesest">Estado</label>
          <br/>
          <select class="form-control" id="rolesest" name="rolesest" {{if readonly}}disabled{{endif readonly}}>
              <option value="ACT" {{rolesest_ACT}}>Activo</option>
              <option value="INA" {{rolesest_INA}}>Inactivo</option>
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