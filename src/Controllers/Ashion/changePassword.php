<?php

namespace Controllers\Ashion;

use Controllers\PublicController;
use \Utilities\Validators;
use Views\Renderer;
use Dao\Security\Security;
use Exception;

class changePassword extends PublicController
{
    private $viewData = array();

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
                $this->viewData["usercod"] = intval($_GET["id"]);
            }
        }
        // Procesar POST
        if ($this->isPostBack()) {
            \Utilities\ArrUtils::mergeArrayTo($_POST, $this->viewData);
            $hasErrors = false;
            //validaciones
            if (!Validators::IsValidPassword($this->viewData["userpaswd"])) {
                $this->viewData["errorPswd"][] = "La contraseña debe tener al menos 8 caracteres una mayúscula, un número y un caracter especial.";
                $hasErrors = true;
            }
            if ($this->viewData["userpaswdV"] != $this->viewData["userpaswd"]) {
                $this->viewData["errorPasswordV"][] = "Las contraseñas no son iguales.";
                $hasErrors = true;
            }
            error_log(json_encode($this->viewData));
            
            if (!$hasErrors) {
                try{
                    if (Security::changePassword($this->viewData["usercod"], $this->viewData["userpaswd"])) {
                        \Utilities\Site::redirectToWithMsg("index.php?page=ashion_perfil", "¡Contraseña modificada satisfactoriamente!");
                    }
                } catch (Exception $ex){
                    die($ex);
                }
            }
        }

        $this->viewData['QuantityProducts'] = $this->getQuantityProducts();
        Renderer::render("ashion/changePassword", $this->viewData);
    }

    private function init()
    {
        $this->viewData = array();
        $this->viewData["usercod"] = "";
        $this->viewData["userpaswd"] = "";
        $this->viewData["errorPswd"] = array();
        $this->viewData["userpaswdV"] = "";
        $this->viewData["errorPasswordV"] = array();

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
