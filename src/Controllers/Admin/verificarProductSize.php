<?php 

//CONEXION CON PDO
$conexion = new PDO("mysql:host=34.224.30.50;dbname=tienda_online_db", "negociosWeb", "negociosWeb2022");

$product_id = $_REQUEST["product"];
$size = $_REQUEST["size"];

//VERIFICAR SI EXISTE EL PRODUCTO Y EL TAMAÑO
$sql = "SELECT * FROM inventory WHERE product_id = '$product_id' AND inventory_size = '$size'";
$result = $conexion->query($sql);
$row = $result->fetch();
if($row){
    echo "1";
}
else{
    echo "0";
}



?>