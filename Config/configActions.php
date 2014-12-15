<?php 
use classes\Classes\Actions;   
class configActions extends Actions{
    protected $permissions = array(
        "config/public" => array(
            "nome"      => "config/public",
            "label"     => "Configurações pessoais",
            "descricao" => "Permite configurar os próprios dados",
            'default'   => 's',
        ),
        "config/see" => array(
            "nome"      => "config/see",
            "label"     => "Visualizar Configurações",
            "descricao" => "Permite visualizar as configurações de qualquer usuário",
            'default'   => 'n',
        ),
        "config/admin" => array(
            "nome"      => "config/admin",
            "label"     => "Gerenciar Configurações",
            "descricao" => "Permite alterar as configurações de qualquer usuário (requer Visualizar Configurações)",
            'default'   => 'n',
        ),
    );
    protected $actions = array(
        'config/index/index' => array(
            'label' => 'Configurações do usuário', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        
        'config/index/user' => array(
            'label' => 'Configurações do usuário', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        'config/index/request' => array(
            'label' => 'Requisição de dados', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        
        
        'config/form/save' => array(
            'label' => 'Salvar configuração', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        
        'config/form/setmain' => array(
            'label' => 'Marcar como principal', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        
        
        
        'config/group/index' => array(
            'label' => 'Grupo Principal', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        
        'config/group/form' => array(
            'label' => 'Formulário', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        
        'config/group/setmain' => array(
            'label' => 'Marcar como Principal', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        
        'config/group/sform' => array(
            'label' => 'Formulário preenchimento', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
        
        'config/group/request' => array(
            'label' => 'Requisitar Preenchimento', 'publico' => 'n', "default_yes" => "s","default_no" => "n",
            "permission" => "config/public"
        ),
    );
}
