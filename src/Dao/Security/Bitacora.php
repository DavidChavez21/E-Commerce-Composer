<?php
namespace Dao\Security;

Class Bitacora extends \Dao\Table
{
    public static function insert($bitprograma, $bitdescripcion, $bitTipo, $bitusuario)
    {
        $insstr = "INSERT INTO bitacora (BitacoraFch, bitprograma, bitdescripcion, bitTipo, bitusuario) values (NOW(), :bitprograma, :bitdescripcion, :bitTipo, :bitusuario);";
        return self::executeNonQuery(
            $insstr,
            array(
                "bitprograma"=>$bitprograma, 
                "bitdescripcion"=>$bitdescripcion, 
                "bitTipo"=>$bitTipo,
                "bitusuario"=>$bitusuario
            )
        );
    }
}
?>