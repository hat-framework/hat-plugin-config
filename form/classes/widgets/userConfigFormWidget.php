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
    
    private $formId = '';
    public function setFormId($formId){
        $this->formId = $formId;
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
        $data = json_decode($this->form['form_data'],true);
        $item = $this->LoadModel('config/response', 'resp')->getResponse($this->formId, $this->codUsuario);
        $this->form($data, $item);
        $this->grid($data, $item);
    }
    
    private function multipleHeader(){
        if($this->form['multiple'] != 1){return;}
        $link = $this->LoadResource('html', 'html')->getLink(CURRENT_URL);
        echo "<div style='padding:0; margin-bottom:10px;'>
            <ul class='btn-group ' style='padding:0; margin:0;'>
                <li class='btn btn-default btn-lg' style='margin-right:10px;'>
                    <a href='$link/list'><i class='fa fa-list'></i> Listar</a>
                </li>
                <li class='btn btn-default btn-lg' style='margin-right:10px;'>
                    <a href='$link/add'><i class='fa fa-plus'></i> Adicionar</a>
                </li>
            </ul>
        </div>";
    }
    
    private function form($data, $item){
        if(!is_array($data) || empty($data)){return;}
        $item = ($this->form['multiple'] == 1)?array():array_shift($item);
        echo "<div class='col-xs-12 col-sm-12 col-md-4 col-lg-4 pull-left' style='padding:0px'>";
            echo "<div class='panel panel-default'>";
                echo "<div class='panel-heading'><h3 class='title panel-title'><i class='{$this->form['icon']}'></i>{$this->form['title']}</h3></div>";
                echo "<div class='panel-body'>";
                    $this->LoadResource('formulario', 'frm')->NewForm($data,$item,array(),false, "config/form/save/$this->formId/$this->codUsuario");
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }
    
    private function grid($dados, $response){
        if($this->form['multiple'] != 1){return;}
        $header = array();
        foreach($dados as $name => $arr){
            if(!isset($arr['name'])) {continue;}
            $header[] = $arr['name'];
        }
        $header[] = "Opções"; 
        $i = 0;
        $table = array();
        $comp  = new classes\Component\Component();
        foreach($response as $item){
            $tb = array();
            $item = json_decode($item['form_response'],true);
            foreach($item as $name => $valor){
                if(!array_key_exists($name, $dados)){continue;}
                $val = $comp->formatType($name, $dados, $valor, $item);
                $tb[$name] = $val;
            }
            $tb['action'] = "<a href='/#edit'><i class='fa fa-pencil'></i></a><a href='/#drop'><i class='fa fa-close'></i></a>";
            $table[$i] = $tb;
            $i++;
        }
        
        echo "<style>.opcoes{width:60px;}</style>";
        echo "<div class='col-xs-12 col-sm-12 col-md-8 col-lg-8 pull-right' style='padding:0px'>";
            echo "<div class='panel panel-default'>";
                echo "<div class='panel-heading'><h3 class='title panel-title'><i class='{$this->form['icon']}'></i>{$this->form['title']}</h3></div>";
                echo "<div class='panel-body'>";
                    $this->LoadResource('html/table', 'tb')->draw($table,$header);
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }
    
}
