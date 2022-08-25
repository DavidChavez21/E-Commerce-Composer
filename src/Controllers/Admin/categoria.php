<?php
namespace Controllers\Admin;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Admin\Categorias;

class Categoria extends PrivateController
{
    private $viewData = array();
    private $arrModeDesc = array();

    public function run():void
    {
        $this->init();
        if($this->isPostBack()){
            $this->acciones();
        }else{
            $this->getCategory();
        }

        $this->processView();
        Renderer::render('admin/categoria', $this->viewData);
    }

    private function getCategory(){
        if (isset($_GET["mode"])) {
            $this->viewData["mode"] = $_GET["mode"];
            if (!isset($this->arrModeDesc[$this->viewData["mode"]])) {
                error_log('Error: (Categoria) Mode solicitado no existe.');
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=admin_categorias",
                    "No se puede procesar su solicitud!"
                );
            }
        }
        if($this->viewData["mode"] !== "INS" && isset($_GET['category_id'])){
            $this->viewData['category_id'] = intval($_GET['category_id']);
            $tmpCategoria = Categorias::getCategoryById($this->viewData['category_id']);
            error_log(json_encode($tmpCategoria));
            \Utilities\ArrUtils::mergeFullArrayTo($tmpCategoria, $this->viewData);
        }
    }

    private function init(){
        $this->viewData = array();

        $this->viewData["mode"] = "";
        $this->viewData['category_id'] = '';
        $this->viewData["category_name"] = "";
        $this->viewData["category_image_url"] = "";

        $this->viewData["titulo"] = "";
        $this->viewData["readonly"] = false;
        $this->arrModeDesc = array(
            "INS"=>"Agregar Nueva Categoria",
            "UPD"=>"Editando Categoria #%d",
            "DSP"=>"Detalle de la Catgeoria #%d",
            "DEL"=>"Eliminado Categoria #%d"
        );
    }
    
    private function acciones(){
        \Utilities\ArrUtils::mergeFullArrayTo($_POST, $this->viewData);
        switch($this->viewData["mode"]) {
        case 'INS':
            $result = Categorias::insertCategory(
                $this->viewData["category_name"],
                $this->viewData["category_image_url"],
            );
            if($result){
                header('Location: index.php?page=admin_categorias');
                exit;
            }
        case 'UPD':
            $result = Categorias::updateCategory(
                intval($this->viewData["category_id"]),
                $this->viewData["category_name"],
                $this->viewData["category_image_url"],
            );
            if($result){
                header('Location: index.php?page=admin_categorias');
                exit;
            }
        case 'DEL':
            $result = Categorias::deleteCategory(
                intval($this->viewData["category_id"])
            );
            if($result){
                header('Location: index.php?page=admin_categorias');
                exit;
            }
            break;
        default:
            header('Location: index.php?page=admin_categorias');
            break;
        }
    }

    private function processView()
    {
        if ($this->viewData["mode"] === "INS") {
            $this->viewData["titulo"]  = $this->arrModeDesc["INS"];
            $this->viewData["btnGuardar"] = "Agregar";
        } else {
            switch($this->viewData["mode"]){
                case 'DEL':
                    $this->viewData["btnGuardar"] = "Eliminar";
                    $this->viewData["readonly"] = true;
                    $this->viewData["titulo"]  = sprintf(
                        $this->arrModeDesc[$this->viewData["mode"]],
                        $this->viewData["category_id"],
                    );
                    break;
                case 'UPD':
                    $this->viewData["btnGuardar"] = 'Actualizar';
                    $this->viewData["titulo"]  = sprintf(
                        $this->arrModeDesc[$this->viewData["mode"]],
                        $this->viewData["category_id"],
                    );
                    break;
                case 'DSP':
                    $this->viewData["btnGuardar"] = 'Regresar';
                    $this->viewData["readonly"] = true;
                    $this->viewData["titulo"]  = sprintf(
                        $this->arrModeDesc[$this->viewData["mode"]],
                        $this->viewData["category_id"],
                    );
                    break;
            }
        }   
    }
}
?>
