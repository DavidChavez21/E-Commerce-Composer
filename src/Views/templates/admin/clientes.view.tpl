<h1 class="h3 m-4 text-gray-800" >Listado de clientes</h1>

<!-- DataTales Example -->
<div class="card shadow m-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Clientes</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Opciones</th>
                    </tr>
                </tfoot>
                <tbody>
                    {{foreach clientes}}
                    <tr>
                        <td>{{customer_id}}</td>
                        <td> <a href="index.php?page=admin-cliente&mode=DSP&id={{customer_id}}">{{customer_name}}</a></td>
                        <td>{{customer_lastname}}</td>
                        <td>
                        <a href="index.php?page=admin-cliente&mode=UPD&id={{customer_id}}">Editar</a>
                        &NonBreakingSpace;
                        <a href="index.php?page=admin-cliente&mode=DEL&id={{customer_id}}">Eliminar</a>
                        </td>
                    </tr>
                    {{endfor clientes}}
                </tbody>
            </table>
        </div>
    </div>
</div>