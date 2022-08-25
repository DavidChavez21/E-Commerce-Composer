<?php

namespace Dao\Admin;
use Dao\Table;

class Productos extends Table
{
    public static function getAllProducts()
    {
        $sqlstr = "SELECT * FROM products";
        return self::obtenerRegistros($sqlstr, array());
    }

    public static function getProductById($product_id)
    {
        $sqlstr = "SELECT * FROM products
        where product_id = :product_id";
        $sqlParams = array("product_id" => $product_id);
        return self::obtenerUnRegistro($sqlstr, $sqlParams);
    }

    public static function insertProduct(
            $product_image_url,
            $product_name,
            $product_description,
            $product_price,
            $product_discount,
            $category_id,
            $product_status,
        ) {
            $sqlstr = "INSERT INTO `products`
                (`product_name`, `product_description`, 
                 product_price, `product_status`,
                 product_discount, 
                `product_image_url`, category_id)
                VALUES
                (:product_name, :product_description,
                :product_price, :product_status, 
                :product_discount,
                :product_image_url, :category_id);
                ";
                
            $sqlParams = [
                "product_image_url" => $product_image_url ,
                "product_name" => $product_name ,
                "product_description" => $product_description ,
                "product_price" => $product_price ,
                "product_discount" =>  $product_discount ,
                "product_status" => $product_status,
                "category_id" => $category_id,
            ];
            return self::executeNonQuery($sqlstr, $sqlParams);
        }
    
    public static function updateProduct(
            $product_id,
            $product_image_url,
            $product_name,
            $product_description,
            $product_price,
            $product_discount,
            $category_id,
            $product_status,
        ) {
            $sqlstr = "UPDATE `products` set
                `product_name`=:product_name, `product_description`=:product_description,
                product_price=:product_price,`product_status`=:product_status, 
                product_discount=:product_discount, 
                `product_image_url`=:product_image_url, category_id=:category_id
                where product_id = :product_id;
                ";
            $sqlParams = array(
                "product_id" => $product_id,
                "product_name" => $product_name,
                "product_description" => $product_description,
                "product_price" => $product_price,
                "product_status" => $product_status,
                "product_discount" => $product_discount,
                "product_image_url" => $product_image_url,
                "category_id" => $category_id,
            );
            return self::executeNonQuery($sqlstr, $sqlParams);
        }
    
    public static function deleteProduct($product_id)
        {
            $sqlstr = "DELETE from `products` where product_id = :product_id;";
            $sqlParams = array(
                "product_id" => $product_id,
            );
            return self::executeNonQuery($sqlstr, $sqlParams);
        }
}
