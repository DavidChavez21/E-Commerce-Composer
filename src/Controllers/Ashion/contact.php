<?php
namespace Controllers\Ashion;

use Controllers\PublicController;
use Views\Renderer;
use Dao\AShion\Shop;

class Contact extends PublicController
{   
    public function run() :void
    {
        $this->viewData['Categories'] = Shop::getAllCategories();
        $this->viewData['QuantityProducts'] = $this->getQuantityProducts();
        Renderer::render('ashion/contact', $this->viewData);
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
