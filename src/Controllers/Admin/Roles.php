<?php 

namespace Controllers\Admin;

use Dao\Admin\Roles as DaoRoles;
use Controllers\PrivateController;
use Views\Renderer;

class Roles extends PrivateController
{
    public function run():void
    {
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
        $viewData['Roles'] = DaoRoles::getAll();
        Renderer::render('admin/roles', $viewData);
    }
}

?>