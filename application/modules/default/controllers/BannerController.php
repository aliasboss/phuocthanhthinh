<?php

class BannerController extends NHK_DefaultController {

    public function init() {
        //$this->require_authenticated();
        $this->setLayout('main_thongke', 'default');
        $this->view->menu = 'thongke';
    }

    public function indexAction() {
       $model = new Banner();
        $this->view->countBannerActive = $model->countBannerActive(date("m"));
        $this->view->data = $model->getAll();
    }

  

}
?>