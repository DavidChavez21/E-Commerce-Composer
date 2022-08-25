<?php
namespace Controllers\Admin;

use Views\Renderer;
use Dao\Admin\Usuarios as DaoUsuarios;
use Utilities\Validators;

class Usuario extends \Controllers\PrivateController
{
    private $viewData = array();
    private $arrModeDesc = array();
    private $arrEstados = array();
    private $arrTipos = array();

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
        $this->init();

        if (!$this->isPostBack()) {
            $this->procesarGet();
        }

        if ($this->isPostBack()) {
            $this->procesarPost();
        }

        $this->processView();
        Renderer::render('admin/usuario',$this->viewData);
    }

    private function init()
    {

        $this->viewData = array();
        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] !== "PBL"){
                $this->viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $this->viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $this->viewData["logeado"]=false;
            }
        }
        else 
        {
            $viewData["logeado"]=true;
        }
        
        $this->viewData["mode"] = "";
        $this->viewData["mode_desc"] = "";
        $this->viewData["crsf_token"] = "";
        $this->viewData["usercod"] = "";
        $this->viewData["useremail"] = "";
        $this->viewData["error_useremail"] = array();
        $this->viewData["username"] = "";
        $this->viewData["error_username"] = array();
        $this->viewData["userpswd"] = "";
        $this->viewData["error_userpswd"] = array();
        $this->viewData["userest"] = "";
        $this->viewData["userestArr"] = array();
        $this->viewData["usertipo"] = "";
        $this->viewData["usertipoArr"] = array();
        $this->viewData["btnEnviarText"] = "Guardar";
        $this->viewData["readonly"] = false;
        $this->viewData["showBtn"] = true;
        
        $this->arrModeDesc = array(
            "INS"=>"Nuevo Usuarios",
            "UPD"=>"Editando Usuarios con el ID %d",
            "DSP"=>"Detalle del Usuarios con el ID %d",
            "DEL"=>"Eliminado Usuarios con el ID %d"
        );

        $this->arrEstados = array(
            array("value" => "ACT", "text" => "Activo"),
            array("value" => "INA", "text" => "Inactivo"),
            array("value" => "BLQ", "text" => "Bloqueado"),
            array("value" => "SUS", "text" => "Suspendido"),
        );

        $this->arrTipos = array(
            array("value" => "PBL", "text" => "Publico"),
            array("value" => "ADM", "text" => "Administrador"),
            array("value" => "AUD", "text" => "Auditor"),
        );

        $this->viewData["userestArr"] = $this->arrEstados;
        $this->viewData["usertipoArr"] = $this->arrTipos;
    }

    private function procesarGet()
    {
        if (isset($_GET["mode"])) {
            $this->viewData["mode"] = $_GET["mode"];
            if (!isset($this->arrModeDesc[$this->viewData["mode"]])) {
                error_log('Error: (Producto) Mode solicitado no existe.');
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=admin_usuarios",
                    "No se puede procesar su solicitud!"
                );
            }
        }
        if ($this->viewData["mode"] !== "INS" && isset($_GET["id"])) {
            $this->viewData["usercod"] = intval($_GET["id"]);
            $tmpProducto = DaoUsuarios::getById($this->viewData["usercod"]);
            error_log(json_encode($tmpProducto));
            \Utilities\ArrUtils::mergeFullArrayTo($tmpProducto, $this->viewData);
        }
    }
    
    private function procesarPost()
    {

        $this->viewData = array();
        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] !== "PBL"){
                $this->viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $this->viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $this->viewData["logeado"]=false;
            }
        }
        else 
        {
            $viewData["logeado"]=true;
        }

        $hasErrors = false;
        $this->viewData['mode']=$_POST['mode'];
        $this->viewData['useremail']=$_POST['useremail'];
        $this->viewData['username']=$_POST['username'];
        $this->viewData['userpswd']=$_POST['userpswd'];
        $this->viewData['usertipo']=$_POST['usertipo'];
        $this->viewData['userest']=$_POST['userest'];

        //validaciones
        if (!(Validators::IsValidEmail($this->viewData["useremail"]))) {
            $this->viewData["error_useremail"][]
                = "El email no es valido";
            $hasErrors = true;
        }
        if (Validators::IsEmpty($this->viewData["username"])) {
            $this->viewData["error_username"][]
                = "El nombre es requerido";
            $hasErrors = true;
        }
        error_log(json_encode($this->viewData));
        // Ahora procedemos con las modificaciones al registro
        if (!$hasErrors){
            $result = null;
            switch($this->viewData["mode"]){
                case 'INS':
                    $result = DaoUsuarios::insert(
                        $this->viewData["useremail"],
                        $this->viewData["username"],
                        $this->viewData["userpswd"],
                        $this->viewData["userest"],
                        $this->viewData["usertipo"]
                    );
                    if ($result) {
                        \Utilities\Site::redirectToWithMsg(
                            "index.php?page=admin_usuarios",
                            "Usuario guardado Satisfactoriamente!"
                        );
                    }
                    break;

                case 'UPD':
                    $result = DaoUsuarios::update(
                        intval($this->viewData["usercod"]),
                        $this->viewData["useremail"],
                        $this->viewData["username"],
                        $this->viewData["userpswd"],
                        $this->viewData["userest"],
                        $this->viewData["usertipo"]
                    );
                    if ($result) {
                        \Utilities\Site::redirectToWithMsg(
                            "index.php?page=admin_usuarios",
                            "Usuario modificado Satisfactoriamente!"
                        );
                    }
                    break;

                case 'DEL':
                    $result = DaoUsuarios::delete(
                        intval($this->viewData["usercod"])
                    );
                    if ($result) {
                        \Utilities\Site::redirectToWithMsg(
                            "index.php?page=admin_usuarios",
                            "Usuario eliminado Satisfactoriamente!"
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
            $this->viewData["mode_desc"]  = sprintf(
                //$this->arrModeDesc[$this->viewData["mode"]],
                $this->viewData["usercod"]
            );
            $this->viewData["userestArr"]
                = \Utilities\ArrUtils::objectArrToOptionsArray(
                    $this->arrEstados,
                    'value',
                    'text',
                    'value',
                    $this->viewData["userest"]
                );
            $this->viewData["usertipoArr"]
                = \Utilities\ArrUtils::objectArrToOptionsArray(
                    $this->arrTipos,
                    'value',
                    'text',
                    'value',
                    $this->viewData["usertipo"]
                );
            switch($this->viewData["mode"]){
                case 'DSP': 
                    $this->viewData["readonly"] = true;
                    $this->viewData["showBtn"] = false;
                    break; 
                case 'DEL':
                    $this->viewData["readonly"] = true;
                    $this->viewData["btnEnviarText"] = "Eliminar";
                    break;
                case 'UPD':
                    $this->viewData["btnEnviarText"] = 'Actualizar';
                    break;
            }
        }   
        $this->viewData["crsf_token"] = md5(getdate()[0] . $this->name);
        $_SESSION[$this->name . "crsf_token"] = $this->viewData["crsf_token"];
    }
}

?>