<?php
namespace Controllers\Ashion;

use Controllers\PublicController;
use Views\Renderer;
use Dao\AShion\Shop;

class ProductDetails extends PublicController
{
    private $viewData = array();
    private $_productShoppingCarStructure = array(
        "product_id" => "",
        "product_name" => "",
        "product_price" => 0,
        "product_image" => "",
        "quantity" => 0,
        "inventory_size" => "",
        "total_price" => 0
    );

    private $maxStockbySizeStructure = array(
        "size" => "",
        "max_stock" => 0
    );

    public function __construct()
    {
        
    }

    public function run() :void
    {
        if(isset($_GET['product_id']) && isset($_GET['category_id'])){
            $this->viewData['Product'] = Shop::getProductById($_GET['product_id']);
            $this->viewData['Sizes'] = Shop::getSizesByProductId($_GET['product_id']);
            $this->viewData['Products'] = Shop::getProductsByCategoryId($_GET['category_id'],$_GET['product_id']);
            $this->viewData['Categories'] = Shop::getAllCategories();
            error_log(json_encode($this->viewData));

            if(isset($_POST['addToCart'])){
                $this->addToCart();
            }

            $this->viewData['QuantityProducts'] = $this->getQuantityProducts();
            $this->viewData['maxStockbyProduct'] = $this->getMaxStock($_GET['product_id']);

            Renderer::render('ashion/productdetails', $this->viewData);
        }

    }

    private function addToCart()
    {
        $productShoppingCar = $this->_productShoppingCarStructure;
        
        if(!isset($_SESSION['shopping_cart'])){
            $productShoppingCar['product_id'] = $_GET['product_id'];
            $productShoppingCar['product_name'] = $_POST['product_name'];
            $productShoppingCar['product_price'] = $_POST['product_price'];
            $productShoppingCar['product_image'] = $_POST['product_image_url'];
            $productShoppingCar['quantity'] = $_POST['quantity'];
            $productShoppingCar['inventory_size'] = $_POST['size'];
            $productShoppingCar['total_price'] = floatval($_POST['product_price']) * floatval($_POST['quantity']);
            $_SESSION['shopping_cart'][] = $productShoppingCar;
        }
        else
        {
            $exist = false;
            foreach($_SESSION['shopping_cart'] as $key => $value){
                if($value['product_id'] == $_GET['product_id'] && $value['inventory_size'] == $_POST['size']){
                    $exist = true;
                    $_SESSION['shopping_cart'][$key]['quantity'] += $_POST['quantity'];
                    $_SESSION['shopping_cart'][$key]['total_price'] += floatval($_POST['product_price']) * floatval($_POST['quantity']);
                }
            }
            if(!$exist){
                $productShoppingCar['product_id'] = $_GET['product_id'];
                $productShoppingCar['product_name'] = $_POST['product_name'];
                $productShoppingCar['product_price'] = $_POST['product_price'];
                $productShoppingCar['product_image'] = $_POST['product_image_url'];
                $productShoppingCar['quantity'] = $_POST['quantity'];
                $productShoppingCar['inventory_size'] = $_POST['size'];
                $productShoppingCar['total_price'] = floatval($_POST['product_price']) * floatval($_POST['quantity']);
                $_SESSION['shopping_cart'][] = $productShoppingCar;
            }
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

    private function getMaxStock($id)
    {
        $maxStockbySize = $this->maxStockbySizeStructure;
        $maxStockfromDB = Shop::getMaxStock($id);

        foreach ($maxStockfromDB as $size) {
            $maxStockbySize['size'] = $size['inventory_size'];
            $maxStockbySize['max_stock'] = $size['product_stock'];
            $maxStock[] = $maxStockbySize;
        }

        //Disminuir los productos que se encuentran en la session 
        if(isset($_SESSION['shopping_cart'])){
            foreach($_SESSION['shopping_cart'] as $product){
                foreach($maxStock as $key => $value){
                    if($product['inventory_size'] == $value['size'] && $id == $product['product_id']){
                        $maxStock[$key]['max_stock'] -= $product['quantity'];
                    }
                }
            }
        }

        return $maxStock;
    }
}
