window.onload = function() {

    //evitar que se envie el formulario

    var form = document.getElementById("formInventario");
    var IdProducto = document.getElementById("product_id");
    var SizeProduct = document.getElementById("inventory_size");
    var ProductStock = document.getElementById("product_stock");

    //Validar en evento keypress que solo acepte numeros
    IdProducto.addEventListener("keypress", function(event) {
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
            IdProducto.focus();
            IdProducto.style.boxShadow = "0 0 10px red";
            alert("Solo se aceptan numeros");
        }
        else
        {
            IdProducto.style.boxShadow = "none";
        }
    });

    //Validar que en la talla solo acepte letras Mayusculas
    SizeProduct.addEventListener("keypress", function(event) {
        if (event.which < 65 || event.which > 90) {
            event.preventDefault();
            SizeProduct.focus();
            SizeProduct.style.boxShadow = "0 0 10px red";
            alert("Solo se aceptan letras Mayusculas");
        }
        else
        {
            SizeProduct.style.boxShadow = "none";
        }
    });

    //Validar que en el stock solo acepte numeros
    ProductStock.addEventListener("keypress", function(event) {
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
            ProductStock.focus();
            ProductStock.style.boxShadow = "0 0 10px red";
            alert("Solo se aceptan numeros");
        }
        else
        {
            ProductStock.style.boxShadow = "none";
        }
    });



    form.addEventListener("submit", function(e) {
        e.preventDefault();
        
        if(document.getElementById("product_id").value == '')
        {
            Swal.fire({
                title: 'Error',
                text: 'Debe Ingresar el ID del Producto',
                type: 'error',
                icon: 'error',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.value) {
                    document.getElementById("product_id").focus();
                }
            });
        }
        else if(document.getElementById("inventory_size").value == '')
        {
            Swal.fire({
                title: 'Error',
                text: 'Debe Ingresar la Talla del Producto',
                type: 'error',
                icon: 'error',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.value) {
                    document.getElementById("inventory_size").focus();
                }
            });
        }//Validar que las tallas no sean diferentes de XS, S, M, L, XL, XXL
        else if(document.getElementById("inventory_size").value != 'XS' && document.getElementById("inventory_size").value != 'S' && document.getElementById("inventory_size").value != 'M' && document.getElementById("inventory_size").value != 'L' && document.getElementById("inventory_size").value != 'XL' && document.getElementById("inventory_size").value != 'XXL')
        {
            Swal.fire({
                title: 'Error',
                text: 'La Talla del Producto Solo puede ser XS, S, M, L, XL o XXL',
                type: 'error',
                icon: 'error',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.value) {
                    document.getElementById("inventory_size").focus();
                }
            });
        }
        else if(document.getElementById("product_stock").value == '')
        {
            Swal.fire({
                title: 'Error',
                text: 'Debe Ingresar el Stock del Producto',
                type: 'error',
                icon: 'error',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.value) {
                    document.getElementById("product_stock").focus();
                }
            });
        }//Validar en la base de datos que el producto exista
        else if(document.getElementById("product_id").value != '' && document.getElementById("inventory_size").value != '' && document.getElementById("product_stock").value != '')
        {
            if(document.getElementById('mode').value == "INS")
            {
                ajax = new XMLHttpRequest();

                ajax.open("GET", "/E-commerceProject/src/Controllers/Admin/verificarProductSize.php?product=" + document.getElementById("product_id").value + "&size="+document.getElementById("inventory_size").value + "&gender=" + document.getElementById("inventory_gender").value , true);
                ajax.send();
                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if(this.responseText == '1')
                        {
                            Swal.fire({
                                title: 'Error',
                                text: 'El Producto con la Talla y el genero especificados ya existe',
                                type: 'error',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    document.getElementById("product_id").focus();
                                }
                            });
                        }
                        else
                        {
                            form.submit();
                        }
                    }
                }
            }
            else{
                form.submit();
            }
        }
        

    });

}