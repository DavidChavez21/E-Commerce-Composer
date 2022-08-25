<section class="spad">
  <div class="container">
    <form action="index.php?page=sec_Register" method="post" class="checkout__form">
      <div class="row">
        <div class="col-lg">
          <h5>Registrar Usuario</h5>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
              <div class="checkout__form__input">
                <p>Nombres <span>*</span></p>
                <input type="text" id="txtNombres" name="txtNombres" value="{{txtNombres}}">
              </div>
              {{if errorNombre}}
                {{foreach errorNombre}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorNombre}}
              {{endif errorNombre}}
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <div class="checkout__form__input">
                <p>Apellidos <span>*</span></p>
                <input type="text" id="txtApellidos" name="txtApellidos" value="{{txtApellidos}}">
              </div>
              {{if errorApellido}}
                {{foreach errorApellido}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorApellido}}
              {{endif errorApellido}}
            </div>
            <div class="col-lg-12">
              <div class="checkout__form__input">
                <p>Email <span>*</span></p>
                <input type="email" name="txtEmail" id="txtEmail">
              </div>
              {{if errorEmail}}
                {{foreach errorEmail}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorEmail}}
              {{endif errorEmail}}
              <div class="checkout__form__input">
                <p>Nombre de usuario <span>*</span></p>
                <input type="text" id="txtUsuario" name="txtUsuario">
              </div>
              {{if errorUsuario}}
                {{foreach errorUsuario}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorUsuario}}
              {{endif errorUsuario}}
              <div class="checkout__form__input">
                <p>Contraseña <span>*</span></p>
                <input type="password" id="txtPassword" name="txtPassword">
              </div>
              {{if errorPswd}}
                {{foreach errorPswd}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorPswd}}
              {{endif errorPswd}}
              <div class="checkout__form__input">
                <p>Verificar contraseña <span>*</span></p>
                <input type="password" id="txtPasswordV" name="txtPasswordV">
              </div>
              {{if errorPasswordV}}
                {{foreach errorPasswordV}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorPasswordV}}
              {{endif errorPasswordV}}
              <div class="checkout__form__input">
                <p>País </p>
                <select name="txtPais" id="txtPais">
                  <option value="" disabled selected>Seleccione un país</option>
                  <option value="ESP">España</option>
                  <option value="USA">Estados Unidos</option>
                  <option value="HND">Honduras</option>
                </select>
              </div>
              <div class="checkout__form__input">
                <p>Ciudad </p>
                <input type="text" id="txtCiudad" name="txtCiudad">
              </div>
              <div class="checkout__form__input">
                <p>Dirección </p>
                <input type="text" id="txtDireccion" name="txtDireccion" placeholder="Calle, número de casa">
              </div>
              <div class="checkout__form__input">
                <p>Código postal </p>
                <input type="text" id="txtPostal" name="txtPostal">
              </div>
              <div class="checkout__form__input">
                <p>Telefono <span>*</span></p>
                <input type="text" id="txtTelefono" name="txtTelefono">
              </div>
              {{if errorTelefono}}
                {{foreach errorTelefono}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorTelefono}}
              {{endif errorTelefono}}
            </div>
            <button type="submit" name="btnRegistrar" class="site-btn">Registrar</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
<!-- Checkout Section End -->