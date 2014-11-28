<?php 
class config_formModel extends \classes\Model\Model{
    public $tabela = "config_form";
    public $pkey   = 'cod';
    
    public function saveData($post, $form, $cod_usuario){
        $item = $this->getItem($form);
        $data = json_decode($item['form_data'],true);
        if(false === $this->validateData($data, $post)){return false;}
        $this->associaData($data, $post);
        $bool = $this->LoadModel('config/response', 'resp')->inserir(array(
            'login'         => $cod_usuario,
            'form'          => $form,
            'form_response' => $post,
        ));
        $this->setMessages($this->resp->getMessages());
        return $bool;
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
