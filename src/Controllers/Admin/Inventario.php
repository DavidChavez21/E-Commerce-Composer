<?php

namespace Controllers\Admin;

use Controllers\PrivateController;
use Views\Renderer;


class Inventario extends PrivateController
{
    private $viewData = array();

    public function run(): void
    {
        $this->init();

        if (\Utilities\Security::isLogged()) {
            if ($_SESSION["login"]["usertipo"] !== "PBL") {
                $this->viewData["isLogged"] = $_SESSION["login"]["usertipo"];
                $this->viewData["usernameappear"] = $_SESSION["login"]["userName"];
                $this->viewData["logeado"] = false;
            }
        } else {
            $this->viewData["logeado"] = true;
        }

        if (!$this->isPostBack()) {
            $this->procesarGet();
        }

        if ($this->isPostBack()) {
            $this->procesarPost();
        }

        $this->processView();

        Renderer::render('admin/inventario', $this->viewData);
    }

    private function init()
    {
        $this->viewData = array();
        $this->viewData["mode"] = "";
        $this->viewData["mode_desc"] = "";
        //$this->viewData["crsf_token"] = "";
        $this->viewData["inventory_id"] = "";
        //$this->viewData["error_inventory_id"] = array();
        $this->viewData["product_id"] = "";
        //$this->viewData["error_product_id"] = array();
        $this->viewData["inventory_size"] = "";
        //$this->viewData["error_inventory_size"] = array();
        $this->viewData["inventory_gender"] = "";
        //$this->viewData["error_inventory_gender"] = array();
        $this->viewData["product_stock"] = "";
        //$this->viewData["error_product_stock"] = array();


        $this->viewData["readonly"] = false;
        $this->viewData["readonlyUPD"] = false;
        $this->viewData["showBtn"] = true;

        $this->arrModeDesc = array(
            "INS" => "Nuevo Registro de Inventario",
            "UPD" => "Editando Producto %s %s",
            "DSP" => "Detalle de %s %s",
            "DEL" => "Eliminando %s %s"
        );
    }


    private function procesarGet()
    {
        if (isset($_GET["mode"])) {
            $this->viewData["mode"] = $_GET["mode"];
            if (!isset($this->arrModeDesc[$this->viewData["mode"]])) {
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=admin_inventarios",
                    "Error: Modo de operación no válido."
                );
            }
        }

        if ($this->viewData["mode"] !== "INS" && isset($_GET["id"])) {
            $this->viewData["inventory_id"] = intval($_GET["id"]);
            $tmpinventory =  \Dao\Admin\Inventario::getById($this->viewData["inventory_id"]);
            \Utilities\ArrUtils::mergeFullArrayTo($tmpinventory, $this->viewData);
        }
    }



    private function procesarPost()
    {

        \Utilities\ArrUtils::mergeFullArrayTo($_POST, $this->viewData);


        $result = null;
        switch ($this->viewData["mode"]) {
            case "INS":
                $result = \Dao\Admin\Inventario::insertInventory($this->viewData["product_id"], $this->viewData["inventory_size"], $this->viewData["inventory_gender"], $this->viewData["product_stock"]);
                if ($result) {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin_inventarios",
                        "Exito: El registro se inserto correctamente."
                    );
                } else {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin_inventarios",
                        "Error: El registro no se pudo insertar."
                    );
                }
                break;
            case "UPD":
                $result = \Dao\Admin\Inventario::updateInventory($this->viewData["inventory_id"], $this->viewData["product_id"], $this->viewData["inventory_size"], $this->viewData["product_stock"]);
                if ($result) {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin_inventarios",
                        "Exito: El registro se actualizo correctamente."
                    );
                } else {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin_inventarios",
                        "Error: El registro no se pudo actualizar."
                    );
                }
                break;
            case "DEL":
                $result = \Dao\Admin\Inventario::deleteInventory($this->viewData["inventory_id"]);
                if ($result) {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin_inventarios",
                        "Exito: El registro se elimino correctamente."
                    );
                } else {
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=admin_inventarios",
                        "Error: El registro no se pudo eliminar."
                    );
                }
                break;
        }
    }


    private function processView()
    {
        if ($this->viewData["mode"] === "INS") {
            $this->viewData["mode_desc"] = $this->arrModeDesc["INS"];
            $this->viewData["btnEnviarText"] = "Guardar Nuevo";
        } else {
            $this->viewData["mode_desc"] = \sprintf(
                $this->arrModeDesc[$this->viewData["mode"]],
                $this->viewData["inventory_id"],
                ""
            );

            if ($this->viewData["mode"] === "DSP") {
                $this->viewData["readonly"] = true;
                $this->viewData["readonlyUPD"] = true;
                $this->viewData["showBtn"] = false;
            } elseif ($this->viewData["mode"] === "UPD") {
                $this->viewData["btnEnviarText"] = "Actualizar";
                $this->viewData["readonlyUPD"] = true;
            } else if ($this->viewData["mode"] === "DEL") {
                $this->viewData["readonly"] = true;
                $this->viewData["readonlyUPD"] = true;
                $this->viewData["btnEnviarText"] = "Eliminar";
            }
        }
    }
}
?>