<section class="spad">
  <div class="container">
    <form action="index.php?page=ashion_infoCustomer" method="post" class="checkout__form">
      <div class="row">
        <div class="col-lg">
          <h5>Mi información</h5>
          <div class="row">
            <input type="hidden" name="customer_id" value="{{customer_id}}" />
            <div class="col-lg-6 col-md-6 col-sm-6">
              <div class="checkout__form__input">
                <p>Nombres <span>*</span></p>
                <input type="text" id="customer_name" name="customer_name" value="{{customer_name}}">
              </div>
              {{if error_customer_name}}
                {{foreach error_customer_name}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor error_customer_name}}
              {{endif error_customer_name}}
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <div class="checkout__form__input">
                <p>Apellidos <span>*</span></p>
                <input type="text" id="customer_lastname" name="customer_lastname" value="{{customer_lastname}}">
              </div>
              {{if error_customer_lastname}}
                {{foreach error_customer_lastname}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor error_customer_lastname}}
              {{endif error_customer_lastname}}
            </div>
            <div class="col-lg-12">
              <div class="checkout__form__input">
                <p>País </p>
                <select name="customer_country" id="customer_country">
                {{foreach arr_customer_country}}
                    <option value="{{value}}" {{selected}}>{{text}}</option>
                {{endfor arr_customer_country}}
                </select>
              </div>
              <div class="checkout__form__input">
                <p>Ciudad </p>
                <input type="text" id="customer_city" name="customer_city" value="{{customer_city}}">
              </div>
              <div class="checkout__form__input">
                <p>Dirección </p>
                <input type="text" id="customer_address" name="customer_address" placeholder="Calle, número de casa" value="{{customer_address}}">
              </div>
              <div class="checkout__form__input">
                <p>Código postal </p>
                <input type="text" id="customer_postal_code" name="customer_postal_code" value="{{customer_postal_code}}">
              </div>
              <div class="checkout__form__input">
                <p>Telefono <span>*</span></p>
                <input type="text" id="customer_phone_number" name="customer_phone_number" value="{{customer_phone_number}}">
              </div>
              {{if error_customer_phone_number}}
                {{foreach error_customer_phone_number}}
                  <div class="error" style="color: red;">{{this}}</div>
                {{endfor error_customer_phone_number}}
              {{endif error_customer_phone_number}}
            </div>
            <button type="submit" name="btnGuardar" class="site-btn">Guardar</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
<!-- Checkout Section End -->