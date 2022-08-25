<?php
namespace Dao\Security;

if (version_compare(phpversion(), '7.4.0', '<')) {
        define('PASSWORD_ALGORITHM', 1);  //BCRYPT
} else {
    define('PASSWORD_ALGORITHM', '2y');  //BCRYPT
}
/*
usercod     bigint(10) AI PK
useremail   varchar(80)
username    varchar(80)
userpswd    varchar(128)
userfching  datetime
userpswdest char(3)
userpswdexp datetime
userest     char(3)
useractcod  varchar(128)
userpswdchg varchar(128)
userrole    char(3)
 */

use Exception;

class Security extends \Dao\Table
{
    static public function getUsuarios($filter = "", $page = -1, $items = 0)
    {
        $sqlstr = "";
        if ($filter == "" && $page == -1 && $items == 0) {
            $sqlstr = "SELECT * FROM usuario;";
        } else {
            //TODO: Terminar consultas FACET
            if ($page = -1 and $items = 0) {
                $sqlstr = sprintf("SELECT * FROM usuario %s;", $filter);
            } else {
                $offset = ($page -1 * $items);
                $sqlstr = sprintf(
                    "SELECT * FROM usuario %s limit %d, %d;",
                    $filter,
                    $offset,
                    $items
                );
            }
        }
        return self::obtenerRegistros($sqlstr, array());
    }

    static public function newUsuario($email, $password, $username, $usertipo)
    {
        if (!\Utilities\Validators::IsValidEmail($email)) {
            throw new Exception("Correo no es válido");
        }
        if (!\Utilities\Validators::IsValidPassword($password)) {
            throw new Exception("Contraseña debe ser almenos 8 caracteres, 1 número, 1 mayúscula, 1 símbolo especial");
        }

        $newUser = self::_usuarioStruct();
        //Tratamiento de la Contraseña
        $hashedPassword = self::_hashPassword($password);

        unset($newUser["usercod"]);
        unset($newUser["userfching"]);
        unset($newUser["userpswdchg"]);

        $newUser["useremail"] = $email;
        $newUser["username"] = $username;
        $newUser["userpswd"] = $hashedPassword;
        $newUser["userpswdest"] = Estados::ACTIVO;
        $newUser["userpswdexp"] = date('Y-m-d', time() + 7776000);  //(3*30*24*60*60) (m d h mi s)
        $newUser["userest"] = Estados::ACTIVO;
        $newUser["useractcod"] = hash("sha256", $email.time());
        $newUser["usertipo"] = $usertipo;

        $sqlIns = "INSERT INTO `usuario` (`useremail`, `username`, `userpswd`,
            `userfching`, `userpswdest`, `userpswdexp`, `userest`, `useractcod`,
            `userpswdchg`, `usertipo`)
            VALUES
            ( :useremail, :username, :userpswd,
            now(), :userpswdest, :userpswdexp, :userest, :useractcod,
            now(), :usertipo);";

