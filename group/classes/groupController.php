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
    
    public function form(){
        if(!isset( $this->vars[0])){Redirect(LINK ."/index");}
        if(!isset( $this->vars[1])){Redirect(LINK ."/group/{$this->vars[0]}");}
        $this->registerVar('group', $this->vars[0]);
        $this->registerVar('form', $this->vars[1]);
        $this->display(LINK ."/form");
    }
}