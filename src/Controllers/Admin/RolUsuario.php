<?php
namespace Controllers\Admin;

class RolUsuario extends \Controllers\PrivateController 
{
    public function __construct()
    {
        /*
        $userInRole = \Utilities\Security::isInRol(
            \Utilities\Security::getUserId(),
            "ADMINISTRADOR"
        );
        */
        
        parent::__construct();
    }

    private $usercod = 0;
    private $usercod2 = "";
    private $rolescod = "";
    private $rolescod2 = "";
    private $roleuserest = "";
    private $roleuserexp = "";
    private $roleuserfch = "";
    private $username = "";
    private $useremail = "";
    private $usertipo = "";
    private $roleuserest_ACT = "";
    private $roleuserest_INA = "";

    private $mode = "";
    private $mode_dsc = "";
    private $mode_adsc = array(
        "INS" => "Agregar Rol a Usuario",
        "UPD" => "Editar Usuario: %s Rol: %s",
        "DEL" => "Eliminar Usuario: %s Rol: %s",
        "DSP" => "Visualizar Usuario: %s Rol: %s"
    );

    private $minimumDate = "";

    private $onlyDisplayIns = true;
    private $notDisplayIns = false;
    private $allInfoDisplayed = false;
    private $disabled = "";
    private $readonly = "";
    private $showaction= true;

    private $hasErrors = false;
    private $aErrors = array();

    private $usuarios = array();
    private $roles = array();
    
    public function run() :void
    {
        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] !== "PBL"){
                $viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $viewData["logeado"]=false;
            }
        }
        else 
        {
            $viewData["logeado"]=true;
        }
        $this->usuarios = \Dao\Admin\Roles_Usuarios::getUsuarios();
        $this->roles = \Dao\Admin\Roles_Usuarios::getRoles();

        $this->minimumDate = date('Y-m-d', time() + 31104000);  //(12*30*24*60*60) (m d h mi s));
        $this->mode = isset($_GET["mode"])?$_GET["mode"]:"";
        $this->usercod = isset($_GET["usercod"])?$_GET["usercod"]:"";
        $this->rolescod = isset($_GET["rolescod"])?$_GET["rolescod"]:"";

        if (!$this->isPostBack()) 
        {
            if ($this->mode !== "INS") 
            {
                $this->_load();
            } 
            else 
            {
                $this->mode_dsc = $this->mode_adsc[$this->mode];
            }
        } 
        else 
        {
            $this->_loadPostData();
            if (!$this->hasErrors) 
            {
                switch ($this->mode)
                {
                    case "INS":
                        if (\Dao\Admin\Roles_Usuarios::insert($this->usercod2, $this->rolescod2)) 
                        {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=admin_rolesusuarios",
                                "¡Rol Agregado a Usuario Satisfactoriamente!"
                            );
                        }
                    break;

                    case "UPD":
                        if (\Dao\Admin\Roles_Usuarios::update($this->usercod, $this->rolescod, $this->roleuserest, $this->roleuserexp)) 
                        {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=admin_rolesusuarios",
                                "¡Rol Actualizado al Usuario Satisfactoriamente!"
                            );
                        }
                    break;

                    case "DEL":
                        if (\DAO\Admin\Roles_Usuarios::delete($this->usercod, $this->rolescod)) 
                        {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=admin_rolesusuarios",
                                "¡Rol Eliminado al Usuario Satisfactoriamente!"
                            );
                        }
                    break;
                }
            }
        }

        $dataview = get_object_vars($this);
        \Views\Renderer::render("admin/rolusuario", $dataview);
    }

    private function _load()
    {
        $_data = \Dao\Admin\Roles_Usuarios::getOne($this->usercod, $this->rolescod);

        if ($_data) 
        {
            $this->usercod = $_data["usercod"];
            $this->rolescod = $_data["rolescod"];
            $this->roleuserest = $_data["roleuserest"];
          
            $this->username = $_data["username"];
            $this->useremail = $_data["useremail"];
            $this->usertipo = $_data["usertipo"];

            $dateUsuarioFch = isset($_data["roleuserfch"]) ? $_data["roleuserfch"] : "";
            $dateUsuarioExp = isset($_data["roleuserexp"]) ? $_data["roleuserexp"] : "";

            $this->roleuserfch = date("Y-m-d", strtotime($dateUsuarioFch));
            $this->roleuserexp = date("Y-m-d", strtotime($dateUsuarioExp));

            $this->_setViewData();
        }
    }

    private function _loadPostData()
    {
        $this->usercod = isset($_POST["usercod"]) ? $_POST["usercod"] : 0;
        $this->usercod2 = isset($_POST["usercod2"]) ? $_POST["usercod2"] : "";
        $this->rolescod = isset($_POST["rolescod"]) ? $_POST["rolescod"] : 0;
        $this->rolescod2 = isset($_POST["rolescod2"]) ? $_POST["rolescod2"] : "";
        $this->roleuserest = isset($_POST["roleuserest"]) ? $_POST["roleuserest"] : "";
        $this->roleuserexp = isset($_POST["roleuserexp"]) ? $_POST["roleuserexp"] : "";

        if($this->mode == "INS")
        {
            if(!empty(\Dao\Admin\Roles_Usuarios::getOne($this->usercod2, $this->rolescod2)))
            {
                $this->aErrors[] = "El rol ya se encuentra registrado para este usuario.";
            }
        }

        if($this->mode == "UPD")
        {
            if (\Utilities\Validators::IsEmpty($this->roleuserexp)) 
            {
                $this->aErrors[] = "La fecha de expiración no puede ir vacía.";
            }
        }
        
        $this->hasErrors = (count($this->aErrors) > 0);
        $this->_setViewData();
    }

    private function _setViewData()
    {
        $this->roleuserest_ACT = ($this->roleuserest === "ACT") ? "selected" : "";
        $this->roleuserest_INA = ($this->roleuserest === "INA") ? "selected" : "";

        $this->mode_dsc = sprintf(
            $this->mode_adsc[$this->mode],
            $this->usercod,
            $this->rolescod
        );

        $this->onlyDisplayIns = ($this->mode=="INS") ? true: false;
        $this->notDisplayIns = ($this->mode=="INS") ? false : true;
        $this->disabled = ($this->mode == "INS" || $this->mode =="DEL" || $this->mode =="DSP") ? "disabled" : "";
        $this->readonly = ($this->mode =="DEL" || $this->mode=="DSP") ? "readonly" : "";
        $this->allInfoDisplayed = ($this->mode =="UPD" || $this->mode =="DEL" || $this->mode=="DSP") ? true : false;
        $this->showaction = !($this->mode == "DSP");
    }
}
?>