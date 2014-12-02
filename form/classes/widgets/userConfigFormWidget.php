<?php 
class userConfigFormWidget extends \classes\Component\widget{
    
    protected $pgmethod  = "paginate";
    //protected $method    = "listFilthers";
    protected $modelname = "config/form";
    //protected $arr       = array('cod_usuario', 'user_name', 'email', 'user_uacesso', 'status');
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "0";
    //protected $order     = "user_uacesso DESC";
    //protected $title     = "Últimos acessos";
    
    public function getItens() {
        $this->codUsuario = ($this->codUsuario !== "")?$this->codUsuario:usuario_loginModel::CodUsuario();
        $this->form       = $this->LoadModel('config/form','frm')->getItem($this->formId);
        if(!isset($this->form["__type"])){throw new InvalidArgumentException("Não foi setado o tipo para esta configuração!");}
        return array();
    }
    
    public function draw($form) {
        $method = 'draw'.ucfirst($this->form["__type"]);
        if(!method_exists($this, $method)){throw new InvalidArgumentException("O método $method não existe!");}
        $this->$method();
    }
    private $itemId = '';
    public function setItemId($itemId){
        $this->itemId = $itemId;
    }
    
    private $formId = '';
    public function setFormId($formId){
        $this->formId = $formId;
    }
    
    private $action = '';
    public function setAction($action){
        $this->action = $action;
    }
    
    private $groupId = '';
    public function setGroupId($groupId){
        $this->groupId = $groupId;
    }
    
    private $codUsuario = '';
    public function setCodUsuario($codUsuario){
        $this->codUsuario = $codUsuario;
    }
    
    private function drawComponent(){
        $component = $this->form['ref'];
        $method    = $this->form['method'];
        $this->LoadComponent($component, 'comp');
        if(!method_exists($this->comp, $method)){throw new InvalidArgumentException("O método $method não existe no componente $component!");}
        $this->comp->$method();
    }
    
    private function drawDirectdata(){
        $this->multipleHeader();
        $data         = json_decode($this->form['form_data'],true);
        $item         = $this->LoadModel('config/response', 'resp')->getResponse($this->formId, $this->codUsuario);
        
        if($this->form['multiple'] == 1){
            $this->action = ($this->action === "")?'grid':$this->action;
            if(empty($item)){$action = 'form';}
            if(!method_exists($this, $this->action)){$this->action = 'grid';}
        }else{$this->action = 'form';}
        $action = $this->action;
        $this->$action($data, $item);
    }
    
    private function multipleHeader(){
        if($this->form['multiple'] != 1){return;}
        $link1 = $this->LoadResource('html', 'html')->getLink("config/group/form/$this->groupId/$this->formId/grid", false, true);
        $link2 = $this->LoadResource('html', 'html')->getLink("config/group/form/$this->groupId/$this->formId/form", false, true);
        $active_form = ($this->action === 'form')?'active':'';
        $active_grid = ($this->action === 'grid')?'active':'';
        echo "<div style='padding:0; margin-bottom:10px;'>
            <ul class='btn-group ' style='padding:0; margin:0;'>
                <li class='btn btn-default btn-lg $active_grid' style='margin-right:10px;'>
                    <a href='$link1'><i class='fa fa-list'></i> Listar</a>
                </li>
                <li class='btn btn-default btn-lg $active_form' style='margin-right:10px;'>
                    <a href='$link2'><i class='fa fa-plus'></i> Adicionar</a>
                </li>
            </ul>
        </div>";
    }
    
    private function edit($dados, $response){
        if($this->itemId === ""){Redirect("config/group/form/$this->groupId/$this->formId");}
        foreach($response as $item){
            if(!isset($item['cod']) || $this->itemId != $item['cod']){continue;}
            $item = json_decode($item['form_response'],true);
            break;
        }
        $this->form($dados, array($item));
    }
    
    private function form($data, $item){
        if(!is_array($data) || empty($data)){return;}
        $item = ($this->form['multiple'] == '1' && $this->action === 'form')?array():array_shift($item);
        $key  = (isset($item['cod']))?$item['cod']:$this->itemId;
        if(isset($item['form_response']) && !is_array($item['form_response'])){
            $item = json_decode($item['form_response'],true);
        }
        
        echo "<div style='padding:0px'>";
            echo "<div class='panel panel-default'>";
                echo "<div class='panel-heading'><h3 class='title panel-title'><i class='{$this->form['icon']}'></i>{$this->form['title']}</h3></div>";
                echo "<div class='panel-body'>";
                    $this->LoadResource('formulario', 'frm')->NewForm($data,$item,array(),true, "config/form/save/$this->formId/$this->codUsuario/$key");
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }
    
    private function grid($dados, $response){
        if($this->form['multiple'] != 1){return;}
        $header = $this->mountHeader($dados);
        $table  = $this->mountGrid($dados, $response);
        if(empty($table)){Redirect("config/group/form/$this->groupId/$this->formId/form");}
        echo "<style>.opcoes{width:60px;}</style>";
        echo "<div style='padding:0px'>";
            echo "<div class='panel panel-default'>";
                echo "<div class='panel-heading'><h3 class='title panel-title'><i class='{$this->form['icon']}'></i>{$this->form['title']}</h3></div>";
                echo "<div class='panel-body'>";
                    $this->LoadResource('html/table', 'tb')->draw($table,$header);
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }
    
    private function mountHeader($dados){
        $header = array();
        foreach($dados as $arr){
            if(!isset($arr['name'])) {continue;}
            $header[] = $arr['name'];
        }
        $header[] = "Principal"; 
        $header[] = "Opções"; 
        return $header;
    }
    
    private function mountGrid($dados, $response){
        $i = 0;
        $table  = array();
        $comp   = new classes\Component\Component();
        $this->LoadResource('html', 'html');
        foreach($response as $item){
            $tb   = array();
            $key  = $item['cod'];
            $data = json_decode($item['form_response'],true);
            foreach($data as $name => $valor){
                if(!array_key_exists($name, $dados)){continue;}
                $val = $comp->formatType($name, $dados, $valor, $data);
                $tb[$name] = $val;
            }
            
            $link1  = $this->html->getLink("config/form/setmain/$this->formId/$this->codUsuario/$key"     , false, true);
            $link2  = $this->html->getLink("config/group/form/$this->groupId/$this->formId/edit/$key", false, true);
            $link3  = $this->html->getLink("config/group/form/$this->groupId/$this->formId/drop/$key", false, true);
            $tb['principal'] = (!isset($item['main']) || $item['main'] == '0')?"<a href='$link1'>Tornar Principal</a>":"<i class='fa fa-check'></i>";
            $tb['action'] = "<a href='$link2'><i class='fa fa-pencil'></i></a><a href='$link3'><i class='fa fa-close'></i></a>";
            $table[$i] = $tb;
            $i++;
        }
        return $table;
    }
    
}
