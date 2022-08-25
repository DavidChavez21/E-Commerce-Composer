<h1 class="h3 mb-2 text-gray-800" >Productos</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Price</th>
                        <th>Estado</th>
                        <th>ID Categoria</th>
                        <th> <p class="buttonPlus fas fa-plus" style="cursor: pointer; color: white; background-color: #007bff; padding: 15px 10px 15px 20px; font-size: 20px;" data-target="#ventanaModal" data-toggle="modal"></td> &nbsp;</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Price</th>
                        <th>Estado</th>
                        <th>ID Categoria</th>
                        <th> <p class="buttonPlus fas fa-plus" style="cursor: pointer; color: white; background-color: #007bff; padding: 15px 10px 15px 20px; font-size: 20px;" data-target="#ventanaModal" data-toggle="modal"></td> &nbsp;</th>
                    </tr>
                </tfoot>
                <tbody>
                {{foreach Productos}}
                    <tr>
                        <td>{{product_id}}</td>
                        <td>{{product_name}}</td>
                        <td>{{product_price}}</td>
                        <td>{{product_status}}</td>
                        <td>{{category_id}}</td>
                        <td>
                        <a href="index.php?page=admin_producto&mode=UPD&product_id={{product_id}}"><i class="fas fa-edit btn btn-primary"></i></a>&NonBreakingSpace;
                        <a href="index.php?page=admin_producto&mode=DSP&product_id={{product_id}}"><i class="fas fa-eye btn btn-primary"></i></a>&NonBreakingSpace;
                        <a href="index.php?page=admin_producto&mode=DEL&product_id={{product_id}}"><i class="fas fa-trash btn btn-primary"></i></a>
                        </td>
                    </tr>
                {{endfor Productos}}                                        
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="tituloVentana" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 id="tituloVenta">Agregar Nuevo Producto</h5>
            <button class="close" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div>
                    <form action="index.php?page=admin_productos" method="post" >     
                        <div class="form-group">
                            <label for="image">Imagen: </label>
                            <input type="text" class="form-control" id="image" name="image" required value={{image}}>
                        </div>                   
                        <div class="form-group">
                            <label for="name">Nombre: </label>
                            <input type="text" class="form-control" id="name" name="name" required value={{name}}>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripcion: </label>
                            <input type="text" class="form-control" id="description" name="description" required value={{description}}>
                        </div>
                        <div class="form-group">
                            <label for="price">Precio: </label>
                            <input type="number" class="form-control" id="price" name="price" required value={{price}}>
                        </div>

                        <div class="form-group">
                            <label for="discount">Descuento: </label>
                            <input type="number" class="form-control" id="discount" name="discount" required value={{discount}}>
                        </div>
                        <div class="form-group">
                            <label for="category">Categoria: </label>
                            <select required class="form-control" name="category" id="category">
                                    <option name="category" value="1">Coats</option>
                                    <option name="category" value="2">Jackets</option>
                                    <option name="category" value="3">Dresses</option>
                                    <option name="category" value="4">Shirts</option>
                                    <option name="category" value="5">T-Shirts</option>
                                    <option name="category" value="6">Jeans</option>
                                    <option name="category" value="7">Blouses</option>
                                    <option name="category" value="8">Shoes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Estado: </label>
                            <select class="form-control" name="status" id="status">
                                   <option name="status" value="ACT">Activo</option>
                                   <option name="status" value="INA">Inactivo</option>
                            </select>
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