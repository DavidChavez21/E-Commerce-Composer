<?php

namespace Controllers\Sec;

use Controllers\PublicController;
use Dao\Security\UsuarioTipo;
use \Utilities\Validators;
use Exception;

class Register extends PublicController
{
    private $txtNombres = "";
    private $txtApellidos = "";
    private $txtEmail = "";
    private $txtUsuario = "";
    private $txtPswd = "";
    private $txtPswdV = "";
    private $txtPais = "";
    private $txtCiudad = "";
    private $txtDireccion = "";
    private $txtPostal = "";
    private $txtTelefono = "";
    private $errorNombre = array();
    private $errorApellido = array();
    private $errorEmail = array();
    private $errorUsuario = array();
    private $errorPasswordV = array();
    private $errorPswd = array();
    private $errorTelefono = array();
    private $hasErrors = false;
    public function run() :void
    {
       
        if ($this->isPostBack()) {
            $this->txtNombres = $_POST["txtNombres"];
            $this->txtApellidos = $_POST["txtApellidos"];
            $this->txtEmail = $_POST["txtEmail"];
            $this->txtUsuario = $_POST["txtUsuario"];
            $this->txtPswd = $_POST["txtPassword"];
            $this->txtPswdV = $_POST["txtPasswordV"];
            $this->txtPais = $_POST["txtPais"];
            $this->txtCiudad = $_POST["txtCiudad"];
            $this->txtDireccion = $_POST["txtDireccion"];
            $this->txtPostal = $_POST["txtPostal"];
            $this->txtTelefono = $_POST["txtTelefono"];

            //validaciones
            if (!(Validators::IsValidEmail($this->txtEmail))) {
                $this->errorEmail[] = "El correo no tiene el formato adecuado";
                $this->hasErrors = true;
            }
            if (!Validators::IsValidPassword($this->txtPswd)) {
                $this->errorPswd[] = "La contraseña debe tener al menos 8 caracteres una mayúscula, un número y un caracter especial.";
                $this->hasErrors = true;
            }
            if ($this->txtPswd != $this->txtPswdV){
                $this->errorPasswordV[] = "Las contraseñas no son iguales";
                $this->hasErrors = true;
            }
            if (Validators::IsEmpty($this->txtNombres)) {
                $this->errorNombre[] = "Debe ingresar su nombre";
                $this->hasErrors = true;
            }
            if (Validators::IsEmpty($this->txtApellidos)) {
                $this->errorApellido[] = "Debe ingresar su apellido";
                $this->hasErrors = true;
            }
            if (Validators::IsEmpty($this->txtUsuario)) {
                $this->errorUsuario[] = "Debe ingresar un nombre de usuario";
                $this->hasErrors = true;
            }
            if (Validators::IsEmpty($this->txtTelefono)) {
                $this->errorTelefono[] = "Debe ingresar un numero de telefono";
                $this->hasErrors = true;
            }
            
            if (!$this->hasErrors) {
                try{
                    if (\Dao\Security\Security::newUsuario($this->txtEmail, $this->txtPswd, $this->txtUsuario, UsuarioTipo::PUBLICO)) {
                        if(\Dao\Mnt\Clientes::addCustomer($this->txtNombres, $this->txtApellidos, $this->txtDireccion,
                        $this->txtPostal, $this->txtPais, $this->txtCiudad, $this->txtTelefono, $this->txtEmail))
                        {
                            \Utilities\Site::redirectToWithMsg("index.php?page=sec_login", "¡Usuario registrado satisfactoriamente!");
                        }
                        else{
                            \Dao\Security\Security::deleteUsuario($this->txtEmail); //Elimina el usuario que se acaba de ingresar si falla el ingreso del cliente
                        }
                    }
                } catch (Exception $ex){
                    die($ex);
                }
            }
        }

        $viewData = get_object_vars($this);
        $viewData['QuantityProducts'] = $this->getQuantityProducts();
        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] == "PBL"){
                $viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $viewData["logeado"]=false;
            }
        }
        else 
        {
            $viewData["logeado"]=true;
        }

        \Views\Renderer::render("security/sigin", $viewData);
    }

    private function getQuantityProducts()
    {
        $quantity = 0;
        if(isset($_SESSION['shopping_cart'])){
            foreach($_SESSION['shopping_cart'] as $product){
                $quantity++;
            }
        }
        return $quantity;
    }
}
?>
