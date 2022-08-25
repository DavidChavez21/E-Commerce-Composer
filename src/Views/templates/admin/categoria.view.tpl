
<div style="width: 80%; margin: 10%;">
    <h1>{{titulo}}</h1>
    <form action="index.php?page=admin_categoria&mode={{mode}}&category_id={{category_id}}" method="POST">
        <div class="form-group">
            <label for="category_id" hidden>ID: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="hidden" class="form-control" id="category_id" name="category_id" value="{{category_id}}" >
        </div>

        <div class="form-group">
            <label for="mode" hidden>Mode: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="hidden" class="form-control" id="mode" name="mode" value="{{mode}}">
        </div>

        <div class="form-group">
            <label for="category_name">Nombre: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="text" class="form-control" id="category_name" name="category_name" value="{{category_name}}" required>
        </div>

        <div class="form-group">
            <label for="category_image_url">Imagen: </label>
            <input {{if readonly}}readonly{{endif readonly}} type="text" class="form-control" id="category_image_url" name="category_image_url" value="{{category_image_url}}" required>
        </div>
        <button class="btn btn-success" type="submit" name="btnGuardar">{{btnGuardar}}</button>
    </form>
</div>