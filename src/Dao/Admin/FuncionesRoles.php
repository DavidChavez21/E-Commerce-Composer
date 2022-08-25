<?php
namespace Dao\Admin;
use Dao\Security\Estados;

class FuncionesRoles extends \Dao\Table
{
    public static function getAll()
    {
        return self::obtenerRegistros("SELECT * FROM funciones_roles;",array());
    }

    public static function getOne($rolescod,$fncod)
    {
        $sqlstr="SELECT * FROM funciones_roles WHERE rolescod =:rolescod AND fncod =:fncod;";
        return self::obtenerUnRegistro($sqlstr, array("rolescod"=>$rolescod, "fncod"=>$fncod));
    }

    public static function insert($rolescod, $fncod)
    {
        $insstr="INSERT INTO funciones_roles VALUES (:rolescod,:fncod,:fnrolest,:fnexp);";
        return self::executeNonQuery(
            $insstr, 
            array(
                "rolescod"=>$rolescod,
                "fncod"=>$fncod,
                "fnrolest"=>Estados::ACTIVO,
                "fnexp"=>(date('Y-m-d', time()+155520000)) 
                )
            ); 
    }

    public static function update($rolescod, $fncod, $fnrolest, $fnexp)
    {
        $updsql = "UPDATE funciones_roles SET fnrolest=:fnrolest, fnexp=:fnexp WHERE rolescod=:rolescod,fncod=:fncod;";
        return self::executeNonQuery(
            $updsql,
            array(
                "fnrolest"=>$fnrolest,
                "fnexp"=>$fnexp,
                "rolescod"=>$rolescod,
                "fncod"=>$fncod
            )
        );
    }

    public static function delete($rolescod,$fncod)
    {
        $delsql="DELETE FROM funciones_roles WHERE rolescod=:rolescod AND fncod=:fncod;";
        return self::executeNonQUery(
            $delsql,
            array(
                "rolescod"=>$rolescod,
                "fncod"=>$fncod
            )
        );
    }

    static public function searchFuncionesRoles($UsuarioBusqueda)
    {
        $sqlstr="SELECT * FROM funciones_roles WHERE rolescod LIKE :UsuarioBusqueda OR fncod LIKE :UsuarioBusqueda
        fnrolest LIKE :UsuarioBusqueda OR fnexp LIKE :UsuarioBusqueda;";

        return self::obtenerRegistros($sqlstr, array("UsuarioBusqueda"=>"%".$UsuarioBusqueda."%"));
    }

    static public function getRoles()
    {
        return self::obtenerRegistros("SELECT * FROM roles WHERE rolesest='ACT';",array());
    }

    static public function getFunciones()
    {
        return self::obtenerRegistros("SELECT * FROM funciones WHERE fnest='ACT;", array());
    }
}
?>