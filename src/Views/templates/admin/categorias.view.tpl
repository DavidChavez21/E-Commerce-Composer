
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Categorias</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Image Url</th>
                        <th> <p class="buttonPlus fas fa-plus" style="cursor: pointer; color: white; background-color: #007bff; padding: 15px 10px 15px 20px; font-size: 20px;" data-target="#ventanaModal" data-toggle="modal"></td> &nbsp;</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Image Url</th>
                        <th> <p class="buttonPlus fas fa-plus" style="cursor: pointer; color: white; background-color: #007bff; padding: 15px 10px 15px 20px; font-size: 20px;" data-target="#ventanaModal" data-toggle="modal"></td> &nbsp;</th>
                    </tr>
                </tfoot>
                <tbody>
                {{foreach Categorias}}
                    <tr>
                        <td>{{category_id}}</td>
                        <td>{{category_name}}</td>
                        <td>{{category_image_url}}</td>
                        <td>
                        <a href="index.php?page=admin_categoria&mode=UPD&category_id={{category_id}}"><i class="fas fa-edit btn btn-primary"></i></a>&NonBreakingSpace;
                        <a href="index.php?page=admin_categoria&mode=DSP&category_id={{category_id}}"><i class="fas fa-eye btn btn-primary"></i></a>&NonBreakingSpace;
                        <a href="index.php?page=admin_categoria&mode=DEL&category_id={{category_id}}"><i class="fas fa-trash btn btn-primary"></i></a>
                        </td>
                    </tr>
                {{endfor Categorias}}                                        
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="tituloVentana" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 id="tituloVenta">Agregar Nueva Categoria</h5>
            <button class="close" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div>
                    <form action="index.php?page=admin_categorias" method="post" >                        
                        <div class="form-group">
                            <label for="name">Nombre: </label>
                            <input type="text" class="form-control" id="name" name="name" required value={{name}}>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagen: </label>
                            <input type="text" class="form-control" id="image" name="image" required value={{image}}>
                        </div>
                        <button class="btn btn-success" type="submit" id="btnEnviar" name="btnEnviar">Agregar</button>
                    </form>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
</script>