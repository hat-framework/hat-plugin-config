<?php 
class config_formData extends \classes\Model\DataModel{
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
         'group' => array(
	    'name'     => 'Grupo',
	    'type'     => 'varchar',
	    'size'     => '16',
	    'grid'    => true,
	    'display' => true,
	    'notnull' => true,
	    'fkey' => array(
	        'model' => 'config/group',
	        'cardinalidade' => '1n',
	        'keys' => array('cod', 'cod'),
	    ),
        ),
         'title' => array(
	    'name'     => 'Title',
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
         'icon' => array(
	    'name'     => 'Icon',
	    'type'     => 'varchar',
	    'size'     => '32',
	    'grid'    => true,
	    'display' => true,
        ),
        
        'type' => array(
	    'name'     => 'Tipo',
	    'type'     => 'enum',
            'default'  => 'directdata',
            'options'  => array(
                'component' => 'Componente',
                'widget'    => 'Widget',
                'model'     => 'Modelo',
                'directdata'=> 'Dado Direto',
            ),
            'size'     => '32',
	    'grid'    => true,
	    'display' => true,
        ),
        
        'ref' => array(
	    'name'     => 'Referência',
	    'type'     => 'varchar',
            'size'     => '32',
	    'grid'    => true,
	    'display' => true,
        ),
        
        'method' => array(
	    'name'     => 'Método',
	    'type'     => 'varchar',
            'size'     => '32',
	    'grid'    => true,
	    'display' => true,
        ),
        
         'form_data' => array(
	    'name'     => 'Data',
	    'type'     => 'text',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'multiple' => array(
	    'name'     => 'Multiple',
	    'type'     => 'enum',
	    'notnull' => true,
            'default' => 'n',
            'options' => array(
                's' => 'Multiplo',
                'n' => "Não"
            )
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
         'nresponses' => array(
	    'name'     => 'Número de Respostas',
	    'type'     => 'int',
	    'size'     => '2',
            'default'  => '1',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
	    'button'     => array('button' => 'Gravar Form'),);
}