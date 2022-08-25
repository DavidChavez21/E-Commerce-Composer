<?php
namespace Controllers\Admin;
use Dao\Admin\Funciones as DaoFN;
use Utilities\Validators;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
class Funcion extends \Controllers\PrivateController
{
    private $viewData= array();
    private $fnestArray= array(); 
    private $fntypArray= array();
    private $arrayModeDesc= array();
    /**
     * Constructor
     */
    public function __construct()
    {
        // $userInRole = \Utilities\Security::isInRol(
        //     \Utilities\Security::getUserId(),
        //     "ADMIN"
        // );
        parent::__construct();
    }
    /** 
     * Ejecuta el controlador
     */
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
        if(!$this->isPostBack()) {
            $this->procesarGet();
        }

        if($this->isPostBack()) {
            $this->procesarPost();
        }

        $this->procesarView();

        \Views\Renderer::render("admin/funcion", $this->viewData);
    }

    private function init() {
        $this->viewData['mode']= "";
        $this->viewData['mode_desc']= "";
        $this->viewData['fncod']="";
        $this->viewData['fndsc']="";
        $this->viewData['fnest']="";
        $this->viewData['fntyp']="";
        $this->viewData['error_fncod']="";
        $this->viewData['error_fndsc']="";
        $this->viewData["btnEnviarText"]= "Guardar";
        $this->viewData["showBtn"]= true;
        $this->viewData["readonly"]= false;
        $this->viewData["type"]='text';

        $this->arrayModeDesc= array (
            "INS" => "Nueva Funcion", 
            "UPD" => "Editando %s %s",
            "DSP"=>"Detalle de %s %s",
            "DEL" => "Elimiando %s %s"
        );

        $this->fnestArray=array(
            array("value" => "ACT", "text" => "Activo"),
            array("value" => "INA", "text" => "Inactivo")
        );

        $this->fntypArray=array(
            array("value" => "CTR", "text" => "Controlador"),
            array("value" => "VWS", "text" => "Vista")
        );

        $this->viewData["fnestArray"]= $this->fnestArray; 
        $this->viewData["fntypArray"]= $this->fntypArray;

    }


    private function procesarGet() {
        if(isset($_GET["mode"])) {
            $this->viewData["mode"]= $_GET["mode"];
            if(!isset($this->arrayModeDesc[$this->viewData["mode"]])) {
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=admin_funciones",
                    "No existe el modo solicitado"
                );
            }
        }

        if($this->viewData["mode"] !== "INS" && isset($_GET["id"])) {
            $this->viewData["fncod"]= strval($_GET["id"]);
            $tmpFn= DaoFN::getById($this->viewData["fncod"]);
            \Utilities\ArrUtils::mergeFullArrayTo($tmpFn, $this->viewData);
        }
    }

    private function procesarPost() {
        
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
        $this->viewData['fncod']=$_POST['fncod'];
        $this->viewData['fnddc']=$_POST['fnddc'];
        $this->viewData['fnest']=$_POST['fnest'];
        $this->viewData['fntyp']=$_POST['fntyp'];
        //\Utilities\ArrUtils::mergeArrayTo($_POST, $this->viewData);
        if(Validators::IsEmpty($this->viewData["fncod"]))
        {
            $this->viewData["error_fncod"][] 
            = "El codigo esta vacio debe ingresarlo";
            $hasErrors= true;
        }
        if(Validators::IsEmpty($this->viewData["fnddc"]))
        {
            $this->viewData["error_fndsc"][] 
            = "La descripcion esta vacia debe ingresarla";
            $hasErrors= true;
        }

        if(!$hasErrors) {
            $result= null;

            switch($this->viewData["mode"]) {
                case 'INS': 
                    $result= DaoFN::insert(
                        $this->viewData["fncod"],
                        $this->viewData["fndsc"],
                        $this->viewData["fnest"],
                        $this->viewData["fntyp"]
                    );

                    if($result) {
                        \Utilities\Site::redirectToWithMsg(
                            "index.php?page=admin_funciones",
                            "Funcion Agregada"
                        );
                    }
                     break; 
                case 'UPD': 
                        $result= DaoFN::update(
                            strval($this->viewData["fncod"]),
                            $this->viewData["fndsc"],
                            $this->viewData["fnest"],
                            $this->viewData["fntyp"]
                        );
    
                        if($result) {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=admin_funciones",
                                "Funcion Actualizada"
                            );
                        }
                         break; 
                 case 'DEL': 
                            $result= DaoFN::delete(
                                $this->viewData["fncod"]
                            );
        
                            if($result) {
                                \Utilities\Site::redirectToWithMsg(
                                    "index.php?page=admin_funciones",
                                    "Funcion Eliminada"
                                );
                            }
                            break; 

            }
        }
    }

    private function procesarView() {
        if($this->viewData["mode"]==='INS') {
            $this->viewData["mode_desc"]= $this->arrayModeDesc["INS"];
            $this->viewData["btnEnviarText"] = "Guardar Nuevo";
        }
        else 
        {
            $this->viewData["mode_desc"] = sprintf(
                //$this->arrayModeDesc[$this->viewData["mode"]],
                $this->viewData["fncod"],
                $this->viewData["fnddc"]
            );

            $this->viewData["fnestArray"]
            = \Utilities\ArrUtils::objectArrToOptionsArray(
                $this->fnestArray,
                'value',
                'text',
                'value',
                $this->viewData["fnest"]
            );

            $this->viewData["fntypArray"]
            = \Utilities\ArrUtils::objectArrToOptionsArray(
                $this->fntypArray,
                'value',
                'text',
                'value',
                $this->viewData["fntyp"]
            );

            if($this->viewData["mode"]=== "DSP") {
                $this->viewData["readonly"]= true;
                $this->viewData["showBtn"] = false;
                $this->viewData["type"] = 'hidden';
            }

            if ($this->viewData["mode"] === "DEL") {
                $this->viewData["readonly"] = true;
                $this->viewData["btnEnviarText"] = "Eliminar";
                $this->viewData["type"] = 'text';
            }
            if ($this->viewData["mode"] === "UPD") {
                $this->viewData["btnEnviarText"] = "Actualizar";
                $this->viewData["type"] = 'hidden';
            }
        }
    }



}
?>