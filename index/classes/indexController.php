<?php
class indexController extends \classes\Controller\Controller{
    public $model_name = 'config/index';
    
    public function index(){
        Redirect("config/group/form/acesso/acesso_email");
    }
    
    public function request(){
        $var  = isset($this->vars[0])?$this->vars[0]:"";
        if($var !== ""){$var = explode("|", $var);}
        $user = usuario_loginModel::CodUsuario();
        $out  = $this->LoadModel('config/response', 'resp')->requestData($var, $user);
        echo json_encode($out);
    }
}
