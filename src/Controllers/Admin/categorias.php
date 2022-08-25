<?php
namespace Controllers\Admin;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Admin\Categorias as DaoCategorias;

class Categorias extends PrivateController
{
    private $viewData = array();
    public function run() :void
    {
        if($this->isPostBack()){
            $this->insertCategory();
        }
        $this->viewData['Categorias'] = DaoCategorias::getAllCategories();
        Renderer::render('admin/categorias', $this->viewData);
    }
    
    private function insertCategory(){
        if(isset($_POST['btnEnviar'])){
            $image = $_POST['image'];
            $name =  $_POST['name'];

            DaoCategorias::insertCategory($image,$name);
        }
    }
}
?>
