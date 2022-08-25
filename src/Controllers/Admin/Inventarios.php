<?php 

namespace Controllers\Admin;

use Controllers\PrivateController;
use Dao\Admin\Inventario as DaoInventarios;
use Views\Renderer;

class Inventarios extends PrivateController
{

    private $viewData= array();

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

        $this->viewData["Inventarios"] = DaoInventarios::getAll();
        Renderer::render('admin/inventarios', $this->viewData);
    }

}

?>