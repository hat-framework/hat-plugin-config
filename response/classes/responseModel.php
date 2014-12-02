<?php 
class config_responseModel extends \classes\Model\Model{
    public $tabela  = "config_response";
    public $pkey    = 'cod';
    private $idname = "respid";
    public function getResponse($formId, $codUsuario, $where = '', $limit = 0){
        $wh = "login='$codUsuario' AND form='$formId'";
        $w  = ($where === "")?$wh:"$where AND ($wh)";
        return $this->selecionar(array('form_response','cod','main'), $w, $limit);
    }
    
    public function validate() {
        if(isset($this->post['form_response'])){
            $this->post['form_response'] = json_encode($this->post['form_response'], JSON_UNESCAPED_UNICODE);
        }
        return parent::validate();
    }
    
    public function apagar($valor, $chave = "") {
        $item = $this->getItem($valor, $chave, false, array('login','main','form'));
        $bool = parent::apagar($valor, $chave);
        if($item['main'] === "1"){$this->setOneMain($item['__form'], $item['__login']);}
        return $bool;
    }
    
    public function setOneMain($form, $user){
        $response = $this->getResponse($form, $user);
        if(empty($response)){return;}
        foreach($response as $resp){
            if($resp['main'] === '1'){return;}
        }
        $res      = array_shift($response);
        $this->setMain($form, $user, $res['cod']);
    }
    
    public function setMain($formId, $codUsuario, $id){
        return $this->db->ExecuteQuery("
            UPDATE $this->tabela SET main = 0 WHERE login='$codUsuario' AND form='$formId';
            UPDATE $this->tabela SET main = 1 WHERE login='$codUsuario' AND form='$formId' AND cod='$id'
        ");
    }
}
