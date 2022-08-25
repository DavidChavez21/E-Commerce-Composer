<section class="spad">
  <div class="container">
    <h3 class="mb-4">Detalle de la compra</h3>
        <h5 class="mb-4">No. {{sale_id}}</h5>
        <h5 class="mb-4">Fecha: {{sale_date}}</h5>
    <table class="table table-bordered">
    <thead>
        <tr>
        <th scope="col">ID</th>
        <th scope="col">Producto</th>
        <th scope="col">Precio</th>
        <th scope="col">Cantidad</th>
        <th scope="col">Importe</th>
        </tr>
    </thead>
    <tbody>
        {{foreach detalle}}
        <tr>
        <th>{{product_id}}</th>
        <td>{{product_name}}</td>
        <td>{{sale_price}}</td>
        <td>{{sale_quantity}}</td>
        <td>{{importe}}</td>
        </tr>
        {{endfor detalle}}
    </tbody>
    </table>
    <div class="row">
        <h5 class="m-4">ISV: {{sale_isv}}</h5>
        <h5 class="m-4">Subtotal: {{sale_subtotal}}</h5>
        <h5 class="m-4">Total: {{total}}</h5>
    </div>
  </div>
</section>