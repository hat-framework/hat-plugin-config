<?php 
class groupController extends \classes\Controller\CController{
    public $model_name = 'config/group';
    
    public function __construct($vars) {
        $this->addToFreeCod(array('form','sform','request','group','creategroups'));
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
        }
        Redirect(LINK ."/form/{$this->vars[0]}/{$this->vars[1]}");
    }
    
    public function form($link = ""){
        if(!isset( $this->vars[0])){die('eee');Redirect(LINK ."/index");}
        if(!isset( $this->vars[1])){die('iooo');Redirect(LINK ."/group/{$this->vars[0]}");}
        $action = isset($this->vars[2])?$this->vars[2]:"listar";
        $id     = isset($this->vars[3])?$this->vars[3]:"";
        $this->dropitem($action, $id);
        $this->registerVar('group' , $this->vars[0]);
        $this->registerVar('form'  , $this->vars[1]);
        $this->registerVar('action', $action);
        $this->registerVar('itemId', $id);
        $this->display(($link === "")?LINK ."/form":$link);
    }
    
    public function setmain(){
        $this->capture();
    }
    
    public function sform(){
        $this->form(LINK ."/sform");
    }
    
    /*public function request(){
        $data         = base64_decode(filter_input(INPUT_GET, '_request'));
        $credirect    = base64_decode(filter_input(INPUT_GET, '_credirect'));
        $url          = filter_input(INPUT_GET, 'url');
        $redirect     = filter_input(INPUT_GET, '_redirect');
        $index        = filter_input(INPUT_GET, '_index');
        $request_data = explode("-",$data);
        if($index == false){$index = 0;}
        
        //se o dado que está sendo requisitado na página atual existe
        if(isset($request_data[$index])){
            //se existe o próximo dado a ser preenchido pelo usuário
            if(isset($request_data[$index+1])){
                $temp = explode("_", $request_data[$index+1]);
                $form = "config/group/request/{$temp[0]}/{$request_data[$index+1]}";
            }else{$form = $credirect;}
        
            $temp   = explode("_", $request_data[$index]);
            $action = "config/group/request/{$temp[0]}/{$request_data[$index]}/form";
            if($redirect == false){
                Redirect("$action&_redirect=$form&_index=1&_request=".filter_input(INPUT_GET, '_request'));
            }
            
            $result = $this->LoadModel('config/response', 'rep')->getResponse($request_data[$index], usuario_loginModel::CodUsuario(), "", 1);
            if(!empty($result) || in_array($url, array("$redirect/form", $redirect))){
                if(!empty($result)){
                    $action = $form;
                    $index++;
                }
                Redirect("$action&_redirect=$form/form&_index=$index&_request=".filter_input(INPUT_GET, '_request'));
            }
            
        }else{Redirect($credirect);}
        $this->registerVar("alert", 'Preencha o formulário abaixo para prosseguir');
        $this->form(LINK ."/sform");
    }*/
    
    public function request(){
        $data         = base64_decode(filter_input(INPUT_GET, '_request'));
        $credirect    = base64_decode(filter_input(INPUT_GET, '_credirect'));
        $redirect     = filter_input(INPUT_GET, '_redirect');
        if($redirect == false){Redirect("config/group/request&_redirect=config/group/request&_request=".filter_input(INPUT_GET, '_request'));}
        
        //registra o alerta
        $this->registerVar("alert", 'Preencha o formulário abaixo para prosseguir');
        
        //se o dado que está sendo requisitado na página atual existe
        $result = $this->LoadModel('config/response', 'resp');
        if($data === ""){Redirect($credirect);}
        $request_data = explode("-",$data);
        foreach($request_data as $req){
            $result     = $this->resp->getResponse($req, usuario_loginModel::CodUsuario(), "", 1);
            if(!empty($result)){continue;}
            $temp       = explode("_", $req);
            $this->vars = array($temp[0], $req, 'form');
            return $this->form(LINK ."/sform");
        }
        
        //redireciona para a página inicial, que chamou esta página
        Redirect($credirect);
    }
}