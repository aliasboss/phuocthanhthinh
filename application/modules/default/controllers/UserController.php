<?php

class UserController extends NHK_DefaultController {

    public function init() {
        //$this->require_authenticated();
        $this->setLayout('main_thongke', 'default');
    }

    public function indexAction() {
        //$muser=new User;
        //print_r($muser->listall());
    }

    public function thongKeTaiKhoanAction() {
        //$this->setLayout('main', 'default');
        //$this->noLayout();
        $this->view->menu = 'thongke';
        $model = new User();
        $this->view->countUserActive = $model->countUserActive(date("m"));
        $this->view->data = $model->getAll();
    }

}
?>