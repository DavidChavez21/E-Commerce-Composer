<?php

    namespace Dao\Mnt;

    class Clientes extends \Dao\Table
    {
        static public function addCustomer(
            $customer_name, $customer_lastname, $customer_address, $customer_postal_code, 
            $customer_country, $customer_city, $customer_phone_number, $email
        ){
            $sqlstr = "INSERT INTO customers 
            (customer_name, customer_lastname, customer_address, customer_postal_code, 
            customer_country, customer_city, customer_phone_number, usercod) VALUES 
            (:customer_name, :customer_lastname, :customer_address, :customer_postal_code, 
            :customer_country, :customer_city, :customer_phone_number, :usercod)";
            $sqlparams = array(
                "customer_name" => $customer_name,
                "customer_lastname" => $customer_lastname,
                "customer_address" => $customer_address,
                "customer_postal_code" => $customer_postal_code,
                "customer_country" => $customer_country,
                "customer_city" => $customer_city,
                "customer_phone_number" => $customer_phone_number,
                "usercod" => intval(self::getUserId($email))
            );

            return self::executeNonQuery($sqlstr, $sqlparams);
        }

        static public function deleteCustomer($customer_id){
            $sqlstr = "DELETE FROM customers WHERE customer_id = :customer_id";
            $sqlparams = array(
                "customer_id" => intval($customer_id)
            );

            return self::executeNonQuery($sqlstr, $sqlparams);
        }

        static public function getCustomer($id){
            $sqlstr = "SELECT * FROM customers WHERE customer_id = :customer_id";
            $sqlParams = array("customer_id" => $id);

            return self::obtenerUnRegistro($sqlstr, $sqlParams);
        }

        static public function getAllCustomer(){
            $sqlstr = "SELECT * FROM customers";
            
            return self::obtenerRegistros($sqlstr, array());
        }

        static public function updateCustomer(
            $customer_name, $customer_lastname, $customer_address, $customer_postal_code, 
            $customer_country, $customer_city, $customer_phone_number, $customer_id
        ){
            $sqlstr = "UPDATE customers SET
            customer_name = :customer_name,
            customer_lastname = :customer_lastname,
            customer_address = :customer_address,
            customer_postal_code = :customer_postal_code,
            customer_country = :customer_country,
            customer_city = :customer_city,
            customer_phone_number = :customer_phone_number
            WHERE customer_id = :customer_id";

            $sqlParams = array(
                "customer_name" => $customer_name,
                "customer_lastname" => $customer_lastname,
                "customer_address" => $customer_address,
                "customer_postal_code" => $customer_postal_code,
                "customer_country" => $customer_country,
                "customer_city" => $customer_city,
                "customer_phone_number" => $customer_phone_number,
                "customer_id" => $customer_id
            );
            
            return self::executeNonQuery($sqlstr ,$sqlParams);
        }

        static public function getUserId($email){
            $sqlstr = "SELECT MAX(usercod) as codigo FROM usuario 
            WHERE useremail = :useremail";
            $sqlParams = array(
                "useremail" => $email
            );

            $cod = self::obtenerUnRegistro($sqlstr, $sqlParams);

            return $cod["codigo"];
        }

        static public function getCustomerId($usercod){
            $sqlstr = "SELECT customer_id FROM customers 
            WHERE usercod = :usercod";
            $sqlParams = array(
                "usercod" => $usercod
            );

            $cod = self::obtenerUnRegistro($sqlstr, $sqlParams);

            return $cod["customer_id"];
        }

        static public function getSaleByCustomer($customer_id){
            $sqlstr = "SELECT sale_id, sale_date, ROUND((sale_isv * sale_subtotal),2) as sale_isv, sale_subtotal , ROUND((sale_subtotal + sale_subtotal * sale_isv),2) as total 
            FROM sales WHERE customer_id = :customer_id";
            $sqlParams = array("customer_id" => $customer_id);

            return self::obtenerRegistros($sqlstr, $sqlParams);
        }

        static public function getSaleById($sale_id){
            $sqlstr = "SELECT sale_id, sale_date, ROUND((sale_isv * sale_subtotal),2) as sale_isv, sale_subtotal , ROUND((sale_subtotal + sale_subtotal * sale_isv),2) as total 
            FROM sales WHERE sale_id = :sale_id";
            $sqlParams = array("sale_id" => $sale_id);

            return self::obtenerUnRegistro($sqlstr, $sqlParams);
        }

        static public function getSaleDetail($sale_id){
            $sqlstr = "SELECT sd.product_id, p.product_name, sd.sale_price, sd.sale_quantity, round((sd.sale_price * sd.sale_quantity),2) AS importe
            FROM sales_details sd JOIN products p
            WHERE sd.product_id = p.product_id AND sale_id = :sale_id;";
            $sqlParams = array("sale_id" => $sale_id);

            return self::obtenerRegistros($sqlstr, $sqlParams);
        }
        
    }

?>