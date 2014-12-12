<?php

class formDetector extends \classes\Classes\Object{
    private $groups  = array();
    private $plugins = array();
    public function __construct() {
        $this->LoadModel('config/form' , 'frm');
        $this->LoadModel('plugins/plug', 'plug');
        $this->LoadModel('config/group', 'gr');
        $this->plugins = classes\Classes\Registered::getAllPluginsLocation();
    }
    
    public function importData(){
        $this->all = $this->getGroups();
        foreach($this->plugins as $name => $location){
            $obj = $this->plug->getPluginInstaller($name, "UserConfig");
            if($obj === null){continue;}
            $forms  = $obj->getUserConfigForm(); 
            $groups = $obj->getUserConfigGroup();
            $this->detectNewGroups($forms, $groups);
            $this->importForms($forms, $groups);
        }
    }
    private function detectNewGroups(&$forms, $groups){
        foreach($forms as $cod => &$form){
            if(!array_key_exists($form['group'], $this->all)){
                if(!array_key_exists($form['group'], $groups)){continue;}
                $this->groups[] = $form['group'];
            }
            $this->frm->prepareFormData($form);
        }
    }
    
    private function importForms($forms, $groups){
        if(is_array($this->groups) && !empty($this->groups)){$this->gr->importDataFromArray($this->groups);}
        if(is_array($groups)       && !empty($groups))      {$this->gr->importDataFromArray($groups);}
        if(is_array($forms)        && !empty($forms))       {$this->frm->importDataFromArray($forms);}
    }
    
    private function getGroups(){
        $groups = $this->gr->selecionar(array('cod'));
        $out    = array();
        foreach($groups as $g){
            $out[$g['cod']] = $g['cod'];
        }
        return $out;
    }
}