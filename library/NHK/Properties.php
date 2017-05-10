<?php

/* ------------------------------------------
 * @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
 * @PHONE: +84933731173
 * ----------------------------------------- */

class NHK_Properties {

  //Get key in application
  static function get($key, $section = null, $file = "./application/configs/vietnamese.ini") {
    $result = "";
    if ($section == null)
      $_messages = new Zend_Config_Ini($file,'public');
    else
      $_messages = new Zend_Config_Ini($file, $section);

    $result = $_messages->$key;
    
    return $result;
  }

}

?>