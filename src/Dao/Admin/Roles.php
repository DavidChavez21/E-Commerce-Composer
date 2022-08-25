<?php 
namespace Dao\Admin;
use Dao\Table;
class Roles extends Table
{

    public static function getAll()
    {
        $sqlstr = "SELECT * FROM roles;";
        return self::obtenerRegistros($sqlstr, array());
    }

    public static function getById($rolescod)
    {
        $sqlstr = "SELECT * FROM `roles` WHERE `rolescod` = :rolescod;";
        $sqlParams = array(
            'rolescod' => $rolescod
        );
        return self::obtenerUnRegistro($sqlstr, $sqlParams);
    }

    public static function insert(
        $rolescod,
        $rolesdsc,
        $rolesest
    ) {
        $sqlstr = "INSERT INTO `roles`
            (`rolescod`,
            `rolesdsc`,
            `rolesest`)
            VALUES
            (:rolescod,
            :rolesdsc,
            :rolesest);
            ";

        $sqlParams = array(
            'rolescod' => $rolescod,
            'rolesdsc' => $rolesdsc,
            'rolesest' => $rolesest
        );

        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function update(
        $rolescod,
        $rolesdsc,
        $rolesest
    ) {
        $sqlstr = "UPDATE `roles`
            SET
            `rolesdsc` = :rolesdsc,
            `rolesest` = :rolesest
            WHERE `rolescod` = :rolescod;
            ";

        $sqlParams = array(
            'rolescod' => $rolescod,
            'rolesdsc' => $rolesdsc,
            'rolesest' => $rolesest
        );

        return self::executeNonQuery($sqlstr, $sqlParams);
    }

    public static function delete($rolescod)
    {
        $sqlstr = "DELETE FROM `roles` WHERE `rolescod` = :rolescod;";
        $sqlParams = array(
            'rolescod' => $rolescod
        );
        return self::executeNonQuery($sqlstr, $sqlParams);
    }

}

?>