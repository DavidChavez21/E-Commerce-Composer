<?php
namespace Controllers\Admin;

use Controllers\PrivateController;
use Dao\Admin\Ventas as DaoVentas; 
use Views\Renderer;

class Venta extends PrivateController
{
    private $viewData= array();
    private $_productVentaStructure = array(
        "product_id" => "",
        "product_name" => "",
        "product_price" => 0,
        "quantity" => 0,
        "inventory_size" => "",
        "inventory_gender" => ""
    );
   private $result= "";
   private $stock= array();
   private $arrModeDesc= array();
   private $status= array();
    
    public function run() :void
    {
        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] !== "PBL"){
                $this->viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $this->viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $this->viewData["logeado"]=false;
            }
        }
        else 
        {
            $this->viewData["logeado"]=true;
        }

        $this->viewData["mode"]="";
        $this->arrModeDesc = array(
            "INS"=>"Nueva",
            "UPD"=>"Editando",
            "DSP"=>"Detalle",
            "DEL"=>"Eliminado"
        );
        $this->viewData["sale_statusArray"]= array();
        $this->status= array(
            array("value" => "CONF", "text" => "Confirmada"),
            array("value" => "PEND", "text" => "Pendiente"),
            array("value" => "CANC", "text" => "Cancelada")
        );

        $this->viewData["sale_statusArray"] = $this->status;
        $this->viewData["showBtnVenta"]= "";
        $this->viewData["showBtnUpdate"]= "";
        $this->viewData["url"]= "";
        $this->viewData["sale_statusExist"]=false;
        $this->viewData["sale_status"]= "";
        $this->viewData["cus_id"]= "";
        $this->viewData["idSale"]= "";
        $this->viewData["readonly"]= false;
        $this->viewData["DSP"]= false;
        if($this->isPostBack()) {
            $this->procesarPost();
        }
       
        if(!$this->isPostBack()) {

           $this->procesarGet();

        }
       
        Renderer::render('admin/venta', $this->viewData);
    }


    public function agregarProductoSession($product_id,$product_price,$product_name, $quantity, $inventory_size, $inventory_gender)
    {
        $error= false;
        $product = $this->_productVentaStructure;
        $product["product_id"] = $product_id;
        $product["product_name"] = $product_name;
        $product["product_price"] = $product_price;
        $product["quantity"] = $quantity;
        $product["inventory_size"] = $inventory_size;
        $product["inventory_gender"] = $inventory_gender;

        $products = array();
        if (isset($_SESSION["productsVentas"])) {
            $products = $_SESSION["productsVentas"];
        }

        foreach($products as $key=>$value) {
            if($products[$key]["product_id"]===$product_id && $products[$key]["inventory_gender"]===$inventory_gender && $products[$key]["inventory_size"]===$inventory_size ) {
                $products[$key]["quantity"]+= $quantity;
                $_SESSION["productsVentas"] = $products;
                $error= true;
            }
        }

        if(!$error) {
            $products[] = $product;
            $_SESSION["productsVentas"] = $products;
        }
    }

    function getProducts()
{
    $products = array();
    if (isset($_SESSION["productsVentas"])) {
        $products= $_SESSION["productsVentas"];
    }
    return  $products;
}

