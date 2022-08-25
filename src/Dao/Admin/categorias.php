<?php

namespace Dao\Admin;
use Dao\Table;

class Categorias extends Table
{
    public static function getAllCategories()
    {
        $sqlstr = "SELECT * FROM categories";
        return self::obtenerRegistros($sqlstr, array());
    }

    public static function getCategoryById($category_id)
    {
        $sqlstr = "SELECT category_id,category_name,category_image_url FROM categories where category_id = :category_id";
        $sqlParams = array("category_id" => $category_id);
        return self::obtenerUnRegistro($sqlstr, $sqlParams);
    }

    public static function insertCategory(
            $category_name,
            $category_image_url,
        ) {
            $sqlstr = "INSERT INTO `categories`
                (`category_name`,
                `category_image_url`)
                VALUES
                (:category_name,
                :category_image_url);
                ";
            $sqlParams = [
                "category_name" => $category_name ,
                "category_image_url" =>  $category_image_url ,
            ];
            return self::executeNonQuery($sqlstr, $sqlParams);
        }
    
    public static function updateCategory(
            $category_id,
            $category_name,
            $category_image_url,
        ) {
            $sqlstr = "UPDATE `categories` set
                `category_name`=:category_name, 
                `category_image_url`=:category_image_url
                where category_id = :category_id;";
            $sqlParams = array(
                "category_id" => $category_id,
                "category_name" => $category_name,
                "category_image_url" => $category_image_url,
            );
            return self::executeNonQuery($sqlstr, $sqlParams);
        }
    
    public static function deleteCategory($category_id)
        {
            $sqlstr = "DELETE from `categories` where category_id = :category_id;";
            $sqlParams = array(
                "category_id" => $category_id
            );
            return self::executeNonQuery($sqlstr, $sqlParams);
        }
}
