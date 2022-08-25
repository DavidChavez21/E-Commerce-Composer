<div class="container-fluid">
    <h3>Orden Denegada</h3>
    <p>Su orden fue denegada </p>
</div>

<script>
        swal({
            title: "Orden Denegada",
            text: "Su orden fue denegada ",
            icon: "error",
            button: "Aceptar",
            dangerMode: false,
        }).then( () => {
            window.location.assign('index.php?page=index');
        });
</script>