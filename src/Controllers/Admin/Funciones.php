<?php
namespace Controllers\Admin;
use Dao\Admin\funciones as DaoFN; 

class Funciones extends \Controllers\PrivateController
{
    private $viewData= array();
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
        $this->viewData["Funciones"]= DaoFN::getAll();
        \Views\Renderer::render("admin/funciones", $this->viewData);
    }
}
?>
