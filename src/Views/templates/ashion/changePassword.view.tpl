<section class="spad">
  <div class="container">
    <form action="index.php?page=ashion_changePassword" method="post" class="checkout__form">
      <div class="row">
        <div class="col-lg">
          <h5>Cambiar contraseña</h5>
          <div class="row">
            <input type="hidden" name="usercod" value="{{usercod}}" />
            <div class="col-lg-12">
              <div class="checkout__form__input">
                <p>Contraseña <span>*</span></p>
                <input type="password" id="userpaswd" name="userpaswd">
              </div>
              {{if errorPswd}}
                {{foreach errorPswd}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorPswd}}
              {{endif errorPswd}}
              <div class="checkout__form__input">
                <p>Verificar contraseña <span>*</span></p>
                <input type="password" id="userpaswdV" name="userpaswdV">
              </div>
              {{if errorPasswordV}}
                {{foreach errorPasswordV}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor errorPasswordV}}
              {{endif errorPasswordV}}
            </div>
            <button type="submit" name="btnGuardar" class="site-btn">Guardar</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
<!-- Checkout Section End -->