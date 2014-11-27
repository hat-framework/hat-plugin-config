<?php 
class config_groupData extends \classes\Model\DataModel{
    public $dados  = array(
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'varchar',
	    'size'     => '16',
	    'pkey'    => true,
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
         'title' => array(
	    'name'     => 'Title',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
         'icon' => array(
	    'name'     => 'Icon',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
         'description' => array(
	    'name'     => 'Description',
	    'type'     => 'varchar',
	    'size'     => '200',
	    'grid'    => true,
	    'display' => true,
        ),
        
         'ordem' => array(
	    'name'     => 'Ordem',
	    'type'     => 'int',
	    'size'     => '3',
            'default'  => '999',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
	    'button'     => array('button' => 'Gravar Group'),);
}