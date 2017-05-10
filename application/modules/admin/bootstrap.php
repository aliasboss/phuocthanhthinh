<?php
class Admin_Bootstrap extends Zend_Application_Module_Bootstrap{
    protected function _initAutoload(){
	$resourceLoader = new Zend_Loader_Autoloader_Resource( array(
	      'namespace' => '',
	      'basePath'  => APPLICATION_PATH ));

        $resourceLoader->addResourceType( 'model', 'models/', 'Model' );
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('NHK_');
    }
}