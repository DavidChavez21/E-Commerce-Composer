<?php
namespace Controllers\Admin;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Admin\Productos;

class Producto extends PrivateController
{
    private $viewData = array();
    private $arrModeDesc = array();

    public function run():void
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
        
        $this->init();
            
        if($this->isPostBack()){
            $this->acciones();
        }else{
            $this->getProduct();
        }

        $this->processView();
        Renderer::render('admin/producto', $this->viewData);
    }

    private function getProduct(){
        if (isset($_GET["mode"])) {
            $this->viewData["mode"] = $_GET["mode"];
            if (!isset($this->arrModeDesc[$this->viewData["mode"]])) {
                error_log('Error: (Producto) Mode solicitado no existe.');
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=admin_productos",
                    "No se puede procesar su solicitud!"
                );
            }
        }
        if($this->viewData["mode"] !== "INS" && isset($_GET['product_id'])){
            $this->viewData['product_id'] = intval($_GET['product_id']);
            $tmpCategoria = Productos::getProductById($this->viewData['product_id']);
            error_log(json_encode($tmpCategoria));
            \Utilities\ArrUtils::mergeFullArrayTo($tmpCategoria, $this->viewData);
        }
    }

    private function init(){
        $this->viewData = array();

        $this->viewData["mode"] = "";
        $this->viewData['product_id'] = '';
        $this->viewData["product_image_url"] = "";
        $this->viewData["product_name"] = "";
        $this->viewData["product_description"] = "";
        $this->viewData["product_price"] = "";
        $this->viewData["product_discount"] = "";
        $this->viewData["category_id"] = "";
        $this->viewData["product_status"] = "";

        $this->viewData["titulo"] = "";
        $this->viewData["readonly"] = false;
        $this->arrModeDesc = array(
            "INS"=>"Agregar Nueva Producto",
            "UPD"=>"Editando Producto #%d",
            "DSP"=>"Detalle de la Producto #%d",
            "DEL"=>"Eliminado Producto #%d"
        );
    }
    
    private function acciones(){
        \Utilities\ArrUtils::mergeFullArrayTo($_POST, $this->viewData);
        switch($this->viewData["mode"]) {
        case 'INS':
            $result = Productos::insertProduct(
                $this->viewData["product_image_url"],
                $this->viewData["product_name"],
                $this->viewData["product_description"],
                $this->viewData["product_price"] ,
                $this->viewData["product_discount"],
                $this->viewData["category_id"],
                $this->viewData["product_status"],
            );
            if($result){
                header('Location: index.php?page=admin_productos');
                exit;
            }
        case 'UPD':
            $result = Productos::updateProduct(
                $this->viewData["product_id"],
                $this->viewData["product_image_url"],
                $this->viewData["product_name"],
                $this->viewData["product_description"],
                $this->viewData["product_price"],
                $this->viewData["product_discount"],
                $this->viewData["category_id"],
                $this->viewData["product_status"],
            );
            if($result){
                header('Location: index.php?page=admin_productos');
                exit;
            }
        case 'DEL':
            $result = Productos::deleteProduct(
                intval($this->viewData["product_id"])
            );
            if($result){
                header('Location: index.php?page=admin_productos');
                exit;
            }
            break;
        default:
            header('Location: index.php?page=admin_productos');
            break;
        }
    }

    private function processView()
    {
        if ($this->viewData["mode"] === "INS") {
            $this->viewData["titulo"]  = $this->arrModeDesc["INS"];
            $this->viewData["btnGuardar"] = "Agregar";
        } else {
            switch($this->viewData["mode"]){
                case 'DEL':
                    $this->viewData["btnGuardar"] = "Eliminar";
                    $this->viewData["readonly"] = true;
                    $this->viewData["titulo"]  = sprintf(
                        $this->arrModeDesc[$this->viewData["mode"]],
                        $this->viewData["product_id"],
                    );
                    break;
                case 'UPD':
                    $this->viewData["btnGuardar"] = 'Actualizar';
                    $this->viewData["titulo"]  = sprintf(
                        $this->arrModeDesc[$this->viewData["mode"]],
                        $this->viewData["product_id"],
                    );
                    break;
                case 'DSP':
                    $this->viewData["btnGuardar"] = 'Regresar';
                    $this->viewData["readonly"] = true;
                    $this->viewData["titulo"]  = sprintf(
                        $this->arrModeDesc[$this->viewData["mode"]],
                        $this->viewData["product_id"],
                    );
                    break;
            }
        }   
    }
}
?>
