<?php

namespace Controllers\Ashion;

use Controllers\Admin\Cliente;
use Controllers\PublicController;
use Views\Renderer;
use Dao\Mnt\Clientes;

class detalleCompra extends PublicController{

    /**
     * Runs the controller
     * 
     * @return void
     */
    private $viewData = array();

    public function run():void{
        //code
        $this->init();

        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] == "PBL"){
                $this->viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $this->viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $this->viewData["customer_id"] = Clientes::getCustomerId(intval($_SESSION["login"]["userId"]));
                $this->viewData["logeado"]=false;
            }
        }
        else 
        {
            $this->viewData["logeado"]=true;
        }

        if(isset($_GET["id"])){
            $tmpCrompra = Clientes::getSaleById($_GET["id"]);
            $this->viewData["detalle"] = Clientes::getSaleDetail($_GET["id"]);
            \Utilities\ArrUtils::mergeFullArrayTo($tmpCrompra, $this->viewData);
        }
        
        $this->viewData['QuantityProducts'] = $this->getQuantityProducts();
        Renderer::render('ashion/detalleCompra', $this->viewData);
    }

    private function init(){
        $this->viewData = array();
        $this->viewData["sale_id"] = "";
        $this->viewData["sale_date"] = "";
        $this->viewData["sale_isv"] = "";
        $this->viewData["sale_subtotal"] = "";
        $this->viewData["total"] = "";
    }
  
    private function getQuantityProducts()
    {
        $quantity = 0;
        if(isset($_SESSION['shopping_cart'])){
            foreach($_SESSION['shopping_cart'] as $product){
                $quantity++;
            }
        }
        return $quantity;
    }
}

?>