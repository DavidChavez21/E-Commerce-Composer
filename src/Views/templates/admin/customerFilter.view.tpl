
<!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800" style="margin-left: 5px;">Clientes</h1>
                    <p class="mb-4" style="margin-left: 5px;" >Buscar cliente para ingresar la venta</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
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
                                            <th>Email</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                             <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    {{foreach Clientes}}
                                        <tr>
                                            <td>{{customer_id}}</td>
                                            <td>{{Nombre}}</td>
                                            <td>{{useremail}}</td>
                                       
                                             <td> <a href="index.php?page=admin_venta&mode=INS&cus_id={{customer_id}}" class="btn btn-primary btn-sm active" role="button" aria-pressed="true" ><i class="fas fa-plus"></i></a> &nbsp;
                                            
                                        </tr>
                                      {{endfor Clientes}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



<script>
    function redirect(id) {
        const idc= id;
       alert(idc);
       window.location.assign("index.php?page=admin_venta&mode=INS&cus_id="+id);
    }
</script>


