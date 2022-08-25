<?php

namespace Controllers\Admin;

use Controllers\PrivateController;
use Dao\Mnt\Clientes as DaoClientes;
use Views\Renderer;

class Clientes extends PrivateController{

    /**
     * Runs the controller
     * 
     * @return void
     */
    private $viewData = array();
    public function run():void{
        //code
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
        $this->viewData["clientes"] = DaoClientes::getAllCustomer();

        Renderer::render('admin/clientes', $this->viewData);
    }
}

?>