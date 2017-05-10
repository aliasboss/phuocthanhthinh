<?php

class Admin_BotController extends NHK_AdminController {

  protected $_controller = 'bot';

  public function init() {
    $this->require_authenticated();
    $this->setLayout('index', 'admin');
    $this->_model = new News();

    $registry = Zend_Registry::getInstance();
    $config = $registry->get('config');
    $this->_itemPerPage = $config->itemPerPage;
    $this->_pageRange = $config->pageRange;

    $this->_section = $this->_controller;
    $this->view->section = $this->_section;

    $this->_link = $this->_controller;
    $this->view->link = $this->_link;


    $this->_properties = $registry->get('properties');
    $this->view->properties = $this->_properties;

    $this->_total = $this->_model->countAll();
    $this->view->total = $this->_total;
    
    $this->view->menu = $this->_controller;
  }

  public function indexAction() {
    
  }

  

}
?>