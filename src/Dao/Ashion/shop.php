<?php

namespace Dao\AShion;
use Dao\Table;

class Shop extends Table
{
    //--------------------------FIND ALL---------------------------//
    //FIND ALL PRODUCTS WITH A LIMIT
    public static function getAllProducts($offset, $limite)
    {
        $sqlstr = "SELECT * FROM products LIMIT :offset, :limite;";
        $sqlParams = array("offset" => $offset, "limite" => $limite);
        return self::obtenerRegistros($sqlstr, $sqlParams);
    }

    //FIND 6 CATEGORIES ONLY FOR DECORATION
    public static function getAllCategories()
    {
        $sqlstr = "SELECT * FROM categories LIMIT 2, 6;";
        return self::obtenerRegistros($sqlstr, array());
    }
    
    //--------------------------FIND---------------------------//
    //FIND CATEGORIES BY GENDER
    //FOR MALE
    public static function getMaleCategories()
    {
        $sqlstr = "SELECT DISTINCT(p.category_id),c.category_name,i.inventory_gender 
        FROM inventory AS i 
        LEFT JOIN products AS p ON i.product_id = p.product_id
        LEFT JOIN categories AS c ON p.category_id = c.category_id
        WHERE i.inventory_gender = 'Male';";
        return self::obtenerRegistros($sqlstr, array());
    }
    //FOR FEMALE
    public static function getFemaleCategories()
    {
        $sqlstr = "SELECT DISTINCT(p.category_id),c.category_name,i.inventory_gender 
        FROM inventory AS i 
        LEFT JOIN products AS p ON i.product_id = p.product_id
        LEFT JOIN categories AS c ON p.category_id = c.category_id
        WHERE i.inventory_gender = 'Female';";
        return self::obtenerRegistros($sqlstr, array());
    }

    //FIND THE MIN AND MAX PRICE
    public static function getPrices(){
        $sqlstr = "SELECT max(product_price) AS max_price, min(product_price) AS min_price FROM products;";
        return self::obtenerRegistros($sqlstr, array());
    }

    //FIND ALL SIZES 
    public static function getInventorySize(){
        $sqlstr = 'SELECT  DISTINCT(inventory_size) AS sizes FROM inventory;';
        return self::obtenerRegistros($sqlstr,array());
    }

    //--------------------------FILTER---------------------------//
    //FILTER PRODUCTS BY CATEGORY ID
    public static function getCategoryById($category_id)
    {
        $sqlstr = "SELECT * FROM categories c
         LEFT JOIN products AS p ON c.category_id = p.category_id
         WHERE c.category_id = :category_id;";
        $sqlParams = array("category_id" => $category_id);
        return self::obtenerRegistros($sqlstr, $sqlParams);
    }

    //FILTER PRODUCTS BY GENDER AND CATEGORY ID
    public static function getProductByGender($category_id,$gender){
        $sqlstr = "SELECT DISTINCT(p.product_name),p.product_id,p.category_id,c.category_name,
        i.inventory_gender,p.product_image_url FROM inventory AS i 
        LEFT JOIN products AS p ON i.product_id = p.product_id
        LEFT JOIN categories AS c ON p.category_id = c.category_id
        WHERE i.inventory_gender = :gender AND p.category_id = :category_id;";
        $sqlParams = array("category_id" => $category_id,"gender" => $gender);
        return self::obtenerRegistros($sqlstr,$sqlParams);
    }

    //FILTER PRODUCTS BY MIN AND MAX PRICE
    public static function getByPrices($min, $max){
        $sqlstr = "SELECT * FROM products WHERE product_price between :min AND :max + 1 LIMIT 0, 50;";
        $sqlParams = array("min" => $min, "max" => $max);

        return self::obtenerRegistros($sqlstr, $sqlParams);
    }

    //FILTER PRODUCTS BY SIZES
    public static function getBySizes($sizes){
        $sqlstr = " SELECT p.product_id, p.product_name, p.product_price, 
        p.product_image_url, p.category_id, i.inventory_gender, i.inventory_gender,
        i.inventory_size
        FROM inventory AS i 
        LEFT JOIN products AS p ON i.product_id = p.product_id
        WHERE i.inventory_size IN (:sizes)
        ORDER BY i.inventory_size LIMIT 0,50";
        $sqlParams = array("sizes" => $sizes);
        return self::obtenerRegistros($sqlstr, $sqlParams);
    }

    //---------------FILTER FOR SHOP DEATAILS-----------------//
     //FILTER PRODUCT BY ID
     public static function getProductById($id)
     {
         $sqlstr = "SELECT *,round((product_price - (product_price * product_discount)),2) AS discount 
         FROM products AS p 
         WHERE p.product_id = :id";
         $sqlParams = array("id" => $id);
         return self::obtenerRegistros($sqlstr, $sqlParams);
     }
 
     //FILTER PRODUCT SIZES BY PRODUCT ID
     public static function getSizesByProductId($id)
     {
         $sqlstr = "SELECT p.product_id, inventory_size FROM products AS p 
         RIGHT JOIN inventory AS i on p.product_id = i.product_id
         WHERE p.product_id = :id;";
         $sqlParams = array("id" => $id);
         return self::obtenerRegistros($sqlstr, $sqlParams);
     }
     //FILTER PRODUCT RELATED BY CATEGORY ID
     public static function getProductsByCategoryId($category_id,$product_id)
     {
         $sqlstr = "SELECT * FROM products WHERE category_id = :category_id AND not product_id = :product_id LIMIT 0,4;";
         $sqlParams = array("category_id" => $category_id,"product_id" => $product_id);
         return self::obtenerRegistros($sqlstr, $sqlParams);
     }
     
    //---------------FIND AND FILTER FOR INDEX-----------------//
    //FIND ALL CATEGORIES
    public static function getAllCategory()
    {
        $sqlstr = "SELECT * FROM categories";
        return self::obtenerRegistros($sqlstr, array());
    }

    //FIND PRODUCTS RANDOM
    public static function getHotTrends()
    {
        $sqlstr = "SELECT * FROM products ORDER BY rand() LIMIT 0,3;";
        return self::obtenerRegistros($sqlstr, array());
    }

    //FIND NEW PRODUCT (LAST THREE PRODUCTS) 
    public static function getThreeNewProduct()
    {
        $sqlstr = "SELECT * FROM products ORDER BY product_id DESC LIMIT 0,3;";
        return self::obtenerRegistros($sqlstr, array());
    }
    
    //FIND RANDOM PRODUCTS WITH DISCOUNT
    public static function getProductWithDiscounts()
    {
        $sqlstr = "SELECT *, round((product_price - (product_price * product_discount)),2) AS discount 
        FROM products WHERE product_discount > 0 ORDER BY rand() LIMIT 0,3;";
        return self::obtenerRegistros($sqlstr, array());
    }

    //FIND MAX STOCK AVAILABLE FOR SELLING
    public static function getMaxStock($id)
    {
        $sqlstr = "SELECT * FROM inventory WHERE product_id = :id";
        $sqlParams = array("id" => $id);
        return self::obtenerRegistros($sqlstr, $sqlParams); 
    }
}
