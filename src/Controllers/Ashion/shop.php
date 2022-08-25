<?php
namespace Controllers\Ashion;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Ashion\Shop as DaoShop;

class Shop extends PublicController
{
    private $viewData = array();

    private $paginaActual;
    private $totalPaginas;
    private $nResultados;
    private $resultadosPorPagina = 6;
    private $indice;

    function __construct()
    {
        parent::__construct();
        $this->indice = 0;
        $this->paginaActual = 1;
        $this->calcularPaginas();
    }

    private function calcularPaginas(){
        $this->nResultados = 100;
        $this->totalPaginas = $this->nResultados / $this->resultadosPorPagina;

        if(isset($_GET['position'])){
            if(is_numeric($_GET['position'])){
                if($_GET['position'] >= 1 && $_GET['position'] <= $this->totalPaginas){
                    $this->paginaActual = $_GET['position'];
                    $this->indice = ($this->paginaActual - 1) * ($this->resultadosPorPagina);
                }
            }else {
                header('Location: index.php?page=ashion_shop');
            }
        }else{
            $this->resultadosPorPagina = 6;
        }
    }

    public function run() :void
    {
        if($this->isPostBack()){
            if(isset($_POST['btnFiltrar'])){
                $this->findByPrices();
            }
            if(isset($_POST['rbtFiltrar'])){
                $this->findBySizes();
            }
        }else{
            if(isset($_GET['category_id'])){
                $this->findByCategory($_GET['category_id']);   
            }          
            else {
                $this->findProducts($this->indice,$this->resultadosPorPagina);
            }

        }
        $this->filters();
        $this->viewData['Categories'] = DaoShop::getAllCategories();
        error_log(json_encode($this->viewData));
        $this->viewData['QuantityProducts'] = $this->getQuantityProducts();
        Renderer::render('ashion/shop', $this->viewData);
    }

    private function findProducts($offset,$limite){
        $this->viewData["Shops"] = DaoShop::getAllProducts($offset,$limite);
    }

    private function filters(){
        $this->viewData["Women"] = DaoShop::getFemaleCategories();
        $this->viewData["Men"] = DaoShop::getMaleCategories();
        $this->viewData["All"] = DaoShop::getAllCategory();
        $this->viewData["Prices"] = DaoShop::getPrices();
        $this->viewData["Sizes"] = DaoShop::getInventorySize();
    }

    private function findByCategory($category_id){
        if(isset($_GET['gender'])){
            $gender = $_GET['gender'];
            if($gender == 'Male'){
                $this->viewData["Shops"] = DaoShop::getProductByGender($category_id,$gender);
            }
            if($gender == 'Female'){
                $this->viewData["Shops"] = DaoShop::getProductByGender($category_id,$gender);
            }
        } else {
            $this->viewData["Shops"] = DaoShop::getCategoryById($category_id);
        }
    }

    private function findByPrices(){
        $this->viewData["min_price"] = intval(str_replace('$','',$_POST["minamount"]));
        $this->viewData["max_price"] = intval(str_replace('$','',$_POST["maxamount"]));
        $this->viewData["Shops"] = DaoShop::getByPrices($this->viewData['min_price'],$this->viewData['max_price']);
    }

    private function findBySizes(){
        if(isset($_POST['sizes'])){
            $sizes = $_POST['sizes'];
            $this->viewData["Shops"] = DaoShop::getBySizes($sizes);
        }
    }

    private function getQuantityProducts()
    {
        $quantity = 0;
        if(isset($_SESSION['shopping_cart'])){
            foreach($_SESSION['shopping_cart'] as $product){
                $quantity++;
            }
        }
        return $quantity;
    }
}
?>
