<?php
namespace controllers\Admin;
class FuncionRol extends \Controllers\PrivateController
{
    public function __construct()
    {
        /*
            $userInRole = \Utilities\Security::isInRol(
            \Utilities\Security::getUserId(),
            "ADMINISTRADOR"
         */

         parent::__construct();
    }

    private $rolescod = 0;
    private $rolescod2 = "";
    private $fncod = "";
    private $fncod2 = "";
    private $fnrolest = "";
    private $fnexp = "";
    private $fnrolest_ACT = "";
    private $fnrolest_INA = "";

    private $mode = "";
    private $mode_dsc = "";
    private $mode_adsc = array(
        "INS" => "Agregar Función a Rol",
        "UPD" => "Editar Rol: %s Función: %s",
        "DEL" => "Eliminar Rol: %s Función: %s",
        "DSP" => "Visualizar Rol: %s Función: %s"
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

    private $roles = array();
    private $funciones = array();

    public function run() :void
    {
        $this->roles = \Dao\Admin\FuncionesRoles::getRoles();
        $this->funciones = \Dao\Admin\FuncionesRoles::getFunciones();

        $this->minimumDate = date('Y-m-d', time()+31104000);//(12*30*24*60*60) (m d h mi s));
        $this->mode = isset($_GET["mode"])?$_GET["mode"]:"";
        $this->rolescod = isset($_GET["rolescod"])?$_GET["rolescod"]:"";
        $this->fncod = isset($_GET["fncod"])?$_GET["fncod"]:"";

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
                        if (\Dao\Admin\FuncionesRoles::insert($this->rolescod2, $this->fncod2)) 
                        {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=admin_funcionesroles",
                                "¡Funcion por Rol Agregada Satisfactoriamente!"
                            );
                        }
                    break;

                    case "UPD":
                        if (\Dao\Admin\FuncionesRoles::update($this->rolescod, $this->fncod, $this->fnrolest, $this->fnexp)) 
                        {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=admin_funcionesroles",
                                "¡Funcion por Rol Actualizada Satisfactoriamente!"
                            );
                        }
                    break;

                    case "DEL":
                        if (\DAO\Admin\FuncionesRoles::delete($this->rolescod, $this->fncod)) 
                        {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=admin_funcionesroles",
                                "¡Funcion por Rol Eliminada Satisfactoriamente!"
                            );
                        }
                    break;
                }
            }
        }

        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] !== "PBL"){
               $dataview["isLogged"]=$_SESSION["login"]["usertipo"];
               $dataview["usernameappear"]=$_SESSION["login"]["userName"];
               $dataview["logeado"]=false;
            }
        }
        else 
        {
           $dataview["logeado"]=true;
        }

        $dataview = get_object_vars($this);
        \Views\Renderer::render("admin/funcionrol", $dataview);
    }

    private function _load()
    {
        $_data = \Dao\Admin\FuncionesRoles::getOne($this->rolescod, $this->fncod);
        if($_data)
        {
            $this->rolescod=$_data["rolescod"];
            $this->fncod=$_data["fncod"];
            $this->fnrolest = $_data["fnrolest"];

            $date = isset($_data["fnexp"]) ? $_data["fnexp"] : "";
            $this->fnexp = date("Y-m-d", strtotime($date));

            $this->_setViewData();
        }
    }

    private function _loadPostData()
    {
        $this->rolescod = isset($_POST["rolescod"]) ? $_POST["rolescod"] : 0;
        $this->rolescod2 = isset($_POST["rolescod2"]) ? $_POST["rolescod2"] : "";
        $this->fncod = isset($_POST["fncod"]) ? $_POST["fncod"] : "" ;
        $this->fncod2 = isset($_POST["fncod2"]) ? $_POST["fncod2"] : "" ;
        $this->fnrolest = isset($_POST["fnrolest"]) ? $_POST["fnrolest"] : "";
        $this->fnexp = isset($_POST["fnexp"]) ? $_POST["fnexp"] : "";

        if($this->mode == "INS")
        {
            if(!empty(\Dao\Admin\FuncionesRoles::getOne($this->rolescod2, $this->fncod2)))
            {
                $this->aErrors[] = "La funcion ya se encuentra registrada para este rol.";
            }
        }

        if($this->mode == "UPD")
        {
            if (\Utilities\Validators::IsEmpty($this->fnexp)) 
            {
                $this->aErrors[] = "La fecha de expiración no puede ir vacía.";
            }
        }

        $this->hasErrors = (count($this->aErrors) > 0);
        $this->_setViewData();
    }

    private function _setViewData()
    {
        $this->fnrolest_ACT = ($this->fnrolest === "ACT") ? "selected" : "";
        $this->fnrolest_INA = ($this->fnrolest === "INA") ? "selected" : "";

        $this->mode_dsc = sprintf(
            $this->mode_adsc[$this->mode],
            $this->rolescod,
            $this->fncod
        );

        $this->onlyDisplayIns = ($this->mode=="INS") ? true: false;
        $this->notDisplayIns = ($this->mode=="INS") ? false : true;
        $this->disabled = ($this->mode == "INS" || $this->mode =="DEL" || $this->mode =="DSP") ? "disabled" : "";
        $this->readonly = ($this->mode =="DEL" || $this->mode=="DSP") ? "readonly" : "";
        $this->allInfoDisplayed = ($this->mode =="DEL" || $this->mode=="DSP") ? true : false;
        $this->showaction = !($this->mode == "DSP");
    }
}
?>