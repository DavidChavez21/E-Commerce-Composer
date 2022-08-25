
<div style="width: 80%; margin: 10%;">
    <h1>{{titulo}}</h1>
    
    <form action="index.php?page=admin_producto&mode{{mode}}&product_id={{product_id}}" method="post">
        <div class="form-group">
            <label for="product_id" hidden>ID Producto: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="hidden" class="form-control" id="product_id" name="product_id" value="{{product_id}}">
        </div>
        <div class="form-group">
            <label for="mode" hidden>Mode: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="hidden" class="form-control" id="mode" name="mode" value="{{mode}}">
        </div>
      
        <div>
            <label for="product_image_url">URL de la Imagen: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="text" class="form-control" id="product_image_url" name="product_image_url"value="{{product_image_url}}"/>
        </div>

        <div class="form-group">
            <label for="product_name">Nombre: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="text" class="form-control" id="product_name" name="product_name" value="{{product_name}}">
        </div>

        <div class="form-group">
            <label for="product_description">Descripcion: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="text" class="form-control" id="product_description" name="product_description" value="{{product_description}}" />
        </div>

        <div class="form-group">
            <label for="product_price">Precio: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="text" class="form-control" id="product_price" name="product_price" value="{{product_price}}"/>
        </div>

        <div class="form-group">
            <label for="product_discount">Descuento: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="text" class="form-control" id="product_discount" name="product_discount" value="{{product_discount}}"/>
        </div>

        <div class="form-group">
            <label for="category_id">Categoria: </label>
            <select class="form-control" name="category_id" id="category_id" {{if readonly}}readonly disabled{{endif readonly}}>
                    <option name="category_id" value="1">Coats</option>
                    <option name="category_id" value="2">Jackets</option>
                    <option name="category_id" value="3">Dresses</option>
                    <option name="category_id" value="4">Shirts</option>
                    <option name="category_id" value="5">T-Shirts</option>
                    <option name="category_id" value="6">Jeans</option>
                    <option name="category_id" value="7">Blouses</option>
                    <option name="category_id" value="8">Shoes</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="product_status">Estado: </label>
            <select class="form-control" name="product_status" id="product_status" {{if readonly}}readonly disabled{{endif readonly}}>
                    <option name="product_status" value="ACT">Activo</option>
                    <option name="product_status" value="INA">Inactivo</option>
            </select>
        </div>
        

        <button class="btn btn-success" type="submit" id="btnGuardar" name="btnGuardar">{{btnGuardar}}</button>
    </form>
    
</div>

 <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
</script>