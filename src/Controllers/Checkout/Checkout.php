<?php

namespace Controllers\Checkout;

use Controllers\PublicController;
use Dao\Admin\Ventas as DaoVentas;



class Checkout extends PublicController{
    public function run():void
    {
        $viewData = array();
        $products= array();
        if ($this->isPostBack()) {
            $PayPalOrder = new \Utilities\Paypal\PayPalOrder(
                "test".(time() - 10000000),
                "http://localhost/E-commerceProject/index.php?page=checkout_error",
                "http://localhost/E-commerceProject/index.php?page=checkout_accept"
            );

            if(isset($_POST["btnEnviarVenta"]) && isset($_POST["product_id"]) && !isset($_POST["btnCancelar"])) {
                if(isset($_SESSION["productsVentas"])) {
                   
                    $products= $_SESSION["productsVentas"];
                    $customer= intval($_POST["cus_id"]);
                    $_SESSION["cus_id"]= $customer;
                    
                   foreach($products as $key => $value) {
                    $isv= ( $products[$key]["product_price"] * 0.15);
                error_log(json_decode($isv));
                    $description= $products[$key]["inventory_size"] . " " . $products[$key]["inventory_gender"];
                        $PayPalOrder->addItem(
                            $products[$key]["product_name"], 
                            $description, 
                            "PRD",
                            $products[$key]["product_price"],
                            $isv,
                            $products[$key]["quantity"],
                            "DIGITAL_GOODS"
                        );
                   }

                   $response = $PayPalOrder->createOrder();
                   $_SESSION["orderid"] = $response[1]->result->id;
                   \Utilities\Site::redirectTo($response[0]->href);
                   die();
                }
            
            } else {
                    
                if(isset($_POST["cus_id"]) && !isset($_POST["btnCancelar"])) {
                    $customer= intval($_POST["cus_id"]);
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin_venta&mode=INS&cus_id=".$customer,
                        "No ha agregado productos a la venta"
                    );
                }
            }


            if(isset($_POST["btnPlaceOrder"])) {
                if(isset($_SESSION["shopping_cart"])) {
                   
                    $products= $_SESSION["shopping_cart"];

                    error_log(json_encode($_SESSION["shopping_cart"]));
                   foreach($products as $key => $value) {
                    $isv= ( $products[$key]["product_price"] * 0.15);
                    $description= $products[$key]["inventory_size"];
                        $PayPalOrder->addItem(
                            $products[$key]["product_name"], 
                            $description, 
                            "PRD",
                            $products[$key]["product_price"],
                            $isv,
                            $products[$key]["quantity"],
                            "DIGITAL_GOODS"
                        );
                   }

                   $response = $PayPalOrder->createOrder();
                   $_SESSION["orderid"] = $response[1]->result->id;
                   \Utilities\Site::redirectTo($response[0]->href);
                   die();
                }
            
            } 

            if(isset($_POST["btnCancelar"])) {
                $_SESSION["productsVentas"]= array();
                \Utilities\Site::redirectTo("index.php?page=admin_ventas");
            }
            
        }

        \Views\Renderer::render("paypal/checkout", $viewData);
    }
}
?>