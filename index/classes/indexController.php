<?php
class indexController extends \classes\Controller\Controller{
    public $model_name = 'config/index';
    
    public function index(){
        if(null === filter_input(INPUT_GET, '_user')){Redirect("config/group/form/acesso/acesso_email");}
        Redirect("config/group/form/pessoal/pessoal_phone");
    }
    
    public function user(){
        $this->LoadResource('html', 'html')->getLink("config/group/form/acesso/acesso_email");
        Redirect("config/group/form/pessoal/pessoal_phone", 0, "", array(), true);
    }
    
    public function request(){
        $var  = isset($this->vars[0])?$this->vars[0]:"";
        if($var !== ""){$var = explode("|", $var);}
        $user = usuario_loginModel::CodUsuario();
        $out  = $this->LoadModel('config/response', 'resp')->requestData($var, $user);
        echo json_encode($out);
    }
    
    public function detect(){
        $this->LoadClassFromPlugin('config/form/formDetector', 'fd')->importData();
    }
    
    public function migrate(){
        $this->LoadClassFromPlugin('config/form/formMigration', 'fm')->migrateData();
    }
    
}
