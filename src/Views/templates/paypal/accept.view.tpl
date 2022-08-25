<div class="container-fluid">
    <h3>Orden Aceptada</h3>
    <p>Su orden fue procesada con exito </p>
</div>

{{if admin}}
<script>
        swal({
            title: "Orden Aceptada",
            text: "Su orden fue procesada con exito \n Recibira un correo de confirmacion con los detalles de su orden",
            icon: "success",
            button: "Aceptar",
            dangerMode: false,
        }).then( () => {
            window.location.assign('index.php?page=admin_admin');
        });
</script>
{{endif admin}}



{{if cliente}}
<script>
        swal({
            title: "Orden Aceptada",
            text: "Su orden fue procesada con exito \n Recibira un correo de confirmacion con los detalles de su orden",
            icon: "success",
            button: "Aceptar",
            dangerMode: false,
        }).then( () => {
            window.location.assign('index.php?page=index');
        });
</script>
{{endif cliente}}

