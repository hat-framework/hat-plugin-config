<?php
class configInstall extends classes\Classes\InstallPlugin{
    protected $dados = array(
        "pluglabel" => "Configurações de Usuários",
        "isdefault" => "n",
        "system"    => "n",
        "detalhes"  => "",
    );
    public function install(){
        $this->LoadModel('config/group', 'gr')->importDataFromArray(array(
            array('cod'=>'acesso' ,'title'=>'Dados de Acesso','icon'=>'fa fa-lock' ,'ordem' =>'1'),
            array('cod'=>'pessoal','title'=>'Dados Pessoais' ,'icon'=>'fa fa-user' ,'ordem' =>'2'),
            array('cod'=>'notify' ,'title'=>'Notificações'   ,'icon'=>'fa fa-globe','ordem' =>'3')
        ));
        
        $this->LoadClassFromPlugin('config/form/formDetector', 'fd')->importData();
        return true;
    }
    public function unstall(){return true;}
}
