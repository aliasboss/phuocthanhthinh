<?php

/* ------------------------------------------
 * @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
 * @PHONE: +84933731173
 * ----------------------------------------- */

class NHK_DefaultController extends Zend_Controller_Action {

    protected $_model;
    protected $_properties;
    protected $_itemPerPage;
    protected $_pageRange;
    protected $_section;
    protected $_link;
    protected $_total;
    protected $_logger;

    function parentInit() {
        
    }

    protected function setView($file='index/index.phtml') {
        $this->_helper->viewRenderer->setViewScriptPathSpec($file);
    }

    public function noRender() {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function noLayout() {
        $this->_helper->layout()->disableLayout();
    }

    public function noView() {
        $this->noRender();
        $this->noLayout();
    }

    public function setLayout($layout, $dir="") {

        $layoutPath = APPLICATION_PATH  . '/layouts/'.$dir;
    	$option = array ('layout' => $layout,
    		   'layoutPath' => $layoutPath );
    	Zend_Layout::startMvc ( $option );

    }

    public function appendJS($filename, $folder="js") {
        $this->view->headScript()->appendFile(ROOT_URL . "public/{$folder}/{$filename}.js");
    }

    public function appendCSS($filename, $folder="css") {
        $this->view->headLink()->appendStylesheet(ROOT_URL . "/public/{$folder}/{$filename}.css");
    }

    public function render_json($string) {
        echo json_encode($string);
    }

    public function require_authenticated() {
        if (!NHK_Auth_User::getInstance()->hasIdentity()) {
            $this->_redirect("/");
        }
    }

}

