<?php
namespace Controllers\Admin;

class FuncionRol extends \Controllers\PrivateController
{
    public function __construct(){
        /*
        $userInRole = \Utilities\Security::isInRol(
            \Utilities\Security::getUserId(),
            "ADMINISTRADOR"
        );
        */
        parent::__construct();
    }
    private $UsuarioBusqueda = "";

    public function run() :void
    {
        $dataview = array();

        if ($this->isPostBack()) 
        {   
            $this->UsuarioBusqueda = isset($_POST["UsuarioBusqueda"]) ? $_POST["UsuarioBusqueda"] : "";

            if(!empty($this->UsuarioBusqueda))
            {
                $dataview["items"] = \Dao\Admin\FuncionesRoles::searchFuncionesRoles($this->UsuarioBusqueda);
                \Utilities\Context::setContext("UsuarioBusqueda", $this->UsuarioBusqueda);
            }
            else
            {
                $dataview["items"] = \Dao\Admin\FuncionesRoles::getAll();
            }
        } 
        else
        {   
            $dataview["items"] = \Dao\Admin\FuncionesRoles::getAll();
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
        
        \Views\Renderer::render("admin/funcionesroles", $dataview);
    } 

}
?>