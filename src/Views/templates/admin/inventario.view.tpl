<h1 class="text-center">{{mode_desc}}</h1>
<br>
<section style="margin-left: 420px;">
    <form class="form" action="index.php?page=admin_inventario" method="POST" id="formInventario">
        <input type="hidden" name="mode" value="{{mode}}" id="mode" />
        <input {{if readonly}}readonly{{endif readonly}} type="hidden" name="inventory_id" id="inventory_id"
            placeholder="inventory_id" value="{{inventory_id}}" />
        <div class="form-group col-sm-6">
            <label for="product_id">ID Producto: </label>
            <input {{if readonlyUPD}}readonly{{endif readonlyUPD}} type="text" class="form-control" name="product_id" id="product_id"
                placeholder="ID Producto" value="{{product_id}}" />
        </div>
        <div class="form-group col-sm-6">
            <label for="inventory_size">Talla: </label>
            <input {{if readonlyUPD}}readonly{{endif readonlyUPD}} type="text" class="form-control" name="inventory_size" id="inventory_size"
                placeholder="Talla" value="{{inventory_size}}" />
        </div>
        <div class="form-group col-sm-6">
            <label for="inventory_gender">Genero: </label>
            <br>
            <select {{if readonlyUPD}}disabled{{endif readonlyUPD}} class="form-control" name="inventory_gender" id="inventory_gender">
                <option value="Male">Masculino</option>
                <option value="Female">Femenino</option>
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="product_stock">Stock del Producto: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="text" class="form-control" name="product_stock" id="product_stock"
                placeholder="Stock del Producto" value="{{product_stock}}" />
        </div>
        <div class="form-group col-sm-6">
            {{if showBtn}}
            <button type="submit" class="btn btn-primary" name="btnEnviar">{{btnEnviarText}}</button>
            &nbsp;&nbsp;&nbsp;
            {{endif showBtn}}
            &nbsp;&nbsp;&nbsp;
            <button name="btnCancelar" class="btn btn-danger" id="btnCancelar">Cancelar</button>
        </div>
    </form>
</section>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('btnCancelar').addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      window.location.href = 'index.php?page=admin_inventarios';
    });
  });
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/{{BASE_DIR}}/public/js/scriptInventario.js"></script>