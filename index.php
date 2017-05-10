<?php
/*------------------------------------------
* @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
* @PHONE: +84933731173
* -----------------------------------------*/


//set error report
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors','on');
date_default_timezone_set('Asia/Bangkok');

//set include path
set_include_path('.' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . './application/models' . PATH_SEPARATOR . './application/common' . PATH_SEPARATOR . get_include_path());
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application')); 
define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/public'));


//include Loader file
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('NHK_'); 
$autoloader->registerNamespace('DB_');//register for database utility classes
$autoloader->setFallbackAutoloader(true); 
//init helper path
$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
$viewRenderer->init();
$view = $viewRenderer->view;
$view->addHelperPath('NHK/Helper'); 
Zend_Controller_Action_HelperBroker::addPath("D/Helper", "NHK_Helper");

if (isset($_POST['sid'])) {
	Zend_Session::setId($_POST['sid']);
}
Zend_Session::start();


$config = new Zend_Config_Ini('./application/configs/application.ini', 'development');


if($config->repair == 1){
  header('Content-Type: text/html; charset=utf-8');
  //echo '<img src="public/img/mainternance.jpg" title="Hiện tại website đang bảo trì. Vui lòng quay lại sau" /> ';
  echo "<h1>Hiện tại website đang bảo trì. Vui lòng quay lại sau. Xin cảm ơn.</h1>";
  exit();
}


//define base url
$baseUrl = $config->baseHttp;
define('BASE_URL', $baseUrl);

$baseUrlAdmin = $config->baseUrlAdmin;
define('BASE_URL_ADMIN', $baseUrlAdmin);

//URL_ADMIN
defined('URL_TEMPLATE_ADMIN')||define('URL_TEMPLATE_ADMIN',$config->urlTemplateAdmin);

//ROOT_URL
defined('ROOT_URL')||define('ROOT_URL',$config->rootUrl);


$registry = Zend_Registry::getInstance();
$registry->set('config',$config);



//config db
$db = Zend_Db::factory($config->db);
$db->getProfiler()->setEnabled(true);
Zend_Db_Table::setDefaultAdapter($db);

//config logger
$writer = new Zend_Log_Writer_Stream('./logs/development.txt');
$columnMapping = array('priority' => 'priority', 'message' => 'message');
//$writer = new Zend_Log_Writer_Db($db, 'logs', $columnMapping);
//$filter = new Zend_Log_Filter_Priority(Zend_Log::DEBUG);
//$writer->addFilter($filter);
$logger = new Zend_Log($writer);
Zend_Registry::set('logger', $logger);

//$logger->info('Informational message');

Zend_Layout::startMvc(array('layoutPath' => './application/layouts'));

$front = Zend_Controller_Front::getInstance();
$front->setControllerDirectory(array('default' => './application/modules/default/controllers','error' => './application/modules/error/controllers', 'admin' => './application/modules/admin/controllers')
							  );

$arr_perpage = array('10'=>'10','20'=>'20','30'=>'30');
Zend_Registry::set('perpage', $arr_perpage);

$GLOBAL_PROPERTIES = './application/configs/vietnamese.ini';
Zend_Registry::set('properties', $GLOBAL_PROPERTIES);

$arr_status = array('0'=>'Active','1'=>'Deleted',''=>'-All-');
Zend_Registry::set('status', $arr_status);
$arr_publish = array(''=>'-All-','1'=>'Publish','0'=>'Unpublish');
Zend_Registry::set('publish', $arr_publish);

//authentication
//$auth = NHK_Auth_Admin::getInstance();
//$auth->setStorage(new Zend_Auth_Storage_Session('hrdb_auth'));
//$acl = new NHK_Auth_Admin_Acl($auth);


//===== init navigation
//NHK_Navigation::init($view);



//$front->registerPlugin(
//	new NHK_Auth_Admin_AclPlugin($auth, $acl)
//);


//$front->registerPlugin(new NHK_Plugin_Main());

$front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
                              'module'     => 'error',
                              'controller' => 'error',
                              'action'     => 'error'
      )));


$configMail = array('ssl' => 'ssl', 'port' => 465, 'auth' => 'login', 'username' => 'info.game4t@gmail.com', 'password' => 'g4tv2app');
$smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $configMail);

/*
//send mail config
$tr = new Zend_Mail_Transport_Smtp('smtp.jv-it.com.vn', array(
			'auth' => 'login',
			'username' => 'hongkhanh@jv-it.com.vn',
			'password' => 'qi8&34ljxwoya'
			)
		);*/
if($_SERVER["SERVER_NAME"]=='localhost'){
	Zend_Mail::setDefaultTransport($smtpConnection);
}

//===============================================================

//========= ROUTER ==========
//$router = $front->getRouter();
//NHK_Router::init($router);

//END ROUTER===============================================================


$front->throwExceptions(true);
$front->dispatch();


