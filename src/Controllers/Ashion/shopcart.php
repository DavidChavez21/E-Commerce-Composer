<?php
namespace Controllers\Ashion;

use Controllers\PublicController;
use Views\Renderer;
use Dao\AShion\Shop;

class ShopCart extends PublicController
{
    private $viewData = array();



    public function run() :void
    {
        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] == "PBL"){
                $this->viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $this->viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $this->viewData["logeado"]=false;
            }
        }
        else 
        {
            $this->viewData["logeado"]=true;
        }
        
        $this->viewData['Categories'] = Shop::getAllCategories();
        if(!isset($_SESSION['shopping_cart']))
        {
            $this->viewData['Products'] = array();
        }
        else
        {
            if(isset($_POST['btnDelete']))
            {
                $this->deleteProduct();
            }
            if(isset($_POST['btnUpdate']))
            {
                $this->updateProduct();
            }

            $this->viewData['Total'] = $this->getTotal();


            $this->viewData['Products'] = $_SESSION['shopping_cart'];
        }

        $this->viewData['QuantityProducts'] = $this->getQuantityProducts();

        Renderer::render('ashion/shopcart', $this->viewData);
        
    }

    private function deleteProduct()
    {
        foreach($_SESSION['shopping_cart'] as $key => $product)
        {
            if($product['product_id'] == $_POST['product_id'] && $product['inventory_size'] == $_POST['inventory_size'])
            {
                unset($_SESSION['shopping_cart'][$key]);
            }
        }

        $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);

    }

    private function updateProduct()
    {
        for($i = 0; $i < count($_SESSION['shopping_cart']); $i++)
        { 
                $_SESSION['shopping_cart'][$i]['quantity'] = $_POST['newQuantity'.$_SESSION['shopping_cart'][$i]['product_id'].$_SESSION['shopping_cart'][$i]['inventory_size']];
                $_SESSION['shopping_cart'][$i]['total_price'] = floatval($_SESSION['shopping_cart'][$i]['product_price']) * floatval($_POST['newQuantity'.$_SESSION['shopping_cart'][$i]['product_id'].$_SESSION['shopping_cart'][$i]['inventory_size']]);  
        }
    }

    private function getTotal()
    {
        $total = 0;
        foreach($_SESSION['shopping_cart'] as $product)
        {
            $total += $product['total_price'];
        }
        return $total;
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
