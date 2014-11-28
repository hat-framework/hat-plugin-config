<?php 
class config_responseModel extends \classes\Model\Model{
    public $tabela = "config_response";
    public $pkey   = 'cod';
    public function getResponse($formId, $codUsuario){
        return $this->selecionar(array('form_response','cod'), "login='$codUsuario' AND form='$formId'");
    }
    
    public function inserir($dados) {
        if(isset($dados['form_response'])){$dados['form_response'] = json_encode($dados['form_response'], JSON_UNESCAPED_UNICODE);}
        return parent::inserir($dados);
    }
}
