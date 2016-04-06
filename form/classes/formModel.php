<?php 
class config_formModel extends \classes\Model\Model{
    public $tabela = "config_form";
    public $pkey   = 'cod';
    
    public function selecionar($campos = array(), $where = "", $limit = "", $offset = "", $orderby = ""){
        $out = parent::selecionar($campos, $where, $limit, $offset, $orderby);
        if(empty($out)){return $out;}
        foreach($out as &$o){
            if(!isset($o['form_data'])){break;}
            if($o['form_data'] == ""){continue;}
            
            $o['form_data'] = json_decode($o['form_data'],true);
            array_walk_recursive($o['form_data'], function(&$item, $key) {
                if(is_string($item)) {$item = html_entity_decode($item);}
            });
        }
        return $out;
    }
    
    public function saveData($post, $form, $cod_usuario, $id = ''){
        $item = $this->getItem($form);
        $data = $item['form_data'];
        if(false === $this->validateData($data, $post)){return false;}
        $this->associaData($data, $post);
        $dados = array('login'=> $cod_usuario,'form'=> $form,'form_response' => $post);
        $this->LoadModel('config/response', 'resp');
        if($id === ""){$bool = $this->resp->inserir($dados);}
        else{$bool = $this->resp->editar($id, $dados);}
        $this->resp->setOneMain($form, $cod_usuario);
        $this->setMessages($this->resp->getMessages());
        return $bool;
    }
    
    public function getExistent($forms){
        if(!is_array($forms)){$forms = array($forms);}
        $in  = implode("','", $forms);
        $res = $this->selecionar(array('cod'), "cod IN('$in')");
        if(empty($res)){return array();}
        $out = array();
        foreach($res as $r){
            $out[] = $r['cod'];
        }
        return $out;
    }
    
    private function validateData($data, &$post){
        if(!$this->LoadResource("formulario/validator", "pval")->validate($data, $post)){
            $this->setSimpleMessage('validation', $this->pval->getMessages());
            $e    = $this->getMessages();
            $erro = (isset($e['validation']['erro'])?$e['validation']['erro']: "Erro ao validar os dados a serem inseridos");
            $this->setErrorMessage($erro);
            return false;
    	}
        $post = $this->pval->getValidPost();
        return true;
    }
    
    private function associaData($data, &$post){
        foreach($post as $tname => $value){
            if(array_key_exists($tname, $data)){continue;}
            unset($post[$tname]);
        }
    }
    
    public function prepareFormData(&$form){
        $out = array();
        if(isset($form['form_data']) && is_array($form['form_data'])){
            array_walk_recursive($form['form_data'], function(&$item, $key) {
                if(is_string($item)) {$item = @htmlentities($item);}
            });
            $temp = @json_encode($form['form_data']);
            $form['form_data'] = $temp;
        }
        if(isset($form['multiple'])){
            if($form['multiple']     == '0'){$form['multiple'] = 'n';}
            elseif($form['multiple'] == '1'){$form['multiple'] = 's';}
        }
        
        foreach($this->dados as $name => $val){
            $default    = isset($val['default'])?$val['default']:"";
            $out[$name] = (isset($form[$name]))?$form[$name]:$default;
        }
        $form = $out;
        //debugWebmaster($form);echo "<hr/>";
    }
    
    public function getFormTitles($formids){
        $in  = implode("','", $formids);
        $arr = $this->selecionar(array('title', 'cod'), "cod IN('$in')");
        $out = array();
        foreach($arr as $temp){
            $out[$temp['cod']] = $temp['title'];
        }
        
        return $out;
    }
}
