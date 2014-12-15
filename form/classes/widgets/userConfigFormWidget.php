<?php 
class userConfigFormWidget extends \classes\Component\widget{
    
    protected $pgmethod  = "paginate";
    //protected $method    = "listFilthers";
    protected $modelname = "config/form";
    //protected $arr       = array('cod_usuario', 'user_name', 'email', 'user_uacesso', 'status');
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "0";
    protected $can_alter = false;
    //protected $order     = "user_uacesso DESC";
    //protected $title     = "Últimos acessos";
    
    public function getItens() {
        $this->codUsuario = (!in_array($this->codUsuario, array(null, "")))?$this->codUsuario:usuario_loginModel::CodUsuario();
        $this->form       = $this->LoadModel('config/form','frm')->getItem($this->formId);
        $this->cur_action = (defined('CURRENT_ACTION') && in_array(CURRENT_ACTION, array('sform','request')))?CURRENT_ACTION:'form';
        $this->can_alter  = $this->LoadModel('usuario/login', 'uobj')->UserCanAlter($this->codUsuario);
        if($this->can_alter === false){
            $err = $this->uobj->getErrorMessage();
            if(trim($err) === ''){$err = "Você não tem permissão de alterar os dados!";}
            throw new \Exception($err);
        }
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
        $data         = json_decode($this->form['form_data'],true);
        $item         = $this->LoadModel('config/response', 'resp')->getResponse($this->formId, $this->codUsuario);
        if($this->form['multiple'] == 1){
            $this->action = ($this->action === "")?'grid':$this->action;
            if(empty($item)){$action = 'form';}
            if(!method_exists($this, $this->action)){$this->action = 'grid';}
        }else{$this->action = 'form';}
        $this->multipleHeader();
        $action = $this->action;
        $this->$action($data, $item);
    }
    
    private function setPermission($perm){
        $user = filter_input(INPUT_GET, '_user');
        if($user === null){return true;}
        if($user === usuario_loginModel::CodUsuario()){return true;}
        if(false === $this->LoadModel('usuario/perfil', 'perf')->hasPermissionByName("config/$perm")){return false;}
        return $this->LoadModel('usuario/login', 'uobj')->UserCanAlter($user);
    }
    
    private function checkPermission($perm){
        static $cached = array();
        if(!isset($cached[$perm])){$cached[$perm] = $this->setPermission($perm);}
        return $cached[$perm];
    }
    
    private function multipleHeader(){
        if($this->form['multiple'] != 1 || $this->cur_action === 'request'){return;}
        if(!$this->checkPermission('admin')){return;}
        $link1 = $this->LoadResource('html', 'html')->getLink("config/group/$this->cur_action/$this->groupId/$this->formId/grid", false, true);
        $link2 = $this->LoadResource('html', 'html')->getLink("config/group/$this->cur_action/$this->groupId/$this->formId/form", false, true);
        $active_form = ($this->action === 'form')?'active':'';
        $active_grid = ($this->action === 'grid')?'active':'';
        echo "<div style='padding:0; margin-bottom:10px;'>
                    <a href='$link1' class='btn btn-default btn-lg $active_grid'><i class='fa fa-list'></i> Listar</a>
                    <a href='$link2' class='btn btn-info btn-lg $active_form'><i class='fa fa-plus'></i> Adicionar</a>
              </div>";
    }
    
    private function edit($dados, $response){
        if($this->itemId === ""){Redirect("config/group/$this->cur_action/$this->groupId/$this->formId");}
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
            echo "<div class='panel panel-info'>";
                echo "<div class='panel-heading'><h3 class='title panel-title'><i class='{$this->form['icon']}'></i>{$this->form['title']}</h3></div>";
                echo "<div class='panel-body'>";
                    $this->LoadResource('formulario', 'frm')->NewForm($data,$item,array(),true, "config/form/save/$this->formId/$this->codUsuario/$key");
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }
    
