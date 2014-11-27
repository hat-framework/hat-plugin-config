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
            array('cod'=>'pessoal','title'=>'Dados Pessoais','icon'=>'fa fa-user','ordem' =>'1'),
            array('cod'=>'notify' ,'title'=>'Notificações' ,'icon'=>'fa fa-globe','ordem' =>'2')
        ));
        
        $mercado = json_encode($this->formdata['mercado'], JSON_UNESCAPED_UNICODE);
        $conta   = json_encode($this->formdata['conta']  , JSON_UNESCAPED_UNICODE);
        $phone   = json_encode($this->formdata['phone']  , JSON_UNESCAPED_UNICODE);
        $this->LoadModel('config/form', 'frm')->importDataFromArray(array(
            array('cod'=>'email'  , 'group'=>'pessoal','title'=>'Email'    ,'icon'=>'fa fa-envelope'  ,'ordem' =>'1','type' => 'component' , 'ref' => 'usuario/login/alterar', 'method'=>'email', 'form_data' => '', 'multiple'=>0),
            array('cod'=>'senha'  , 'group'=>'pessoal','title'=>'Senha'    ,'icon'=>'fa fa-lock'      ,'ordem' =>'2','type' => 'component' , 'ref' => 'usuario/login/alterar', 'method'=>'senha'),
            array('cod'=>'phone'  , 'group'=>'pessoal','title'=>'Telefone' ,'icon'=>'fa fa-phone'     ,'ordem' =>'3','type' => 'component' , 'ref' => 'usuario/login/alterar', 'method'=>'telefone'),
            array('cod'=>'address', 'group'=>'pessoal','title'=>'Endereço' ,'icon'=>'fa fa-map-marker','ordem' =>'4','type' => 'component' , 'ref' => 'usuario/login/alterar', 'method'=>'endereco'),
            array('cod'=>'phone2' , 'group'=>'pessoal','title'=>'Telefone' ,'icon'=>'fa fa-phone'     ,'ordem' =>'3','type' => 'directdata', 'form_data' => $phone, 'multiple'=>1),
            
            array('cod'=>'conta'  , 'group'=>'notify' , 'title'=>'Notificações da Conta'  ,'ordem' =>'1','icon'=>'fa fa-user'      ,'type' => 'directdata', 'form_data' => $conta),
            array('cod'=>'mercado', 'group'=>'notify' , 'title'=>'Atualizações do mercado','ordem' =>'2','icon'=>'fa fa-line-chart','type' => 'directdata', 'form_data' => $mercado)
        ));
        return true;
    }
    private $formdata = array(
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
                'name'     => 'Telefone Fixo',
                'type'     => 'varchar',
                'size'     => '11',
                'especial' => 'telefone',
                'grid'    => true,
                'display' => true,
            ),
            
            'button' => array('button' => "Salvar Telefone")
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
        'mercado' => array(
            
            'novas_empresas' => array(
                'name'        => 'Notificação de Novas Empresas',
                'description' => 'Receber por email quando novas empresas fizerem IPO',
                'type'        => 'bit',
                'default'     => '1',
                'notnull'     => true
            ),
            
            'balancos' => array(
                'name'        => 'Notificação de Balanços',
                'description' => 'Receber por email quando saírem novos Balanços das empresas listadas na bolsa',
                'type'        => 'bit',
                'default'     => '1',
                'notnull'     => true
            ),
            
            'dividendos' => array(
                'name'        => 'Notificação de Dividendos',
                'description' => 'Receber por email quando saírem novos dividendos das empresas listadas na bolsa',
                'type'        => 'bit',
                'default'     => '1',
                'notnull'     => true
            ),
            
            'proventos' => array(
                'name'        => 'Notificação de Proventos',
                'description' => 'Receber por email quando as empresas lançarem novos proventos (subscrição, bonificação, etc)',
                'type'        => 'bit',
                'default'     => '1',
                'notnull'     => true
            ),
            
            'button' => array('button' => "Salvar Opção")
        ),
    );
    public function unstall(){return true;}
}
