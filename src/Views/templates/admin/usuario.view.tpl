<section class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="card my-5 w-100">
    <div class="card-header">
      <h3 class="text-center">{{mode_dsc}}</h3>
    </div>
    <div class="card-body"> 
      <form class="form" method="post" action="index.php?page=admin_usuario&mode={{mode}}&usercod={{usercod}}">
        <input type="hidden" name="mode" value="{{mode}}"/>
        {{if notDisplayIns}}
        <div class="form-group col-md-2">
          <label for="usercod">Código</label>
          <input type="hidden" class="form-control" id="usercod" name="usercod" value="{{usercod}}"/>
          <input readonly type="text" class="form-control" name="UsaurioIdDummy" value="{{usercod}}"/>
        </div>
        {{endif notDisplayIns}}
  
        <div class="form-group col-md-10">
          <label for="useremail">Correo Electrónico</label>
          <input type="email" class="form-control" {{readonly}} id="useremail" name="useremail" value="{{useremail}}" maxlength = "80" placeholder="Ingrese su correo">
        </div>

        <div class="form-group col-md-10">
          <label for="username">Nombre Completo</label>
          <input type="text" class="form-control" {{readonly}} id="username" name="username" value="{{username}}" maxlength="80" placeholder="Ingrese su nombre completo">
        </div>

        <div class="form-group col-md-10">
          <label for="userpswd">Contraseña</label>
          <input type="password" class="form-control" {{readonly}} id="userpswd" name="userpswd" value="{{userpswd}}" maxlength="20" placeholder="Ingrese su contraseña">
        </div>

        {{if allInfoDisplayed}}
        <div class="form-group col-md-10">
            <label for="userfching">Fecha de ingreso del usuario</label>
            <br/>
            <input type="text" readonly class="form-control" id="userfching" name="userfching" value="{{userfching}}" maxlength="128"/>
        </div>
        {{endif allInfoDisplayed}}
    
        {{if allInfoDisplayed}}
        <div class="form-group col-md-10">
            <label for="userpswdest">Estado de la contraseña</label>
            <br/>
            <input type="text" readonly class="form-control" id="userpswdest" name="userpswdest" value="{{userpswdest}}" maxlength="5"/>
        </div>
        {{endif allInfoDisplayed}}
    
        {{if allInfoDisplayed}}
        <div class="form-group col-md-10">
            <label for="userpswdexp">Fecha de vencimiento de la contraseña</label>
            <br/>
            <input type="text" readonly class="form-control" id="userpswdexp" name="userpswdexp" value="{{userpswdexp}}" maxlength="128"/>
        </div>
        {{endif allInfoDisplayed}}

       
          <div class="form-group col-md-4">
            <label for="userest">Estado del usuario</label>
            <br/>
            <select class="form-control" id="userest" name="userest" {{if readonly}}disabled{{endif readonly}}>
                <option value="ACT" {{userest_ACT}}>Activo</option>
                <option value="INA" {{userest_INA}}>Inactivo</option>
            </select>
          </div>
        

        {{if allInfoDisplayed}}
        <div class="form-group col-md-10">
          <label for="userpswdchg">Fecha en que se cambio la contraseña por última vez</label>
          <br/>
          <input type="text" readonly class="form-control" id="userpswdchg" name="userpswdchg" value="{{userpswdchg}}" maxlength="128"/>
        </div>
        {{endif allInfoDisplayed}}
        
        <div class="form-group col-md-4">
          <label for="usertipo">Tipo de usuario</label>
          <br/>
          <select class="form-control" id="usertipo" name="usertipo" {{if readonly}}disabled{{endif readonly}}>
              <option value="ADM" {{usertipo_ADM}}>Administrador</option>
              <option value="AUD" {{usertipo_AUD}}>Auditor</option>
              <option value="PBL" {{usertipo_PBL}}>Público</option>
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
        window.location.assign("index.php?page=admin_usuarios");
      });
  });
  $('select').selectpicker();
</script>