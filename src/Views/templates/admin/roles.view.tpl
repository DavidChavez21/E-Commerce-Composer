<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Roles</h6>
</div>
<div class="card-body">
    <div class="table-responsive"> 
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Id</th>                
                    <th>Descripcion</th>
                    <th>Estado</th>
                    <th><a href="index.php?page=admin_rol&mode=INS">NUEVO REGISTRO</a></th>
                </tr>
            </thead>
            <tbody>
                {{foreach Roles}}
                <tr>
                    <td>{{rolescod}}</td>
                    <td>{{rolesdsc}}</td>
                    <td>{{rolesest}}</td>
                    <td>
                        <a href="index.php?page=admin_rol&mode=UPD&Id={{rolescod}}">Editar</a>
                        <a href="index.php?page=admin_rol&mode=DEL&Id={{rolescod}}">&nbsp;Eliminar</a>
                    </td>
                </tr>
                {{endfor Roles}}
            </tbody>
    </table>
    </div>
</div>
