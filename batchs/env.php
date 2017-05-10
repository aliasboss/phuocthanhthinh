<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Define base path obtainable throughout the whole application
defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__)));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', BASE_PATH . '/application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

set_include_path(implode(PATH_SEPARATOR, array(
   realpath(APPLICATION_PATH . '/../library'),
   APPLICATION_PATH . '/modules/admin/models' ,
   get_include_path(),
)));


/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
   APPLICATION_ENV,
   APPLICATION_PATH . '/configs/application.ini'
);


//Get config
$config = new Zend_Config_Ini(
        APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);

//define base url
$baseUrl = $config->baseHttp;
define('BASE_URL', $baseUrl);

$baseUrlAdmin = $config->baseUrlAdmin;
define('BASE_URL_ADMIN', $baseUrlAdmin);

//URL_ADMIN
defined('URL_TEMPLATE_ADMIN')||define('URL_TEMPLATE_ADMIN',$config->urlTemplateAdmin);

//ROOT_URL
defined('ROOT_URL')||define('ROOT_URL',$config->rootUrl);



//Properties file
$GLOBAL_PROPERTIES = './application/configs/vietnamese.ini';
Zend_Registry::set('properties', $GLOBAL_PROPERTIES);



//defined('GLOBAL_PROPERTIES')||define('GLOBAL_PROPERTIES',$GLOBAL_PROPERTIES);
$application->bootstrap()->run();