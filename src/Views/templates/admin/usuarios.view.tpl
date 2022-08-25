
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Usuarios</h6>
</div>
<div class="card-body">
  <div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
           <th>Email</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Tipo</th>
            <th><a href="index.php?page=admin_Usuario&mode=INS">Nuevo</a></th>
        </tr>
      </thead>
      <tbody>
      {{foreach Usuarios}}
        <tr>
          <td>{{usercod}}</td>
          <td>{{useremail}}</td>
          <td> <a href="index.php?page=admin-Usuario&mode=DSP&id={{usercod}}">{{username}}</a></td>
          <td>{{userest}}</td>
          <td>{{usertipo}}</td>
          <td>
            <a href="index.php?page=admin_Usuario&mode=UPD&id={{usercod}}">Editar</a>
            &NonBreakingSpace;
            <a href="index.php?page=admin_Usuario&mode=DEL&id={{usercod}}">Eliminar</a>
          </td>
        </tr>
        {{endfor Usuarios}}
    </table>
  </div>
   </div>
 </div>