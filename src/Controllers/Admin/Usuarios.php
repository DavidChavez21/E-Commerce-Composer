<?php 

namespace Controllers\Admin;

use Views\Renderer;
use Dao\Admin\Usuarios as DaoUsuarios;

class Usuarios extends \Controllers\PrivateController {
    public function run():void{
        $viewData = array();
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
        $viewData["Usuarios"] = DaoUsuarios::getAll();
        Renderer::render('admin/usuarios', $viewData);
    }
}
?>