        return self::executeNonQuery($sqlIns, $newUser);

    }
    /*
    static public function newUsuario($email, $password, $name, $phone, $phone2, $address, $bio, $gender)
    {
        if (!\Utilities\Validators::IsValidEmail($email)) {
            throw new Exception("Correo no es válido");
        }
        if (!\Utilities\Validators::IsValidPassword($password)) {
            throw new Exception("Contraseña debe ser almenos 8 caracteres, 1 número, 1 mayúscula, 1 símbolo especial");
        }

        $newUser = self::_usuarioStruct();
        //Tratamiento de la Contraseña
        $hashedPassword = self::_hashPassword($password);

        $newUser["useremail"]   = $email;
        $newUser["userpswd"]    = $hashedPassword;//(3*30*24*60*60) (m d h mi s)
        $newUser["username"]    = $name;
        $newUser["userphone"]   = $phone;
        $newUser["userphone2"]  = $phone2;
        $newUser["useraddress"] = $address;
        $newUser["userbio"]     = $bio;
        $newUser["userest"]     = Estados::ACTIVO;
        $newUser["userrole"]    = UsuarioTipo::PUBLICO;
        $newUser["usergender"]  = $gender;

        $sqlIns = "INSERT INTO usuario (useremail, userpswd, username, userphone, userphone2, useraddress, userbio, userest, userrole, usergender) 
                    VALUES( :useremail, :userpswd, :username, :userphone, :userphone2, :useraddress, :userbio, :userest, :userrole, :usergender);";

        return self::executeNonQuery($sqlIns, $newUser);

    }
    */

    static public function changePassword($usercod, $password){
        $hashedPassword = self::_hashPassword($password);

        $sqlstr = "UPDATE `usuario` set
        `userpswd` = :userpswd, 
        `userpswdest` = :userpswdest,
        `userpswdexp` = :userpswdexp
        where `usercod` = :usercod";
        $sqlParams = array(
            "usercod" => $usercod,
            "userpswd" => $hashedPassword,
            "userpswdest" => Estados::ACTIVO,
            "userpswdexp" => date('Y-m-d', time() + 7776000)
        );

        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    static public function newUsuarioRol($userId)
    {

        $newUserRol = self::_usuarioRolStruct();

        $newUserRol["usercod"]   = $userId;
        $newUserRol["rolescod"]    = 1;
        $newUserRol["roleuserest"]    = Estados::ACTIVO;
        $newUserRol["roleuserfch"]   = date("Y-m-d");
        $newUserRol["roleuserexp"]  = date("Y-m-d", strtotime('+5 years'));

        $sqlIns = "INSERT INTO roles_usuarios (usercod, rolescod, roleuserest, roleuserfch, roleuserexp)
                    VALUES( :usercod, :rolescod, :roleuserest, :roleuserfch, :roleuserexp);";

        return self::executeNonQuery($sqlIns, $newUserRol);

    }

    static public function deleteUsuario($email)
    {
        $sqlstr = "DELETE FROM usuario WHERE useremail = :useremail";
        $sqlParams = array("useremail" => $email);

        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    static public function getUsuarioByEmail($email)
    {
        $sqlstr = "SELECT * from usuario where useremail = :useremail ;";
        $params = array("useremail"=>$email);

        return self::obtenerUnRegistro($sqlstr, $params);
    }

    static private function _saltPassword($password)
    {
        return hash_hmac(
            "sha256",
            $password,
            \Utilities\Context::getContextByKey("PWD_HASH")
        );
    }

    static private function _hashPassword($password)
    {
        return password_hash(self::_saltPassword($password), PASSWORD_ALGORITHM);
    }

    static public function verifyPassword($raw_password, $hash_password)
    {
        return password_verify(
            self::_saltPassword($raw_password),
            $hash_password
        );
    }

    static private function _usuarioStruct()
    {
        return array(
            "usercod"      => "",
            "useremail"    => "",
            "username"     => "",
            "userpswd"     => "",
            "userfching"   => "",
            "userpswdest"  => "",
            "userpswdexp"  => "",
            "userest"      => "",
            "useractcod"   => "",
            "userpswdchg"  => "",
            "usertipo"     => "",

        );
    }

    static private function _usuarioRolStruct()
    {
        return array(
            "usercod"      => "",
            "rolescod"     => "",
            "roleuserest"  => "",
            "roleuserfch"  => "",
            "roleuserexp"  => "",
        );
    }

    static public function getFeature($fncod)
    {
        $sqlstr = "SELECT * from funciones where fncod=:fncod;";
        $featuresList = self::obtenerRegistros($sqlstr, array("fncod"=>$fncod));
        return count($featuresList) > 0;
    }

    static public function addNewFeature($fncod, $fndsc, $fnest, $fntyp )
    {
        $sqlins = "INSERT INTO funciones (fncod, fndsc, fnest, fntyp)
            VALUES (:fncod , :fndsc , :fnest , :fntyp );";

        return self::executeNonQuery(
            $sqlins,
            array(
                "fncod" => $fncod,
                "fndsc" => $fndsc,
                "fnest" => $fnest,
                "fntyp" => $fntyp
            )
        );
    }

    static public function getFeatureByUsuario($userCod, $fncod)
    {
        $sqlstr = "select * from
        funciones_roles a inner join roles_usuarios b on a.rolescod = b.rolescod
        where a.fnrolest = 'ACT' and b.roleuserest='ACT' and b.usercod=:usercod
        and a.fncod=:fncod limit 1;";
        $resultados = self::obtenerRegistros(
            $sqlstr,
            array(
                "usercod"=> $userCod,
                "fncod" => $fncod
            )
        );
        return count($resultados) > 0;
    }

    static public function getRol($rolescod)
    {
        $sqlstr = "SELECT * from roles where rolescod=:rolescod;";
        $featuresList = self::obtenerRegistros($sqlstr, array("rolescod" => $rolescod));
        return count($featuresList) > 0;
    }

    static public function addNewRol($rolescod, $rolesdsc, $rolesest)
    {
        $sqlins = "INSERT INTO roles (rolescod, rolesdsc, rolesest)
        VALUES (:rolescod, :rolesdsc, :rolesest);";

        return self::executeNonQuery(
            $sqlins,
            array(
                "rolescod" => $rolescod,
                "rolesdsc" => $rolesdsc,
                "rolesest" => $rolesest
            )
        );
    }

    static public function isUsuarioInRol($userCod, $rolescod)
    {
        $sqlstr = "select * from roles a inner join
        roles_usuarios b on a.rolescod = b.rolescod where a.rolesest = 'ACT'
        and b.usercod=:usercod and a.rolescod=:rolescod limit 1;";
        $resultados = self::obtenerRegistros(
            $sqlstr,
            array(
                "usercod" => $userCod,
                "rolescod" => $rolescod
            )
        );
        return count($resultados) > 0;
    }

    static public function getRolesByUsuario($userCod)
    {
        $sqlstr = "select * from roles a inner join
        roles_usuarios b on a.rolescod = b.rolescod where a.rolesest = 'ACT'
        and b.usercod=:usercod;";
        $resultados = self::obtenerRegistros(
            $sqlstr,
            array(
                "usercod" => $userCod
            )
        );
        return $resultados;
    }

    static public function removeRolFromUser($userCod, $rolescod)
    {
        $sqldel = "UPDATE roles_usuarios set roleuserest='INA' 
        where rolescod=:rolescod and usercod=:usercod;";
        return self::executeNonQuery(
            $sqldel,
            array("rolescod"=>$rolescod, "usercod"=>$userCod)
        );
    }

    static public function removeFeatureFromRol($fncod, $rolescod)
    {
        $sqldel = "UPDATE funciones_roles set roleuserest='INA'
        where fncod=:fncod and rolescod=:rolescod;";
        return self::executeNonQuery(
            $sqldel,
            array("fncod" => $fncod, "rolescod" => $rolescod)
        );
    }
    static public function getUnAssignedFeatures($rolescod)
    {
        
    }
    static public function getUnAssignedRoles($userCod)
    {

    }
    private function __construct()
    {
    }
    private function __clone()
    {
    }
}


?>