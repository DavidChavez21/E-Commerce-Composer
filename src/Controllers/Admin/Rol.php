<?php 

namespace Controllers\Admin;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Admin\Roles as DaoRoles;
use Utilities\Validators;

class Rol extends PrivateController
{

    private $viewData = array();
    private $arrEstados = array();
    private $arrModeDesc = array();

    public function run(): void
    {
        
        $this->init();

        if(!$this->isPostBack())
        {
            $this->procesarGet();
        }

        if($this->isPostBack())
        {
            $this->procesarPost();
        }

        $this->processView();

        Renderer::render('admin/rol', $this->viewData);

    }

    private function init()
    {
        $this->viewData = array();
        if(\Utilities\Security::isLogged()){
            if($_SESSION["login"]["usertipo"] !== "PBL"){
                $this->viewData["isLogged"]=$_SESSION["login"]["usertipo"];
                $this->viewData["usernameappear"]=$_SESSION["login"]["userName"];
                $this->viewData["logeado"]=false;
            }
        }
        else 
        {
            $viewData["logeado"]=true;
        }

        $this->viewData['mode'] = '';
        $this->viewData['mode_desc'] = '';
        $this->viewData['crsf_token'] = '';
        $this->viewData['rolescod'] = '';
        $this->viewData['error_rolescod'] = array();
        $this->viewData['rolesdsc'] = '';
        $this->viewData['error_rolesdsc'] = array();
        $this->viewData['rolesest'] = '';

        $this->viewData['readonly'] = false;
        $this->viewData['showBtn'] = true;
     
        $this->arrEstados = array(
            array('value' => 'ACT', 'text' => 'Activo'),
            array('value' => 'INA', 'text' => 'Inactivo')
        );

        $this->arrModeDesc = array(
            'INS' => "Nuevo Rol",
            'UPD' => "Editando %s %s",
            'DSP' => "Visualizando %s %s",
            'DEL' => "Eliminando %s %s"
        );

        $this->viewData['rolesestArr'] = $this->arrEstados;

    }

    private function procesarGet()
    {
        if(isset($_GET['mode'])){
            $this->viewData['mode'] = $_GET['mode'];
            if(!isset($this->arrModeDesc[$this->viewData['mode']])){
                \Utilities\Site::redirectToWithMsg(
                    'index.php?page=Admin_Roles',
                    'Error: Modo de operación no válido.'
                );
            }
        }
        
        if($this->viewData['mode'] !== 'INS' && isset($_GET['Id']))
        {
            $this->viewData['rolescod'] = $_GET['Id'];
            $tmpRol = DaoRoles::getById($this->viewData['rolescod']);
            \Utilities\ArrUtils::mergeFullArrayTo($tmpRol, $this->viewData);
        }
    }

    private function procesarPost()
    {
        $hasErrors = false;
        //\Utilities\ArrUtils::mergeFullArrayTo($_POST, $this->viewData);
        $this->viewData['mode'] = $_POST['mode'];
        $this->viewData['rolescod'] = $_POST['rolescod'];
        $this->viewData['rolesdsc'] = $_POST['rolesdsc'];
        $this->viewData['rolesest'] = $_POST['rolesest'];

        if(Validators::IsEmpty($this->viewData['rolescod']))
        {
            $this->viewData['error_rolescod'][] = 'El codigo del rol es requerido.';
            $hasErrors = true;
        }

        if(Validators::IsEmpty($this->viewData['rolesdsc']))
        {
            $this->viewData['error_rolesdsc'][] = 'El nombre del rol es requerido.';
            $hasErrors = true;
        }

        if(!$hasErrors)
        {
            $result = null;
            switch($this->viewData['mode'])
            {
                case 'INS':
                    $result = DaoRoles::insert(
                        $this->viewData['rolescod'],
                        $this->viewData['rolesdsc'],
                        $this->viewData['rolesest']
                    );
                    if($result)
                    {
                        \Utilities\Site::redirectToWithMsg(
                            'index.php?page=Admin_Roles',
                            'Rol creado correctamente.'
                        );
                    }
                    else
                    {
                        \Utilities\Site::redirectToWithMsg(
                            'index.php?page=Admin_Roles',
                            'Error: No se pudo crear el rol.'
                        );
                    }
                    break;
                case 'UPD':
                    $result = DaoRoles::update(
                        $this->viewData['rolescod'],
                        $this->viewData['rolesdsc'],
                        $this->viewData['rolesest']
                    );
                    if($result)
                    {
                        \Utilities\Site::redirectToWithMsg(
                            'index.php?page=Admin_Roles',
                            'Rol actualizado correctamente.'
                        );
                    }
                    else
                    {
                        \Utilities\Site::redirectToWithMsg(
                            'index.php?page=Admin_Roles',
                            'Error: No se pudo actualizar el rol.'
                        );
                    }
                    break;
                case 'DEL':
                    $result = DaoRoles::delete($this->viewData['rolescod']);
                    if($result)
                    {
                        \Utilities\Site::redirectToWithMsg(
                            'index.php?page=Admin_Roles',
                            'Rol eliminado correctamente.'
                        );
                    }
                    else
                    {
                        \Utilities\Site::redirectToWithMsg(
                            'index.php?page=Admin_Roles',
                            'Error: No se pudo eliminar el rol.'
                        );
                    }
                    break;
            }
        }else{
            \Utilities\Site::redirectToWithMsg(
                'index.php?page=Admin_Roles',
                'Error'
            );
        }

    }

    private function processView()
    {
        if($this->viewData['mode'] === 'INS')
        {
            $this->viewData['mode_desc'] = $this->arrModeDesc['INS'];
            $this->viewData['readonlycode'] = false;
            $this->viewData['btnEnviarText'] = 'Guardar Nuevo';
        }
        else 
        {
            $this->viewData['mode_desc'] = \sprintf(
               //$this->arrModeDesc[$this->viewData['mode']],
                $this->viewData['rolescod'],
                $this->viewData['rolesdsc']
            );
            $this->viewData['rolesestArr'] = \Utilities\ArrUtils::objectArrToOptionsArray(
                $this->arrEstados,
                'value',
                'text',
                'value',
                $this->viewData['rolesest']
            );

            if($this->viewData['mode'] === 'DSP')
            {
                $this->viewData['readonlycode'] = true;
                $this->viewData['readonly'] = true;
                $this->viewData['showBtn'] = false;
            }
            elseif($this->viewData['mode'] === 'UPD')
            {
                $this->viewData['readonlycode'] = true;
                $this->viewData['btnEnviarText'] = 'Actualizar';
            }
            elseif($this->viewData['mode'] === 'DEL')
            {
                $this->viewData['readonlycode'] = true;
                $this->viewData['readonly'] = true;
                $this->viewData['btnEnviarText'] = 'Eliminar';
            }

        }

        $this->viewData['crsf_token'] = \md5(\getdate()[0].$this->name);
        $_SESSION[$this->name.'crsf_token'] = $this->viewData['crsf_token'];
    }

}

?>