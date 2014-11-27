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
        return $this->LoadModel('config/form','frm')->getItem($this->formId);
    }
    
    public function draw($form) {
        $this->form = $form;
        if(!isset($form["__type"])){throw new InvalidArgumentException("Não foi setado o tipo para esta configuração!");}
        $method = 'draw'.ucfirst($form["__type"]);
        if(!method_exists($this, $method)){throw new InvalidArgumentException("O método $method não existe!");}
        $this->$method();
            //$this->LoadResource('formulario', 'form')->NewForm($itens['form'],$itens['data']);
    }
    
    private $formId = '';
    public function setFormId($formId){
        $this->formId = $formId;
    }
    
    private $groupId = '';
    public function setGroupId($groupId){
        $this->groupId = $groupId;
    }
    
    private function drawComponent(){
        $component = $this->form['ref'];
        $method    = $this->form['method'];
        $this->LoadComponent($component, 'comp');
        if(!method_exists($this->comp, $method)){throw new InvalidArgumentException("O método $method não existe no componente $component!");}
        $this->comp->$method();
    }
    
    private function drawDirectdata(){
        $data = json_decode($this->form['form_data'],true);
        if(!is_array($data) || empty($data)){return;}
        echo "<div id='change_widget' class='panel panel-default'>";
            echo "<div class='panel-heading'><h3 class='title panel-title'><i class='{$this->form['icon']}'></i>{$this->form['title']}</h3>";
        echo "</div>";
        echo "<div class='panel-body'>";
            $this->LoadResource('formulario', 'form')->NewForm($data,array());
            $this->grid($data);
        echo "</div>";
    }
    
    private function grid($data){
        if($this->form['multiple'] !== 1){return;}
        echo "<div>";
        
        echo "</div>";
    }
    
}
