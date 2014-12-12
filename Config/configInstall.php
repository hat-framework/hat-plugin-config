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
            array('cod'=>'acesso' ,'title'=>'Dados de Acesso','icon'=>'fa fa-lock','ordem' =>'1'),
            array('cod'=>'pessoal','title'=>'Dados Pessoais' ,'icon'=>'fa fa-user','ordem' =>'2'),
            array('cod'=>'notify' ,'title'=>'Notificações'   ,'icon'=>'fa fa-globe','ordem' =>'3')
        ));
        
        $conta   = json_encode($this->formdata['conta']  , JSON_UNESCAPED_UNICODE);
        $phone   = json_encode($this->formdata['phone']  , JSON_UNESCAPED_UNICODE);
        $address = json_encode($this->formdata['address'], JSON_UNESCAPED_UNICODE);
        $mail    = json_encode($this->formdata['mail']   , JSON_UNESCAPED_UNICODE);
        $this->LoadModel('config/form', 'frm')->importDataFromArray(array(
            array('cod'=>'acesso_email'   , 'group'=>'acesso' ,'title'=>'Email'             ,'icon'=>'fa fa-envelope'  ,'ordem' =>'1','type' => 'component' , 'ref' => 'usuario/login/alterar', 'method'=>'email', 'form_data' => '', 'multiple'=>0),
            array('cod'=>'acesso_senha'   , 'group'=>'acesso' ,'title'=>'Senha'             ,'icon'=>'fa fa-lock'      ,'ordem' =>'2','type' => 'component' , 'ref' => 'usuario/login/alterar', 'method'=>'senha'),
            array('cod'=>'pessoal_phone'  , 'group'=>'pessoal','title'=>'Telefone'          ,'icon'=>'fa fa-phone'     ,'ordem' =>'3','type' => 'directdata', 'form_data' => $phone  , 'multiple'=>1),
            array('cod'=>'pessoal_address', 'group'=>'pessoal','title'=>'Endereço'          ,'icon'=>'fa fa-map-marker','ordem' =>'4','type' => 'directdata', 'form_data' => $address, 'multiple'=>1),
            array('cod'=>'pessoal_email'  , 'group'=>'pessoal','title'=>'Email Alternativo' ,'icon'=>'fa fa-map-marker','ordem' =>'5','type' => 'directdata', 'form_data' => $mail   , 'multiple'=>1),
            
            array('cod'=>'notify_conta'   , 'group'=>'notify' , 'title'=>'Notificações da Conta'  ,'ordem' =>'1','icon'=>'fa fa-user'      ,'type' => 'directdata', 'form_data' => $conta),
        ));
        return true;
    }
    private $formdata = array(
        'address' => array(
            'cep' => array(
                'name'     => 'Cep',
                'type'     => 'int',
                'size'     => '8',
                'especial' => 'cep',
                'notnull' => true,
                'grid'    => true,
                'display' => true,
            ),
             'rua' => array(
                'name'     => 'Rua',
                'type'     => 'varchar',
                'size'     => '190',
                'notnull' => true,
                'grid'    => true,
                'display' => true,
            ),
             'numero' => array(
                'name'     => 'Número',
                'type'     => 'varchar',
                'size'     => '10',
                'notnull' => true,
                'grid'    => true,
                'display' => true,
            ),
             'complemento' => array(
                'name'     => 'Complemento',
                'type'     => 'varchar',
                'size'     => '199',
                'grid'    => true,
                'display' => true,
            ),
             'bairro' => array(
                'name'     => 'Bairro',
                'type'     => 'varchar',
                'size'     => '64',
                'notnull' => true,
                'grid'    => true,
                'display' => true,
            ),
             'cidade' => array(
                'name'     => 'Cidade',
                'type'     => 'varchar',
                'size'     => '64',
                'grid'    => true,
                'display' => true,
            ),
             'estado' => array(
                'name'     => 'Estado',
                'type'     => 'enum',
                'options'  => array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins"),
                'notnull' => true,
                'grid'    => true,
                'display' => true,
            ),
            
            'button' => array('button' => "Salvar Endereço")
        ),
        
        'phone'   => array(
            
            'type' => array(
                'name'     => 'Tipo',
                'type'     => 'enum',
                'default'  => 'fixo',
                'options'  => array(
                    'fixo'     => "Residencial",
                    'trabalho' => "Trabalho",
                    'tim'      => "Celular Tim",
                    'oi'       => "Celular Oi",
                    'claro'    => "Celular Claro",
                    'vivo'     => "Celular Vivo",
                    'outro'    => "Outra operadora de Celular",
                ),
                'notnull'  => true
            ),
            
            'numero' => array(
                'name'     => 'Número',
                'type'     => 'varchar',
                'size'     => '11',
                'especial' => 'telefone',
                'notnull'  => true,
                'grid'    => true,
                'display' => true,
            ),
            
            'button' => array('button' => "Salvar Telefone")
        ),
        'mail'   => array(
            'type' => array(
                'name'     => 'Tipo',
                'type'     => 'enum',
                'default'  => 'p',
                'options'  => array(
                    'p' => "Pessoal",
                    't' => "Trabalho"
                ),
                'notnull'  => true
            ),
            
            'email' => array(
                'name'     => 'Email',
                'type'     => 'varchar',
                'display'  => true,
                'size'     => '64',
                'notnull'  => true,
                'grid'     => true,
                'especial' => 'email',
                'description' => "Este email será utilizado para entrarmos em contato com você (Não será utilizado para fazer login)",
             ),
            
            'button' => array('button' => "Salvar Email")
        ),
        'conta'   => array(
            'modificacao' => array(
                'name'        => 'Receber notificação de alteração de Email e Senha',
                'description' => 'Receber notificação por email se o meu email for alterado no site (Aumenta a segurança)',
                'type'        => 'bit',
                'default'     => '1',
                'notnull'     => true
            ),
            
            'dados' => array(
                'name'        => 'Receber notificação de alteração Alteração de dados',
                'description' => 'Receber notificações por email se meus dados (exceto email e senha) forem alterados no site',
                'type'        => 'bit',
                'default'     => '1',
                'notnull'     => true
            ),
            
            'button' => array('button' => "Salvar Opção")
        ),
    );
    public function unstall(){return true;}
}
