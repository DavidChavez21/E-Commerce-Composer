<section class="spad">
  <div class="container">
    <h3 class="mb-4">Listado de compras</h3>
    <table class="table table-bordered">
    <thead>
        <tr>
        <th scope="col">No</th>
        <th scope="col">Fecha</th>
        <th scope="col">ISV</th>
        <th scope="col">Subtotal</th>
        <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        {{foreach compras}}
        <tr>
        <th>{{sale_id}}</th>
        <td><a href="index.php?page=ashion-detalleCompra&id={{sale_id}}">{{sale_date}}</a></td>
        <td>{{sale_isv}}</td>
        <td>{{sale_subtotal}}</td>
        <td>{{total}}</td>
        </tr>
        {{endfor compras}}
    </tbody>
    </table>
  </div>
</section>