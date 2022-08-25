<?php 

namespace Dao\Admin;

use Dao\Table;

class Inventario extends Table
{
    public static function getAll()
    {
        $sqlstr = "SELECT p.product_id as Inventario, p.product_name as Producto,
        i.inventory_size as Talla, i.inventory_gender as Genero, i.product_stock as Stock
        FROM inventory as i inner join products as p on i.product_id = p.product_id;";

        return self::obtenerRegistros($sqlstr, array());
    }

    public static function insertInventory($product_id, $inventory_size, $inventory_gender, $product_stock)
    {
        $sqlstr = "INSERT INTO inventory (product_id, inventory_size, inventory_gender, product_stock) VALUES (:product_id, :inventory_size, :inventory_gender, :product_stock);";
            
        $sqlParams = array("product_id" => $product_id, "inventory_size" => $inventory_size, "inventory_gender" => $inventory_gender, "product_stock" => $product_stock);
   
        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function updateInventory($inventory_id,$product_id,$inventory_size,$product_stock){
        $sqlstr = "UPDATE inventory SET product_id = :product_id, inventory_size = :inventory_size, product_stock = :product_stock WHERE inventory_id = :inventory_id;";
        $sqlParams = array("inventory_id" => $inventory_id, "product_id" => $product_id, "inventory_size" => $inventory_size, "product_stock" => $product_stock);
        return self::executeNonQuery($sqlstr, $sqlParams);
    }
    public static function deleteInventory($inventory_id)
    {
        $sqlstr = "DELETE FROM inventory WHERE inventory_id = :inventory_id;";
        $sqlParams = array(
            "inventory_id" => $inventory_id
        );
            return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function getById($inventory_id)
    {
        $sqlstr = "SELECT * FROM inventory WHERE inventory_id = :inventory_id;";
        $sqlParams = array(
            "inventory_id" => $inventory_id
        );

        return self::obtenerUnRegistro($sqlstr, $sqlParams);
    }

    public static function verifyProductSize($product_id, $inventory_size)
    {
        $sqlstr = "SELECT * FROM inventory WHERE product_id = :product_id AND inventory_size = :inventory_size;";
        $sqlParams = array(
            "product_id" => $product_id,
            "inventory_size" => $inventory_size
        );

        return self::obtenerRegistros($sqlstr, $sqlParams);
    }

}

?>