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
        //print_rd($_POST);
        foreach($this->post['form_response'] as &$resp){
            if(substr($resp, 0, 5) === "FUNC_"){$resp = str_replace('FUNC_', '', $resp);}
        }
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
    
    /**
     * Request user data
     * @param mixed $formsid can be array or string
     * @param int $user cod of user
     * @return array empty array if data doesn't exists
     */
    public function requestData($formsid, $user = ""){
        if($user === ""){$user = usuario_loginModel::CodUsuario();}
        if(!is_array($formsid)){$formsid = array($formsid);}
        $query  = implode("','", $formsid);
        $result = $this->selecionar(array('form_response','main','form'), "login='$user' AND form IN('$query')");
        
        $out    = array();
        if(!empty($result)){
            foreach($result as $res){
                if(!isset($out[$res['form']])){$out[$res['form']] = array();}
                $res['form_response']          = json_decode($res['form_response'], true);
                $res['form_response']['_main'] = $res['main'];
                $out[$res['form']][] = $res['form_response'];
            }
        }
        $need = array();
        foreach($formsid as $form){
            if(!array_key_exists($form, $out)){
                $need[] = $form;
            }
        }
        $exist = $this->LoadModel('config/form', 'form')->getExistent($need);
        $link  = base64_encode($this->LoadResource('html','html')->getLink(CURRENT_URL, false, true));
        $item  = base64_encode(implode("-", $exist));
        if(!empty($exist)){Redirect("config/group/request/&_request=$item&_credirect=$link");}
        return $out;
    }
}

/**
 * 
 http://hat/config/group/request/pessoal/pessoal_phone/form
 * &_redirect=config/group/request/pessoal/pessoal_address
 * &_index=1
 * &_request=cGVzc29hbF9waG9uZS1wZXNzb2FsX2FkZHJlc3MtcGVzc29hbF9lbWFpbA==
 * &_request=cGVzc29hbF9waG9uZS1wZXNzb2FsX2FkZHJlc3MtcGVzc29hbF9lbWFpbA==
 * &_credirect=Y29uZmlnL2luZGV4L3JlcXVlc3Qv
 */
