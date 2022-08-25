<!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800" style="margin-left: 5px;">Inventario</h1>
                    

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Inventario</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Producto ID</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Genero</th>
                                            <th>Stock</th>
                                            <th>Accciones</th>   
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Producto ID</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Genero</th>
                                            <th>Stock</th>
                                            <th>Accciones</th>   
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    {{foreach Inventarios}}
                                        <tr>
                                            <td>{{Inventario}}</td>
                                            <td>{{Producto}}</td>
                                            <td>{{Talla}}</td>
                                            <td>{{Genero}}</td>
                                            <td>{{Stock}}</td>
                                            <td><a href="index.php?page=admin_inventario&mode=UPD&id={{Inventario}}"><i st class="fas fa-pen btn btn-primary"></i></a>&NonBreakingSpace;
                                             <a href="index.php?page=admin_inventario&mode=DEL&id={{Inventario}}"><i class="fas fa-trash-alt btn btn-primary"></i></a>
                                            </td>&NonBreakingSpace;
                                             
                                        </tr>
                                      {{endfor Inventarios}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>