<?php
namespace Controllers\Ashion;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Admin\Ventas as DaoVentas;
use Dao\AShion\Shop;

class Checkout extends PublicController
{   

    private $viewData= array();

    public function run() :void
    {
        $subtotal= 0;
        $user= "";
        $this->viewData['Categories'] = Shop::getAllCategories();
        $this->viewData['QuantityProducts'] = $this->getQuantityProducts();
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

        if(!$this->isPostBack()) {
            error_log(json_encode($_SESSION["shopping_cart"]));
        if( \Utilities\Security::isLogged()) {
            if(isset($_SESSION["shopping_cart"]))
            {
                if(!empty($_SESSION["shopping_cart"])) {
                    $this->viewData["ProductosShoppingCart"]= $_SESSION["shopping_cart"];
                    $user= \Utilities\Security::getUserId();
                    $customerInfo= DaoVentas::getInfoCustomer($user);
                    foreach($this->viewData["ProductosShoppingCart"] as $key => $value) {
                        $subtotal+= $value["total_price"];
                    }

                    $this->viewData["subtotal"]= $subtotal;
                    $this->initInfoCustomer();
                    \Utilities\ArrUtils::mergeArrayTo($customerInfo, $this->viewData);
                    
                }
                else {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=ashion_shop",
                        "No tiene productos agregados al carrito"
                    );
                }
            } else  {
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=ashion_shop",
                    "No tiene productos agregados al carrito"
                );
            }
        } else {
            \Utilities\Site::redirectToWithMsg(
                "index.php?page=sec_login",
                "Debe de iniciar Sesion o crear una cuenta"
            );
        }
    }

        Renderer::render('ashion/checkout', $this->viewData);
    }

    public function initInfoCustomer() {
        $this->viewData["customer_name"]= "";
        $this->viewData["customer_lastname"]= "";
        $this->viewData["customer_address"]= "";
        $this->viewData["customer_postal_code"]= "";
        $this->viewData["customer_country"]= "";
        $this->viewData["customer_city"]= "";
        $this->viewData["customer_phone_number"]= "";
        $this->viewData["useremail"]= "";
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