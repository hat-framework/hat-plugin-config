<?php 
class userConfigGroupWidget extends \classes\Component\widget{
    
    protected $pgmethod  = "paginate";
    //protected $method    = "listFilthers";
    protected $modelname = "config/group";
    //protected $arr       = array('cod_usuario', 'user_name', 'email', 'user_uacesso', 'status');
    protected $link      = '';
    protected $where     = "";
    protected $qtd       = "0";
    //protected $order     = "user_uacesso DESC";
    //protected $title     = "Últimos acessos";
    
    public function getItens() {
        $groups = $this->model->selecionar(array('cod','title','icon'), '', '','',"ordem ASC");
        $forms  = $this->LoadModel('config/form', 'frm')->selecionar(array('cod','title','icon','`group`'), '', '','',"ordem ASC");
        $temp   = array();
        foreach($forms as $frm){
            if(!isset($temp[$frm['group']])){$temp[$frm['group']] = array();}
            $temp[$frm['group']][] = $frm;
        }
        foreach($groups as &$gr){
            if(!isset($temp[$gr['cod']])){continue;}
            $gr['forms'] = $temp[$gr['cod']];
        }
        return $groups;
    }
    
    public function draw($itens) {
        $e = explode("/",CURRENT_URL);
        $this->current_group = isset($e[3])?$e[3]:"";
        $this->current_form  = isset($e[4])?$e[4]:"";
        echo '<div id="accordion" class="panel-group">';
            foreach($itens as $item){
                $this->drawItem($item);
            }
        echo '</div>';
    }
    
    private function drawItem($item){
        static $i = 0;
        extract($item);
        if(!isset($forms) || empty($forms)){return;}
        $i++;
        $class    = "collapse$i";
        $collapse = ($this->current_group === $cod)?"collapsed":"collapse";
        echo '<div class="panel panel-default">';
            echo '<div class="panel-heading">';
                echo "<h4 class='panel-title' style='cursor: pointer' data-toggle='collapse' data-parent='#accordion' href='#$class'>";
                    echo "<a><i class='$icon'></i> $title</a>";
                echo '</h4>';
            echo '</div>';
            echo "<div id='$class' class='panel-collapse $collapse'><div class='panel-body'>";
                
            foreach($forms as $form){
                $this->drawSubItem($cod, $form);
            }
                
            echo '</div></div>';
        echo '</div>';
    }
    
    private function drawSubItem($cod_group, $subitem){
        extract($subitem);
        $active   = ($this->current_form === $cod)?"active":"";
        $url = $this->Html->getLink("config/group/form/$cod_group/$cod");
        echo "<a href='$url' class='col-xs-12 btn $active' style=''><h5><i class='$icon'></i> $title</h5></a>";
    }
    
}
