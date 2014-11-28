<?php 
class formController extends \classes\Controller\CController{
    public $model_name = 'config/form';
    public function __construct($vars) {
        $this->addToFreeCod(array('save'));
        parent::__construct($vars);
    }
    
    public function index(){
        $this->display(LINK ."/index");
    }
    
    public function save(){
        $form        = (isset($this->vars[0]))?$this->vars[0]: '';
        $cod_usuario = (isset($this->vars[1]))?$this->vars[1]: usuario_loginModel::CodUsuario();
        $bool        = $this->model->saveData($_POST, $form, $cod_usuario);
        $this->setVars($this->model->getMessages());
        $this->registerVar('status', ($bool === false)?'0':'1');
        $this->display('');
    }
}