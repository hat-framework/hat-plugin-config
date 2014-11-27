<?php
class indexController extends \classes\Controller\Controller{
    public $model_name = 'config/index';
    
    public function index(){
        Redirect("config/group/form/pessoal/email");
    }
}