function deleteProduct($id, $size, $gender) {
    $products = array();
    if (isset($_SESSION["productsVentas"])) {
        $products = $_SESSION["productsVentas"];
    }
    
    foreach($products as $key=>$value) {
        if($products[$key]["product_id"]===$id && $products[$key]["inventory_gender"]===$gender && $products[$key]["inventory_size"]===$size ) {
            unset($products[$key]);
        }
    }
    $_SESSION["productsVentas"]= $products;
    return $products;
}
public function procesarPost() {
    if(isset($_POST["btnAgregarDetalle"])) {
        $customer_id= intval($_POST["cus_id"]);
        $id= intval($_POST["product_idModal"]);
        $name= $_POST["product_nameModal"];
        $price= $_POST["product_priceModal"];
        $quantity= intval($_POST["quantityModal"]);
        $size= $_POST["inventory_sizeModal"];
        $gender= $_POST["inventory_genderModal"];
        $this->stock["result"]=DaoVentas::lessInventory($quantity, $id, $gender, $size);
        
        foreach($this->stock["result"] as $value) {
            if($value == "true") {
               $this->result= "true";
              // error_log(json_decode($this->result));
            }
        }

        if($this->result==="true") {
            $this->agregarProductoSession($id,$price,$name,$quantity,$size,$gender );
            $this->viewData["ProductosSessionVentas"]= $this->getProducts();
           // error_log(json_encode($this->viewData["ProductosSessionVentas"]));
            $this->result= "";
            \Utilities\Site::redirectToWithMsg(
                "index.php?page=admin_venta&mode=INS&cus_id=".$customer_id,
                "Producto Agregado"
            );
        }
            else {
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=admin_venta&mode=INS&cus_id=".$customer_id,
                    "Stock Insuficiente"
                );
            }
        }
     

    if(isset($_POST["btnEliminarProduct"])) {
        $customer_id= intval($_POST["cus_id"]);
        $id= intval($_POST["product_idModalDelete"]);
        $size= $_POST["inventory_sizeModalDelete"];
        $gender= $_POST["inventory_genderModalDelete"];
        $quantity= $_POST["quantity_modalDelete"];
        $this->stock["delete"]=DaoVentas::plusInventory($quantity, $id, $gender, $size);
           
        foreach($this->stock["delete"] as $value) {
            if($value == "true") {
               $this->result= "true";
               error_log(json_decode($this->result));
            }
           // error_log(json_decode($this->result));
        }

        if($this->result==="true") {
            $this->deleteProduct($id, $size, $gender);
            $this->viewData["ProductosSessionVentas"]= $this->getProducts();
          //  error_log(json_encode($this->viewData["ProductosSessionVentas"]));
            \Utilities\Site::redirectToWithMsg(
                "index.php?page=admin_venta&mode=INS&cus_id=".$customer_id,
                "Producto eliminado"
            );
         } else {
            \Utilities\Site::redirectToWithMsg(
                "index.php?page=admin_venta&mode=INS&cus_id=".$customer_id,
                "Error en el inventario, revisar"
            );
         }
    }

    if(isset($_POST["btnUpdateVenta"])  && isset($_POST["sale_id"])) {
        $this->viewData["idSale"]= $_POST["sale_id"];
        $this->viewData["sale_status"]= $_POST["sale_status"];
        $result= DaoVentas::updateStatusVenta($this->viewData["idSale"], $this->viewData["sale_status"]);

        if($result) {
            \Utilities\Site::redirectToWithMsg(
                "index.php?page=admin_ventas",
                "Venta Actualizada Correctamente"
            );
        }
    }
}

public function procesarGet() {
    if (isset($_GET["mode"])) {
        $this->viewData["mode"] = $_GET["mode"];
        if (!isset($this->arrModeDesc[$this->viewData["mode"]])) {
            \Utilities\Site::redirectToWithMsg(
                "index.php?page=admin_ventas",
                "Error al procesar su solicitud"
            );
        }
    }

    if ($this->viewData["mode"] !== "INS" && isset($_GET["idSale"])) {
        $this->viewData["descripcion"]= $this->arrModeDesc[$this->viewData["mode"]];
        $id = intval($_GET["idSale"]);
        $this->viewData["idSale"]= $id;
        $this->viewData["ProductosDetail"]= DaoVentas::getDetalleVentaBySale($id);
        $this->viewData["Sales"]= DaoVentas::getVentaById($id);
        $this->viewData["cus_id"]= $this->viewData["Sales"]["cus_id"];
        $this->viewData["sale_status"]= $this->viewData["Sales"]["sale_status"];
        $this->viewData["sale_statusExist"]=true;
      //  $this->viewData["sale_status"]= $sale["sale_status"];
       // $this->viewData["cus_id"]= $sale["cus_id"];
        $this->viewData["showBtnUpdate"]= "Actualizar";
        $this->viewData["url"]= "index.php?page=admin_venta";
        $this->viewData["sale_statusArray"]
        = \Utilities\ArrUtils::objectArrToOptionsArray(
            $this->status,
            'value',
            'text',
            'value',
            $this->viewData["sale_status"]
        );

        if($this->viewData["mode"]==="DSP") {
            $this->viewData["showBtnUpdate"]= "";
            $this->viewData["readonly"]= true;
            $this->viewData["DSP"]= true;
        }


    } else {
        if($this->viewData["mode"]=== "INS" && isset($_GET["cus_id"])) {
            $this->viewData["descripcion"]= $this->arrModeDesc[$this->viewData["mode"]];
            $this->viewData["cus_id"]= $_GET["cus_id"];
            $this->viewData["showBtnVenta"]= "Guardar";
            $this->viewData["url"]= "index.php?page=checkout_checkout";
            $this->viewData["Productos"]= DaoVentas::getAllProductsI();
            $this->viewData["ProductosSessionVentas"]= $this->getProducts();
        }
    }
}
}
?>
