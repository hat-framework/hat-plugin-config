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
        if(isset($this->post['form_response']) && is_array($this->post['form_response'])){
            array_walk_recursive($this->post['form_response'], function(&$item, $key) {
                if(is_string($item)) {$item = htmlentities($item);}
            });
            $temp = json_encode($this->post['form_response']);
            $this->post['form_response'] = $temp;
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
        
        $result = $this->getData($formsid, $user);
        $out    = $this->prepareOut($result);        
        $need   = $this->getNeeded($formsid, $out);
        if(empty($need)){return $out;}
        
        $this->redirectNeeded($need);
        return $out;
    }
    
            private function getData($formsid, $user = ""){
                if($user === ""){$user = usuario_loginModel::CodUsuario();}
                if(!is_array($formsid)){$formsid = array($formsid);}
                $query  = implode("','", $formsid);
                return $this->selecionar(array('form_response','main','form'), "login='$user' AND form IN('$query')");
            }
    
            private function prepareOut($result){
                $out    = array();
                if(empty($result)){return array();}
                foreach($result as $res){
                    if(!isset($out[$res['form']])){$out[$res['form']] = array();}
                    $res['form_response']['_main'] = $res['main'];
                    $out[$res['form']][] = $res['form_response'];
                }
                return $out;
            }
    
            private function getNeeded($formsid, $out){
                $need = array();
                foreach($formsid as $form){
                    if(!array_key_exists($form, $out)){
                        $need[] = $form;
                    }
                }
                return $need;
            }
            
            private function redirectNeeded($need){
                $exist = $this->LoadModel('config/form', 'form')->getExistent($need);
                $link  = base64_encode($this->LoadResource('html','html')->getLink(CURRENT_URL, false, true));
                $item  = base64_encode(implode("-", $exist));
                if(!empty($exist)){Redirect("config/group/request/&_request=$item&_credirect=$link");}
            }
    
    public function selecionar($campos = array(), $where = "", $limit = "", $offset = "", $orderby = "") {
        $out = parent::selecionar($campos, $where, $limit, $offset, $orderby);
        if(empty($out)){return array();}
        foreach($out as $i => &$o){
            if(!isset($o['form_response'])){break;}
            if($o['form_response'] == ""){continue;}
            
            $temp = json_decode($o['form_response'],true);
            if($temp === null){
                $temp = $this->manualDecode($o['form_response']);
                if(empty($temp)){
                    //print_rh($temp);
                    //print_rh($o['form_response']);
                    unset($o[$i]);
                    continue;
                }
            }
            $o['form_response'] = $temp;
            array_walk_recursive($o['form_response'], function(&$item, $key) {
                if(is_string($item)) {$item = html_entity_decode($item);}
            });
        }
        return $out;
    }
    
            private function manualDecode($string){
                $e     = explode("{"  , $string);
                $ee    = explode("}"  , $e[1]);
                $arr   = explode('","', $ee[0]);
                $out   = array();
                $count = count($arr);
                foreach($arr as $i => $a){
                    $temp = explode('":"', $a);
                    if($i == 0 && $temp[0][0] == '"'){
                        $temp[0][0] = "";
                    }
                    
                    if($i == $count-1 && $temp[1][strlen($temp[1])-1] == '"'){
                        $temp[1][strlen($temp[1])-1] = '';
                    }
                    
                    $out[$temp[0]] = $temp[1];
                }
                return $out;
            }
}