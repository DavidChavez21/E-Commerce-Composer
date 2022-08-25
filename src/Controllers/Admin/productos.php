<?php
namespace Controllers\Admin;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Admin\Productos as DaoProductos;

class Productos extends PrivateController
{
    private $viewData = array();
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
        if($this->isPostBack()){
            $this->insertarProducto();
        }
        $this->viewData['Productos'] = DaoProductos::getAllProducts();
        Renderer::render('admin/productos', $this->viewData);
    }

    private function insertarProducto(){
        if(isset($_POST['btnEnviar'])){

            $product_image_url = $_POST['image'];
            $product_name = $_POST['name'];
            $product_description = $_POST['description'];
            $product_price = $_POST['price'];
            $product_discount = $_POST['discount'];
            $category_id = $_POST['category'];
            $product_status = $_POST['status'];

            DaoProductos::insertProduct(
                $product_image_url,
                $product_name,
                $product_description,
                $product_price,
                $product_discount,
                $category_id,
                $product_status,
            );
        }
    }
}
?>
