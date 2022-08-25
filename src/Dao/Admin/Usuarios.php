<?php

namespace Dao\Admin;
use Dao\Table;

class Usuarios extends Table{

    public static function getAll(){
        $sqlstr = "SELECT * FROM usuario";
        return self::obtenerRegistros($sqlstr, array());
    }

    public static function getById(int $usercod)
    {
        $sqlstr = "SELECT * FROM `usuario` WHERE usercod=:usercod;";
        $sqlParams = array("usercod" => $usercod);
        return self::obtenerUnRegistro($sqlstr, $sqlParams);
    }
    public static function insert(
        $useremail, $username, $userpswd ,$userest, $usertipo
    ) 
    { $sqlstr = "INSERT INTO `usuario`
        (
        `useremail`, `username`, `userpswd`, `userfching`, `userest`, `usertipo`
        )
        VALUES
        (
        :useremail, :username, :userpswd ,now(), :userest, :usertipo
        ); ";
        $sqlParams = [
            "useremail" => $useremail,
            "username" => $username,
            "userpswd" => $userpswd,
            "userest" => $userest,
            "usertipo" => $usertipo,
        ];
        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function update(
        $usercod,
        $useremail,
        $userpswd,
        $username,
        $userest, 
        $usertipo
    ) {
        $sqlstr = "UPDATE `usuario` set
        `useremail`=:useremail, `username`=:username, `userpswd`=:userpswd,
        `userest`=:userest, `usertipo`=:usertipo
        where `usercod` = :usercod;";
        $sqlParams = array(
            "usercod" => $usercod,
            "useremail" => $useremail,
            "username" => $username,
            "userpswd" => $userpswd,
            "userest" => $userest,
            "usertipo" => $usertipo
        );
        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function delete($usercod)
    {
        $sqlstr = "DELETE from `usuario` where usercod = :usercod;";
        $sqlParams = array( "usercod" => $usercod);
        return self::executeNonQuery($sqlstr, $sqlParams);
    }
}
?>