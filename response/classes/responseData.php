<?php 
class config_responseData extends \classes\Model\DataModel{
    public $dados  = array(
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'int',
	    'pkey'    => true,
	    'ai'      => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
         'login' => array(
	    'name'     => 'Usuário',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'cod_usuario'),
	    ),
        ),
         'form' => array(
	    'name'     => 'Formulário',
	    'type'     => 'varchar',
	    'size'     => '16',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'especial' => 'session',
	    'session'  => 'config/form',
	    'fkey' => array(
	        'model' => 'config/form',
	        'cardinalidade' => '1n',
	        'keys' => array('cod', 'cod'),
	    ),
        ),
         'form_response' => array(
	    'name'     => 'Response',
	    'type'     => 'text',
	    'grid'    => true,
	    'display' => true,
        ),
	    'button'     => array('button' => 'Gravar Response'),);
}