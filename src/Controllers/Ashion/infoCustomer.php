<?php

namespace Controllers\Ashion;

use Controllers\PublicController;
use \Utilities\Validators;
use Views\Renderer;
use Dao\Mnt\Clientes;
use Exception;

class InfoCustomer extends PublicController
{
    private $viewData = array();
    private $arr_customer_country = array();

    public function run() :void
    {
        $this->init();

        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] == "PBL"){
                $this->viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $this->viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $this->viewData["logeado"]=false;
            }
        }
        else 
        {
            $this->viewData["logeado"]=true;
        }

        // Procesar GET
        if (!$this->isPostBack()) {
            if (isset($_GET["id"])) {
                $this->viewData["customer_id"] = intval($_GET["id"]);
                $tmpCliente = Clientes::getCustomer($this->viewData["customer_id"]);
                error_log(json_encode($tmpCliente));
                \Utilities\ArrUtils::mergeFullArrayTo($tmpCliente, $this->viewData);
            }
        }
        // Procesar POST
        if ($this->isPostBack()) {
            \Utilities\ArrUtils::mergeArrayTo($_POST, $this->viewData);
            $hasErrors = false;
            //validaciones
            if (Validators::IsEmpty($this->viewData["customer_name"])) {
                $this->viewData["error_customer_name"][]
                    = "El nombre es requerido";
                $hasErrors = true;
            }
            if (Validators::IsEmpty($this->viewData["customer_lastname"])) {
                $this->viewData["error_customer_lastname"][]
                    = "El apellido es requerido";
                $hasErrors = true;
            }
            if (Validators::IsEmpty($this->viewData["customer_phone_number"])) {
                $this->viewData["error_customer_phone_number"][]
                    = "El telefono es requerido";
                $hasErrors = true;
            }
            error_log(json_encode($this->viewData));
            
            if (!$hasErrors) {
                try{
                    if (Clientes::updateCustomer(
                        $this->viewData["customer_name"],
                        $this->viewData["customer_lastname"],
                        $this->viewData["customer_address"],
                        $this->viewData["customer_postal_code"],
                        $this->viewData["customer_country"],
                        $this->viewData["customer_city"],
                        $this->viewData["customer_phone_number"],
                        intval($this->viewData["customer_id"]))) {
                        \Utilities\Site::redirectToWithMsg("index.php?page=ashion_perfil", "¡Información modificada satisfactoriamente!");
                    }
                } catch (Exception $ex){
                    die($ex);
                }
            }
        }

        $this->viewData["arr_customer_country"]
            = \Utilities\ArrUtils::objectArrToOptionsArray(
                $this->arr_customer_country,
                'value',
                'text',
                'value',
                $this->viewData["customer_country"]
            );

        $this->viewData['QuantityProducts'] = $this->getQuantityProducts();
        Renderer::render("ashion/infoCustomer", $this->viewData);
    }

    private function init()
    {
        $this->viewData = array();
        $this->viewData["customer_id"] = "";
        $this->viewData["customer_name"] = "";
        $this->viewData["errorNombre"] = array();
        $this->viewData["customer_lastname"] = "";
        $this->viewData["errorApellido"] = array();
        $this->viewData["customer_country"] = "";
        $this->viewData["error_customer_country"] = array();
        $this->viewData["customer_city"] = "";
        $this->viewData["error_customer_city"] = array();
        $this->viewData["customer_address"] = "";
        $this->viewData["error_customer_address"] = array();
        $this->viewData["customer_postal_code"] = "";
        $this->viewData["error_customer_postal_code"] = array();
        $this->viewData["customer_phone_number"] = "";
        $this->viewData["errorTelefono"] = array();

        $this->arr_customer_country = array(
            array("value" => "ESP", "text" => "España"),
            array("value" => "USA", "text" => "Estados Unidos"),
            array("value" => "HND", "text" => "Honduras"),
        );

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
