<?php 
class formController extends \classes\Controller\CController{
    public $model_name = 'config/form';
    public function __construct($vars) {
        $this->addToFreeCod(array('save','setmain'));
        parent::__construct($vars);
    }
    
    public function index(){
        $this->display(LINK ."/index");
    }
    
    public function save(){
        $this->capture();
        if(!empty($_POST)){
            $bool        = $this->model->saveData($_POST, $this->form, $this->cod_usuario, $this->id);
            $this->setVars($this->model->getMessages());
            $this->registerVar('status', ($bool === false)?'0':'1');
        }
        
        $group    = $this->getGroup();
        $redirect = (isset($_GET['_redirect']) && trim($_GET['_redirect']) !== "")?$_GET['_redirect']:"config/group/form/$group/$this->form";
        $link     = $this->LoadResource('html', 'html')->getLink($redirect);        
        $this->registerVar('redirect', $link);
        $this->display('');
    }
    
    public function setmain(){
        $this->capture();
        $this->LoadModel('config/response', 'resp')->setMain($this->form, $this->cod_usuario, $this->id);
        $group = $this->getGroup();
        $action = isset($_GET['action'])?$_GET['action']:"form";
        Redirect("config/group/$action/$group/$this->form");
    }
    
    private function getGroup(){
        $item = $this->model->getItem($this->form);
        return $item['__group'];
    }
    
    private function capture(){
        $this->form        = (isset($this->vars[0]))?$this->vars[0]: '';
        $this->cod_usuario = (isset($this->vars[1]))?$this->vars[1]: usuario_loginModel::CodUsuario();
        $this->id          = (isset($this->vars[2]))?$this->vars[2]: "";
    }
}