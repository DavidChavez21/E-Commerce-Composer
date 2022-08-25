<?php
namespace Dao\Admin;
use Dao\Security\Estados;

class Roles_Usuarios extends \Dao\Table
{
    public static function getAll()
    {
        return self::obtenerRegistros("SELECT ru.*, u.username, u.useremail, u.usertipo 
        FROM roles_usuarios ru INNER JOIN usuario u ON ru.usercod=u.usercod WHERE u.usertipo != 'PBL';", array());
    }

    public static function getOne($usercod,$rolescod)
    {
        $sqlstr="SELECT ru.*, u.username, u.useremail, u.usertipo 
        FROM roles_usuarios ru INNER JOIN usuario u 
        ON ru.usercod=u.usercod 
        WHERE ru.usercod=:usercod AND rolescod=:rolescod;";
        return self::obtenerUnRegistro($sqlstr, array("usercod"=>$usercod,"rolescod"=>$rolescod));
    }

    public static function insert($usercod, $rolescod)
    {
        $insstr = "INSERT INTO roles_usuarios VALUES (:usercod, :rolescod, :roleuserest, NOW(), :roleuserexp);";
        return self::executeNonQuery(
            $insstr,
            array("usercod"=>$usercod, "rolescod"=>$rolescod, "roleuserest"=>Estados::ACTIVO, "roleuserexp"=>(date('Y-m-d', time() + 155520000)))  //5*12*30*24*60*60 (y m d h mi s))
        );
    }

    public static function update($usercod, $rolescod, $roleuserest, $roleuserexp)
    {
        $updsql = "UPDATE roles_usuarios SET roleuserest=:roleuserest, roleuserexp=:roleuserexp WHERE usercod=:usercod AND rolescod=:rolescod;";
        return self::executeNonQuery(
            $updsql,
            array("roleuserest" => $roleuserest, "roleuserexp" => $roleuserexp, "usercod"=>$usercod, "rolescod" => $rolescod,)
        );
    }

    public static function delete($usercod, $rolescod)
    {
        $delsql = "DELETE FROM roles_usuarios WHERE usercod=:usercod AND rolescod=:rolescod;";
        return self::executeNonQuery(
            $delsql,
            array("usercod" => $usercod, "rolescod" => $rolescod)
        );
    }

    static public function searchRolesUsuarios($UsuarioBusqueda)
    {
        $sqlstr = "SELECT ru.*, u.username, u.useremail, u.usertipo FROM roles_usuarios ru 
        INNER JOIN usuarios u ON ru.usercod = u.usercod WHERE ru.usercod LIKE :UsuarioBusqueda 
        OR username LIKE :UsuarioBusqueda OR useremail LIKE :UsuarioBusqueda OR usertipo LIKE :UsuarioBusqueda
        OR rolescod LIKE :UsuarioBusqueda OR roleuserest LIKE :UsuarioBusqueda 
        OR roleuserfch LIKE :UsuarioBusqueda OR roleuserexp LIKE :UsuarioBusqueda;";
        
        return self::obtenerRegistros($sqlstr, array("UsuarioBusqueda"=>"%".$UsuarioBusqueda."%"));
    }

    static public function getUsuarios()
    {
        return self::obtenerRegistros("SELECT * FROM usuario WHERE usertipo!='PBL';", array());
    }

    static public function getRoles()
    {
        return self::obtenerRegistros("SELECT * FROM roles WHERE RolEst = 'ACT';", array());
    }
}
?>