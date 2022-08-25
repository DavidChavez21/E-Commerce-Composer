<?php

namespace Controllers\Admin;

use Controllers\PrivateController;
use Controllers\PublicController;
use Views\Renderer;
use Utilities\Validators;
use Dao\Mnt\Clientes;

class Cliente extends PrivateController{

    private $viewData = array();
    private $arrModeDesc = array();
    private $arr_customer_country = array();

    /**
     * Runs the controller
     * 
     * @return void
     */
    public function run():void
    {
        // code
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
        // Procesar GET
        if (!$this->isPostBack()) {
            $this->procesarGet();
        }
        // Procesa POST
        if ($this->isPostBack()) {
            $this->procesarPost();
        }
        // Ejecutar Siempre
        $this->processView();
        Renderer::render('admin/cliente', $this->viewData);
    }

    private function init()
    {
        $this->viewData = array();
        $this->viewData["mode"] = "";
        $this->viewData["mode_desc"] = "";
        $this->viewData["customer_id"] = "";
        $this->viewData["customer_name"] = "";
        $this->viewData["error_customer_name"] = array();
        $this->viewData["customer_lastname"] = "";
        $this->viewData["error_customer_lastname"] = array();
        $this->viewData["customer_country"] = "";
        $this->viewData["error_customer_country"] = array();
        $this->viewData["customer_city"] = "";
        $this->viewData["error_customer_city"] = array();
        $this->viewData["customer_address"] = "";
        $this->viewData["error_customer_address"] = array();
        $this->viewData["customer_postal_code"] = "";
        $this->viewData["error_customer_postal_code"] = array();
        $this->viewData["customer_phone_number"] = "";
        $this->viewData["error_customer_phone_number"] = array();
        $this->viewData["btnEnviarText"] = "Guardar";
        $this->viewData["readonly"] = false;
        $this->viewData["showBtn"] = true;

        $this->arrModeDesc = array(
            "INS"=>"Nuevo cliente",
            "UPD"=>"Editando a %s",
            "DSP"=>"Detalle de %s",
            "DEL"=>"Eliminado a %s"
        );

        $this->arr_customer_country = array(
            array("value" => "ESP", "text" => "España"),
            array("value" => "USA", "text" => "Estados Unidos"),
            array("value" => "HND", "text" => "Honduras"),
        );

    }

    private function procesarGet()
    {
        if (isset($_GET["mode"])) {
            $this->viewData["mode"] = $_GET["mode"];
            if (!isset($this->arrModeDesc[$this->viewData["mode"]])) {
                error_log('Error: (Cliente) Mode solicitado no existe.');
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=admin-clientes",
                    "No se puede procesar su solicitud!"
                );
            }
        }
        if ($this->viewData["mode"] !== "INS" && isset($_GET["id"])) {
            $this->viewData["customer_id"] = intval($_GET["id"]);
            $tmpCliente = Clientes::getCustomer($this->viewData["customer_id"]);
            error_log(json_encode($tmpCliente));
            \Utilities\ArrUtils::mergeFullArrayTo($tmpCliente, $this->viewData);
        }
    }
    private function procesarPost()
    {
        // Validar la entrada de Datos
        //  Todos valor puede y sera usando en contra del sistema
        $hasErrors = false;
        \Utilities\ArrUtils::mergeArrayTo($_POST, $this->viewData);

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
        // Ahora procedemos con las modificaciones al registro
        if (!$hasErrors) {
            $result = null;
            switch($this->viewData["mode"]) {
            case 'UPD':
                $result = Clientes::updateCustomer(
                    $this->viewData["customer_name"],
                    $this->viewData["customer_lastname"],
                    $this->viewData["customer_address"],
                    $this->viewData["customer_postal_code"],
                    $this->viewData["customer_country"],
                    $this->viewData["customer_city"],
                    $this->viewData["customer_phone_number"],
                    intval($this->viewData["customer_id"])
                );
                if ($result) {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin-clientes",
                        "Cliente actualizado satisfactoriamente"
                    );
                }
                break;
            case 'DEL':
                $result = Clientes::deleteCustomer(
                    intval($this->viewData["customer_id"])
                );
                if ($result) {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin-clientes",
                        "Cliente eliminado satisfactoriamente"
                    );
                }
                break;
            }
        }
    }

    private function processView()
    {
        if ($this->viewData["mode"] === "INS") {
            $this->viewData["mode_desc"]  = $this->arrModeDesc["INS"];
            $this->viewData["btnEnviarText"] = "Guardar Nuevo";
        } else {
            $this->viewData["arr_customer_country"]
                = \Utilities\ArrUtils::objectArrToOptionsArray(
                    $this->arr_customer_country,
                    'value',
                    'text',
                    'value',
                    $this->viewData["customer_country"]
                );
            $this->viewData["mode_desc"]  = sprintf(
                $this->arrModeDesc[$this->viewData["mode"]],
                $this->viewData["customer_name"]
            );
            if ($this->viewData["mode"] === "DSP") {
                $this->viewData["readonly"] = true;
                $this->viewData["showBtn"] = false;
            }
            if ($this->viewData["mode"] === "DEL") {
                $this->viewData["readonly"] = true;
                $this->viewData["btnEnviarText"] = "Eliminar";
            }
            if ($this->viewData["mode"] === "UPD") {
                $this->viewData["btnEnviarText"] = "Actualizar";
            }
        }
        
    }
}

?>