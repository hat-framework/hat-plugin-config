<?php 
use classes\Classes\Actions;   
class configActions extends Actions{
    protected $permissions = array(
        "config/public" => array(
            "nome"      => "config/public",
            "label"     => "Configurações pessoais",
            "descricao" => "Permite que o usuário configure dados e opções de funcionamento do site",
            'default'   => 's',
        ),
    );
    protected $actions = array(
        'config/index/index' => array(
            'label' => 'Configuração do site', 'publico' => 's', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public", 'needcod' => false,
        ),
    );
}
