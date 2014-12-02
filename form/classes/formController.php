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
        $bool        = $this->model->saveData($_POST, $this->form, $this->cod_usuario, $this->id);
        $this->setVars($this->model->getMessages());
        $this->registerVar('status', ($bool === false)?'0':'1');
        
        $group = $this->getGroup();
        $this->registerVar('redirect', $this->LoadResource('html', 'html')->getLink("config/group/form/$group/$this->form"));
        
        $this->display('');
    }
    
    public function setmain(){
        $this->capture();
        $this->LoadModel('config/response', 'resp')->setMain($this->form, $this->cod_usuario, $this->id);
        $group = $this->getGroup();
        Redirect("config/group/form/$group/$this->form");
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