    private function grid($dados, $response){
        if($this->form['multiple'] != 1){return;}
        if(!$this->checkPermission('see')){throw new classes\Exceptions\AcessDeniedException();}
        $header = $this->mountHeader($dados);
        $table  = $this->mountGrid($dados, $response);
        
        if(empty($table)){
            if($this->checkPermission('admin')){Redirect("config/group/$this->cur_action/$this->groupId/$this->formId/form");}
        }
        echo "<style>.opcoes{width:60px;}</style>";
        echo "<div style='padding:0px'>";
            echo "<div class='panel panel-info'>";
                echo "<div class='panel-heading'><h3 class='title panel-title'><i class='{$this->form['icon']}'></i>{$this->form['title']}</h3></div>";
                echo "<div class='panel-body'>";
                    if(!empty($table)){
                        foreach($table as $arr){
                            echo '<div class="bs-callout bs-callout-info col-xs-12 col-sm-6 col-md-4 col-lg-3" style="margin-top: 0;">';
                                echo "<table class='table table-hover'>";
                                foreach($header as $name){
                                    $val = array_shift($arr);
                                    if(trim($val) === ""){continue;}
                                    echo "<tr>";
                                         if((trim($name) !== "")){
                                            echo "<td colspan='2'><b>{$name}: </b><br/>$val</td>";
                                         }
                                         else {echo "<td colspan='2'>$val</td>";}
                                    echo "</tr>";
                                }
                                echo "</table>";
                            echo "</div>";
                        }
                    }else{
                        echo "Não há nenhum dado nesta página";
                    }
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
        //$header[] = "Principal"; 
        if(true === $this->checkPermission('see')){$header[] = ""; }
        return $header;
    }
    
    private function mountGrid($dados, $response){
        $i = 0;
        $table      = array();
        $this->comp = new classes\Component\Component();
        $this->LoadResource('html', 'html');
        foreach($response as $item){
            $tb = $this->getRow($item, $dados);
            if(empty($tb)){continue;}
            $table[$i] = $tb;
            $i++;
        }
        return $table;
    }
    
    private function getRow($item, $dados){
        $tb   = array();
        $key  = $item['cod'];
        $data = json_decode($item['form_response'],true);
        foreach($data as $name => $valor){
            if(!array_key_exists($name, $dados)){continue;}
            $tb[$name] = $this->formatType($name, $dados, $valor, $data);
        }
        if(empty($tb)){return array();}
        $link1  = $this->html->getLink("config/form/setmain/$this->formId/$this->codUsuario/$key&action=$this->cur_action"     , false, true);
        $link2  = $this->html->getLink("config/group/$this->cur_action/$this->groupId/$this->formId/edit/$key", false, true);
        $link3  = $this->html->getLink("config/group/$this->cur_action/$this->groupId/$this->formId/drop/$key", false, true);
        $act          = (!isset($item['main']) || $item['main'] == '0')?
                "<a href='$link1' class='btn btn-info btn-block'>Tornar Principal</a>":
                "<a class='btn btn-default btn-block disabled'><i class='fa fa-check'></i> Principal</a>";
        
        if(true === $this->checkPermission('admin')){
            $tb['action'] = "$act "
                . "<a href='$link2' class='btn btn-warning btn-block'>"
                    . "<i class='fa fa-pencil'></i>Editar"
                . "</a>"
                . "<a href='$link3' class='btn btn-danger btn-block'>"
                    . "<i class='fa fa-close'></i>Apagar"
                . "</a>";
        }
        
        return $tb;
    }
    
    private function formatType($name, $dados, $valor, $data){
        $val = $this->comp->formatType($name, $dados, $valor, $data);
        if(!array_key_exists('fkey', $dados[$name])){return $val;}
        extract($dados[$name]['fkey']);
        if($this->LoadModel($model, 'md', false) === null){return $valor;}
        $select = $this->md->selecionar($keys, "{$keys[0]}='$valor'", 1);
        $link   = $this->html->getLink("$model/show/{$select[0][$keys[0]]}");
        return "<a href='$link' target='_BLANK$link'>{$select[0][$keys[1]]}</a>";
    }
    
}
