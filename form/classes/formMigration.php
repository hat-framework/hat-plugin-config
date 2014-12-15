<?php

class formMigration extends \classes\Classes\Object{
    public function __construct() {
        $this->LoadModel('usuario/login', 'uobj');
        $this->LoadModel('usuario/endereco', 'end');
        $this->LoadModel('config/form' , 'form');
    }
    
    public function migrateData(){
        $this->migratePhone();
        $this->migrateAddress();
    }
    
    public function migratePhone(){
        $dados   = $this->uobj->getDados();
        $request = array('cod_usuario','fixo','celular','codcorretora');
        foreach($request as $i => $req){
            if(array_key_exists($req, $dados)){continue;}
            unset($request[$i]);
        }
        $users   = $this->uobj->selecionar($request);
        foreach($users as $user){
            if(isset($user['celular'])      && trim($user['celular'])      !== "")$this->form->saveData(array('type' => 'fixo' ,'numero'    => $user['celular'])        , 'pessoal_phone'    , $user['cod_usuario']);
            if(isset($user['fixo'])         && trim($user['fixo'])         !== "")$this->form->saveData(array('type' => 'outro','numero'    => $user['fixo'])           , 'pessoal_phone'    , $user['cod_usuario']);
            if(isset($user['codcorretora']) && trim($user['codcorretora']) !== "")$this->form->saveData(array('type' => 'geral','corretora' => $user['codcorretora'])   , 'pessoal_corretora', $user['cod_usuario']);
        }
    }
    
    public function migrateAddress(){
        $enderecos = $this->end->selecionar();
        $assign    = array('cep', 'rua' ,'numero','bairro','cidade','estado');
        foreach($enderecos as $end){
            $out = array();
            foreach($end as $name => $e){
                if(!in_array($name, $assign)){continue;}
                $out[$name] = $e;
            }
            $this->form->saveData($out, 'pessoal_address', $end['login']);
        }
    }
}