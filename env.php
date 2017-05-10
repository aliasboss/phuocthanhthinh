<?php
/*------------------------------------------
* @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
* @PHONE: +84933731173
* -----------------------------------------*/
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
   get_include_path(),
)));


require_once 'library/Zend/Loader/Autoloader.php'; 
$loader = Zend_Loader_Autoloader::getInstance(); 

$options = array(
    Zend_Db::ALLOW_SERIALIZATION => false
);
 
$params = array(
    'host'           => 'localhost',
    'username'       => 'mq75_main',
    'password'       => 'khanh@alias',
    'dbname'         => 'mq75_main',
    'charset'        => 'utf8',
    'options'        => $options
);
 
try {
    $db = Zend_Db::factory('MYSQLI', $params);
    $db->getConnection();
} catch (Zend_Db_Adapter_Exception $e) {
    // perhaps a failed login credential, or perhaps the RDBMS is not running
} catch (Zend_Exception $e) {
    // perhaps factory() failed to load the specified Adapter class
}

//$writer = new Zend_Log_Writer_Stream('logs/batch_log.txt');
//$logger = new Zend_Log($writer);
//$logger->setTimestampFormat( "Y-m-d H:i:s" );

$uagent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36 AlexaToolbar/alxg-3.1';


function get_curl($action,$post_params=''){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['uagent']);
    curl_setopt($ch, CURLOPT_URL, $action);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if($post_params != ''){
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
    }
    
    $html = curl_exec($ch);
   
    curl_close($ch);
    return $html;
}


?>
