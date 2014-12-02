<?php 
class groupController extends \classes\Controller\CController{
    public $model_name = 'config/group';
    
    public function __construct($vars) {
        $this->addToFreeCod(array('form','group','creategroups'));
        parent::__construct($vars);
    }
    
    public function index(){
        Redirect(LINK."/form/pessoal/email");
    }
    
    public function group(){
        if(!isset( $this->vars[0])){Redirect(LINK ."/index");}
        $this->registerVar('group', $this->vars[0]);
        $this->display(LINK ."/group");
    }
    
    private function dropitem($action, $id){
        if($action !== "drop" || $id === ""){return;}
        if(false === $this->LoadModel('config/response', 'resp')->apagar($id)){
            $this->setVars($this->resp->getMessages());
            $this->display('');
            die();
        }
        Redirect(LINK ."/form/{$this->vars[0]}/{$this->vars[1]}");
    }
    
    public function form(){
        if(!isset( $this->vars[0])){Redirect(LINK ."/index");}
        if(!isset( $this->vars[1])){Redirect(LINK ."/group/{$this->vars[0]}");}
        $action = isset($this->vars[2])?$this->vars[2]:"listar";
        $id     = isset($this->vars[3])?$this->vars[3]:"";
        $this->dropitem($action, $id);
        
        $this->registerVar('group' , $this->vars[0]);
        $this->registerVar('form'  , $this->vars[1]);
        $this->registerVar('action', $action);
        $this->registerVar('itemId', $id);
        $this->display(LINK ."/form");
    }
    
    public function setmain(){
        $this->capture();
    }
}