<?php 
class config_formModel extends \classes\Model\Model{
    public $tabela = "config_form";
    public $pkey   = 'cod';
    
    public function saveData($post, $form, $cod_usuario, $id = ''){
        $item = $this->getItem($form);
        $data = json_decode($item['form_data'],true);
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
}
