<?php
namespace Dao\Admin;
use Dao\Table;

class funciones extends Table{
    public static function getAll(){
        $sqlstr = "SELECT * FROM funciones;";
        return self::obtenerRegistros($sqlstr, array());
    }

    public static function getById($fncod)
    {
        $sqlStr = "Select * From funciones where fncod =:fncod;";
        return self::obtenerUnRegistro($sqlStr,array("fncod"=>$fncod));
    }

    
    public static function insert($fncod, $fndsc,$fnest,$fntyp){
        $sqlStr = "INSERT INTO funciones (
            fncod, fndsc, fnest, fntyp
        ) 
        VALUES (
            :fncod, :fndsc, :fnest, :fntyp
        );";
        $parametros = array(
            "fncod" => $fncod,
            "fndsc" => $fndsc, 
            "fnest" => $fnest,
            "fntyp" => $fntyp
        );
        return self::executeNonQuery($sqlStr, $parametros);
    }

    public static function update($fncod, $fndsc,$fnest,$fntyp){
        $sqlStr = "UPDATE funciones set fndsc=:fndsc, fnest=:fnest, fntyp =:fntyp where fncod=:fncod;";
        $parametros = array(
            "fncod" => $fncod,
            "fndsc" => $fndsc,
            "fnest" => $fnest,
            "fntyp" => $fntyp
           
        );

        return self::executeNonQuery($sqlStr,$parametros);
    }

    
    public static function delete($fncod)
    {
        $sqlStr = "DELETE FROM funciones_roles WHERE fncod = :fncod; DELETE FROM funciones where fncod =:fncod;";
        $parametros = array(
            "fncod" => $fncod
        );

        return self::executeNonQuery($sqlStr,$parametros);
    }
   
}
?>