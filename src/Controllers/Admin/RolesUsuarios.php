<?php
namespace controllers\Admin;
class RolesUsuarios extends \Controllers\PrivateController
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

        private $UsuarioBusqueda = "";

        public function run() :void
        {
            $dataview = array();
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

            if ($this->isPostBack()) 
            {   
                $this->UsuarioBusqueda = isset($_POST["UsuarioBusqueda"]) ? $_POST["UsuarioBusqueda"] : "";

                if(!empty($this->UsuarioBusqueda))
                {
                    $dataview["items"] = \Dao\Admin\Roles_Usuarios::searchRolesUsuarios($this->UsuarioBusqueda);
                    \Utilities\Context::setContext("UsuarioBusqueda", $this->UsuarioBusqueda);
                }
                else
                {
                    $dataview["items"] = \Dao\Admin\Roles_Usuarios::getAll();
                }
            } 
            else
            {   
                $dataview["items"] = \Dao\Admin\Roles_Usuarios::getAll();
            }
            
            \Views\Renderer::render("admin/rolesusuarios", $dataview);
        } 
}
?>