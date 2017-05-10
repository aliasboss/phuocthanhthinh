<?php

/* ------------------------------------------
 * @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
 * @PHONE: +84933731173
 * ----------------------------------------- */

class NHK_Utils {

	public static function toAscii($str) {
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_| -]+/", '-', $clean);
	
		return $clean;
	}
	
    public static function send_email($_auth, $pre_title, $name, $email, $content)
        {

                $conn_mail = new Zend_Mail_Transport_Smtp (
                                                'smtp.gmail.com',
                                                 array ( 'auth' => 'login',
                                                                 'username' => 'nhkhanh294@gmail.com',
                                                                 'password' => 'khanhsonic',
                                                                 'ssl' => 'ssl',
                                                                 'port' => 465 )
                );
                Zend_Mail::setDefaultTransport ( $conn_mail );
                $mail = new Zend_Mail ( 'UTF-8' );
                $mail->setBodyHtml ( $content );
                $mail->addTo ( 'thiensuvitinh29487@yahoo.com' );
                $mail->addCc('nhkhanh294@gmail.com');
                $mail->setSubject ( '['.$pre_title.'] Contact from websites: '.$name );
                $mail->setFrom ( $email, $name );
                $mail->setDefaultReplyTo($email);
                $mail->send ();
        }

	static function get_date($input = null, $format = 'Y-m-d H:i:s'){
		if($input == null){
			return date($format);
		}
		return date($format, $input);  
	}

        public static function cutStringUTF8($str,$len,$more) {
            if ($str == "" || $str == NULL)
                return $str;
            if (is_array($str))
                return $str;
            $str = trim($str);
            if (strlen($str) <= $len)
                return $str;
            $str = substr($str, 0, $len);
            if ($str != "") {
                if (!substr_count($str, " ")) {
                    if ($more)
                        $str .= " ...";
                    return $str;
                }
                while (strlen($str) && ($str[strlen($str) - 1] != " ")) {
                    $str = substr($str, 0, -1);
                }
                $str = substr($str, 0, -1);
                if ($more)
                    $str .= " ...";
            }
            return $str;
        }
    static function getDateFormat($inputdate = NULL, $format = 'Y-m-d g:i:s') {
        if ($inputdate == NULL)
            return false;

        $date = date($format, strtotime($inputdate));
        return $date;
    }

    static function getDateTimeNow() {        
        $date = gmdate("Y-m-d H:i:s ",time()+7*3600); 
        return $date;
    }
    static function getTimestampNow() {
        return time()+7*3600;
    }

    static function stringDate($date){
        $arr_thu = array(0=>'Chủ nhật',1=>'Thứ hai',2=>'Thứ ba',3=>'Thứ tư',4=>'Thứ năm',5=>'Thứ sáu',6=>'Thứ bảy');
        $str = '';
        $time = strtotime($date);
        $str = $arr_thu[date("w",$time)]. ", Ngày ".date("d",$time). ' Tháng '.date("m",$time). ' Năm '.date("Y",$time);
        return $str;
    }

	static function cv_copy_file($oldfilename, $new_prename)
	{
		$oldfilepath = D_URL::getPath('cv_tmp');
		$newfilepath = D_URL::getPath('cv');
		//if (!file_exists($newfilepath)) {
		//	mkdir($newfilepath, 0777);
		//}
		
		$pathinfo = pathinfo($oldfilename); 
		$ext = strtolower($pathinfo['extension']); 
		$oldfilename = basename($oldfilename);
		
		$newfilename = $new_prename.".".$ext;
		//echo "--".$oldfilepath."/".$oldfilename;
		//echo "<br/>";
		//echo "---".$newfilepath."/".$newfilename;
		//remove new file if exist
		if(file_exists ($newfilepath."/".$newfilename)){
			unlink($newfilepath."/".$newfilename);
		}
		//copy tmp to real
		$excopy = copy($oldfilepath."/".$oldfilename, $newfilepath."/".$newfilename);
		//remove tmp
		$exdel = unlink($oldfilepath."/".$oldfilename);
		return $newfilename;
	}

        static function copy_rename_file($path_source,$path_des,$old_name, $new_name)
	{		

		$pathinfo = pathinfo($path_source.'/'.$old_name);
		$ext = strtolower($pathinfo['extension']);
		$old_name = basename($old_name);

		$newfilename = $new_name.".".$ext;
		
		if(file_exists ($newfilepath."/".$newfilename)){
			unlink($newfilepath."/".$newfilename);
		}
		//copy tmp to real
		$excopy = copy($path_source."/".$old_name, $path_des."/".$newfilename);

		//remove tmp
		$exdel = unlink($path_source."/".$old_name);
		return $newfilename;
	}
        static function copy_rename_file2($path_source,$path_des,$old_name, $new_name)
	{
               
		$pathinfo = pathinfo($path_source.'/'.$old_name);

               
		$ext = strtolower($pathinfo['extension']);
		$old_name = basename($old_name);

                
                if($new_name == ''){
                   $new_name = $pathinfo['filename'];
                   
                }

		$newfilename =  D_Utils::generateUrlSlug($new_name).".".$ext;

		if(file_exists ($path_des."/".$newfilename)){
			$newfilename = $new_name.'-'.date("YmdHis").".".$ext;
		}
		//copy tmp to real
		$excopy = copy($path_source."/".$old_name, $path_des."/".$newfilename);

		//remove tmp
		$exdel = unlink($path_source."/".$old_name);
		return $newfilename;
	}
	
    static function page_not_found($module='default') {
        D_Utils::error_by_code(404, $module);
    }

    static function db_timeout($module='default') {
        D_Utils::error_by_code(511, $module);
    }

    static function session_timeout($module='default') {
        D_Utils::error_by_code(509, $module);
    }

    static function access_finish($module='default') {
        D_Utils::error_by_code(508, $module);
    }

    static function not_support_browser($module='default') {
        D_Utils::error_by_code(507, $module);
    }

    static function log_err($message) {
        $logger = Zend_Registry::get("logger");
        $logger->err($message);
    }

    static function error_by_code($code, $module='default') {
        $error = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
        $error->type = $code;
        $params = array('error_handler' => $error);
        $front = Zend_Controller_Front::getInstance();
        $request = $front->getRequest();
        $request->setModuleName($module)->setControllerName("Error")->setActionName("error")->setParams($params)->setDispatched(false);
    }

    static function getResource($name= 'common') {
        $resource = new Zend_Config_Ini("./application/configs/application.ini", $name);
        return $resource->toArray();
    }

    static function array_category() {
        return array('level1', 'level2', 'level3');
    }

    static function common() {
        return Zend_Registry::get("common");
    }

    static function getUserId() {
        if (D_Auth_Userco::getInstance()->hasIdentity()) {
            //var_dump(D_Auth_Userco::getInstance()->getIdentity());
            return D_Auth_Userco::getInstance()->getIdentity()->UserCoID;
        }
    }

    static function getServicerId() {
        if (D_Auth_Servicer::getInstance()->hasIdentity()) {
            return D_Auth_Servicer::getInstance()->getIdentity()->ServicerID;
        }
    }

    static function view() {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        //$viewRenderer->init();
        return $viewRenderer->view;
    }

    /* ----------------------------------------
     * set up data for layout
     * --------------------------------------- */

    static function getAllState() {
        $result = array();
        $objList = DB_Utils::execQueryString("select * from area where DeleteFlag=0");
        for ($i = 0; $i < count($objList); $i++) {
            $objListState = DB_Utils::execQueryString("select * from state where AreaID='{$objList[$i]['AreaID']}' and DeleteFlag=0");
            $result[$i]['AreaName'] = $objList[$i]['Name'];
            $result[$i]['State'] = $objListState;
        }
        return $result;
    }

    static function getAllCategory() {
        $cat = new Category();
        return $cat->getAllCategory();
    }        

    static function listOrder() {
        $adapter = Zend_Db_Table::getDefaultAdapter();
        $sql = $adapter->select()
                        ->from(array('c' => 'communicationcomment'), array('DISTINCT(c.OrderID)'))
                        ->join(array('o' => 'order'), 'o.OrderID = c.OrderID', null)
                        ->join(array('s' => 'service'), 'o.ServiceID = s.ServiceID', array('ServiceID', 'ServiceName', 'CatchCopy'))
                        ->where("o.UserCoID=?", D_Auth::user()->UserCoID)
                        ->where("o.DeleteFlag = 0")
                        ->where("s.DeleteFlag = 0")
                        ->where("c.DeleteFlag = 0")
                        ->order(array("o.RegistDatetime desc"))
                        ->limit(3);
        $result = $adapter->fetchAll($sql);
        return $result;
    }

    static function listBookmark() {
        $adapter = Zend_Db_Table::getDefaultAdapter();
        $sql = $adapter->select()
                        ->from(array('b' => 'bookmark'), null)
                        ->join(array('s' => 'service'), 'b.ServiceID = s.ServiceID', array('ServiceID', 'ServiceName', 'CatchCopy'))
                        ->where("b.UserCoID=?", D_Auth::user()->UserCoID)
                        ->where("b.DeleteFlag = 0")
                        ->where("s.DeleteFlag = 0")
                        ->order(array("BookmarkID desc"))
                        ->limit(3);
        $result = $adapter->fetchAll($sql);
        return $result;
    }

    static function getContactTypebyName($type) {
        $array_type = array(1, 2, 3, 4, 5);
        if (in_array($type, $array_type)) {
            $ini = new Zend_Config_Ini('./application/configs/application.ini', 'ordertype_userco');
            $types = $ini->toArray();
            return $types[$type];
        }
    }

    static function getOrderStatusForUserco($statusInt) {
        $res = D_Utils::getResource('ordertype_userco');
        return $res[$statusInt];
    }

    static function moduleName() {
        return Zend_Controller_Front::getInstance ()->getRequest()->getModuleName();
    }

    static function controllerName() {
        return Zend_Controller_Front::getInstance ()->getRequest()->getControllerName();
    }

    static function actionName() {
        return Zend_Controller_Front::getInstance ()->getRequest()->getActionName();
    }

    static function request() {
        return Zend_Controller_Front::getInstance ()->getRequest();
    }

    static function generate_validate_code($length = 7) {
        $a = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789";
        $return = "";
        for ($i = 0; $i < $length; $i++)
            $return .= $a [rand(0, D_Utf8::len($a) - 1)];
        return $return;
    }

    static function sendmail($subject, $body, $to = '', $from = '', $reply = '') {
        $config_mail = new Zend_Config_Ini('./application/configs/config.ini', 'mail');

        $from = $from == '' ? $config_mail->mail->from : $from;
        $to = $to == '' ? $config_mail->mail->to : $to;
        $reply = $reply == '' ? $config_mail->mail->reply : $reply;
        $mail = new Zend_Mail('UTF-8');

        $mail->setBodyHtml($body, 'UTF-8');
        $mail->setFrom($from);
        $mail->setReplyTo($reply);
        $mail->addTo($to);
        $mail->setSubject($subject);
        /* Mail */
        if ($mail->send()) {
            return true;
        }
        return false;
    }

    static function setPreUrl($request) {
        //$pre_url="/".$request->getControllerName()."/".$request->getActionName();
        $url = str_replace($request->getBaseUrl(), '', $_SERVER ['REQUEST_URI']);
        setcookie('pre_url', $url, time () + 3600, '/');
    }

    static function getPreUrl() {
        if (isset($_COOKIE ['pre_url'])) {
            return $_COOKIE ['pre_url'];
        }
        return "/";
    }

    static function getDateFormatDB($inputdate = NULL, $format = 'Y-m-d') {
        if ($inputdate == NULL || $inputdate == '')
            return false;

        if (preg_match('/^\s*(\d\d?)[^\w](\d\d?)[^\w](\d{1,4}\s*$)/', $inputdate, $match)) {
            $date = $match [2] . '/' . $match [1] . '/' . $match [3];
        }
        if (!isset($date) || $date == '') {
            return false;
        }

        $date = date($format, strtotime($date));
        return $date;
    }

   

    public static function spliteStringByComma($str) {
        $arrays = split(',', $str);
        return $arrays;
    }

    public static function truncateString_utf8($str, $len, $charset="UTF-8"){
        $str = html_entity_decode($str, ENT_QUOTES, $charset);
        if(mb_strlen($str, $charset)> $len){
            $arr = explode(' ', $str);
            $str = mb_substr($str, 0, $len, $charset);
            $arrRes = explode(' ', $str);
            $last = $arr[count($arrRes)-1];
            unset($arr);
            if(strcasecmp($arrRes[count($arrRes)-1], $last))   unset($arrRes[count($arrRes)-1]);
          return implode(' ', $arrRes)."...";
       }
        return $str;
    }

    public static function spliteString($str, $max) {
        $arrays = str_split($str);
        $count_arrays = count($arrays);
        $result = "";
        $place_space = $max;
        for ($i = 0; $i < $count_arrays; $i++) {
            $item = $arrays [$i];
            if ($i > $max) {
                $result = substr($str, 0, $place_space);
                break;
            }
            if ($item == " ") {
                $place_space = $i;
            }
        }

        if ($result == "") {
            $result = substr($str, 0, $place_space);
        }

        return $result;
    }

    public static function convertToAlias($value) {
    	$value = preg_replace("/(“|”)/", '', $value);
        $value = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $value);
		$value = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $value);
		$value = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $value);
		$value = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $value);
		$value = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $value);
		$value = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $value);
		$value = preg_replace("/(đ)/", 'd', $value);
		$value = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $value);
		$value = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $value);
		$value = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $value);
		$value = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $value);
		$value = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $value);
		$value = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $value);
		$value = preg_replace("/(Đ)/", 'D', $value);
        $value = strtolower($value);
        $value = str_replace("#", " ", $value);
        $value = str_replace("$", " ", $value);
        $value = str_replace("%", " ", $value);
        $value = str_replace("&", " ", $value);
        $value = str_replace("/", " ", $value);
        $value = str_replace("\\", " ", $value);
        $value = str_replace("*", " ", $value);
        $value = str_replace("@", " ", $value);
        $value = str_replace(")", " ", $value);
        $value = str_replace("(", " ", $value);
        $value = str_replace("}", " ", $value);
        $value = str_replace("{", " ", $value);
        $value = str_replace("[", " ", $value);
        $value = str_replace("]", " ", $value);
        $value = str_replace("-", " ", $value);
        $value = str_replace("+", " ", $value);
        $value = str_replace(".", " ", $value);
        $value = str_replace(":", " ", $value);
        $value = str_replace(",", " ", $value);
        $value = str_replace("?", " ", $value);
        $value = str_replace("!", " ", $value);
        $value = str_replace("'", " ", $value);
        $value = str_replace("\"", " ", $value);
        $value = str_replace("â€", " ", $value);
        $value = str_replace("â€", " ", $value);
        $value = str_replace("96;", " ", $value);
        $value = str_replace("gt;", " ", $value);
        $value = str_replace("~", " ", $value);
        $value = str_replace("<", " ", $value);
        $value = str_replace("^", " ", $value);

        $value = preg_replace("/[\s]+/", "-", trim($value));
        $value = preg_replace("/[-]+$/", "", $value);
        $value = preg_replace("/^[-]+/", "", $value);
        return $value;
    }

    static function post_slug($str) {
        $a = array('á»˜', 'á»™', 'á»¨', 'á»©', 'á»š', 'á»›', 'á»†', 'á»‡', 'á»¢', 'á»£', 'á»°', 'á»±', 'á»ž', 'á»Ÿ', 'á»¬', 'á»­', 'á»”', 'á»•', 'á»�', 'á»‘', 'á»¬', 'á»­', 'áº¦', 'áº§', 'áº¬', 'áº­', 'áº®', 'áº¯', 'áº°', 'áº±', 'á»ª', 'á»«', 'áº¾', 'áº¿', 'áº¤', 'áº¥', 'áº¢', 'áº£', 'á»€', 'á»�', 'á»®', 'á»¯', 'á»Š', 'á»‹', 'áº³', 'áº²', 'Ã€', 'Ã�', 'Ã‚', 'Ãƒ', 'Ã„', 'Ã…', 'Ã†', 'Ã‡', 'Ãˆ', 'Ã‰', 'ÃŠ', 'Ã‹', 'ÃŒ', 'Ã�', 'ÃŽ', 'Ã�', 'Ã�', 'Ã‘', 'Ã’', 'Ã“', 'Ã”', 'Ã•', 'Ã–', 'Ã˜', 'Ã™', 'Ãš', 'Ã›', 'Ãœ', 'Ã�', 'ÃŸ', 'Ã ', 'Ã¡', 'Ã¢', 'Ã£', 'Ã¤', 'Ã¥', 'Ã¦', 'Ã§', 'Ã¨', 'Ã©', 'Ãª', 'Ã«', 'Ã¬', 'Ã­', 'Ã®', 'Ã¯', 'Ã±', 'Ã²', 'Ã³', 'Ã´', 'Ãµ', 'Ã¶', 'Ã¸', 'Ã¹', 'Ãº', 'Ã»', 'Ã¼', 'Ã½', 'Ã¿', 'Ä€', 'Ä�', 'Ä‚', 'Äƒ', 'Ä„', 'Ä…', 'Ä†', 'Ä‡', 'Äˆ', 'Ä‰', 'ÄŠ', 'Ä‹', 'ÄŒ', 'Ä�', 'ÄŽ', 'Ä�', 'Ä�', 'Ä‘', 'Ä’', 'Ä“', 'Ä”', 'Ä•', 'Ä–', 'Ä—', 'Ä˜', 'Ä™', 'Äš', 'Ä›', 'Äœ', 'Ä�', 'Äž', 'ÄŸ', 'Ä ', 'Ä¡', 'Ä¢', 'Ä£', 'Ä¤', 'Ä¥', 'Ä¦', 'Ä§', 'Ä¨', 'Ä©', 'Äª', 'Ä«', 'Ä¬', 'Ä­', 'Ä®', 'Ä¯', 'Ä°', 'Ä±', 'Ä²', 'Ä³', 'Ä´', 'Äµ', 'Ä¶', 'Ä·', 'Ä¹', 'Äº', 'Ä»', 'Ä¼', 'Ä½', 'Ä¾', 'Ä¿', 'Å€', 'Å�', 'Å‚', 'Åƒ', 'Å„', 'Å…', 'Å†', 'Å‡', 'Åˆ', 'Å‰', 'ÅŒ', 'Å�', 'ÅŽ', 'Å�', 'Å�', 'Å‘', 'Å’', 'Å“', 'Å”', 'Å•', 'Å–', 'Å—', 'Å˜', 'Å™', 'Åš', 'Å›', 'Åœ', 'Å�', 'Åž', 'ÅŸ', 'Å ', 'Å¡', 'Å¢', 'Å£', 'Å¤', 'Å¥', 'Å¦', 'Å§', 'Å¨', 'Å©', 'Åª', 'Å«', 'Å¬', 'Å­', 'Å®', 'Å¯', 'Å°', 'Å±', 'Å²', 'Å³', 'Å´', 'Åµ', 'Å¶', 'Å·', 'Å¸', 'Å¹', 'Åº', 'Å»', 'Å¼', 'Å½', 'Å¾', 'Å¿', 'Æ’', 'Æ ', 'Æ¡', 'Æ¯', 'Æ°', 'Ç�', 'ÇŽ', 'Ç�', 'Ç�', 'Ç‘', 'Ç’', 'Ç“', 'Ç”', 'Ç•', 'Ç–', 'Ç—', 'Ç˜', 'Ç™', 'Çš', 'Ç›', 'Çœ', 'Çº', 'Ç»', 'Ç¼', 'Ç½', 'Ç¾', 'Ç¿');
        $b = array('o', 'o', 'u', 'u', 'o', 'o', 'e', 'e', 'o', 'o', 'u', 'u', 'o', 'o', 'u', 'u', 'o', 'o', 'o', 'o', 'u', 'u', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'u', 'u', 'e', 'e', 'a', 'a', 'a', 'a', 'e', 'e', 'u', 'u', 'i', 'i', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        $str = str_replace($a, $b, $str);
        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $str));
    }
    
    static function generateUrlSlug($string, $maxlen = 0) {
        $string = NHK_Utils::convertToAlias($string);
        $string = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($string)), '-');
        if ($maxlen && NHK_Utf8::len($string) > $maxlen) {
            $string = substr($string, 0, $maxlen);
            $pos = strrpos($string, '-');
            if ($pos > 0) {
                $string = substr($string, 0, $pos);
            }
        }
        return $string;
    }

    public function creatSimplePaging($total, $num_record_per_page, $current_page, $jsfunction, $jstrailer = '') {
        if ($num_record_per_page == 0 || $num_record_per_page == null)
            $num_record_per_page = 1;
        $intval = intval($total / $num_record_per_page);
        $intval += $total % $num_record_per_page > 0 ? 1 : 0;
        $arrayRecord = $this->creatPaging($total, $num_record_per_page, $current_page);
        $result = '';
        $result .= '<div style="text-align:center;margin-top:5px;margin-bottom:5px">';
        //add back button
        $back = $current_page - 1;
        if ($current_page == 1 && $intval == 1) {
            return;
        }
        if ($arrayRecord != null) {
            foreach ($arrayRecord as $item) {
                if ($item == $current_page) {

                    $result .= '<a class="simple_paging_current">' . $current_page . '</a>';
                } else {
                    $result .= '<a  class="simple_paging_page"  href="javascript:' . $jsfunction . '(' . $item . $jstrailer . ')">' . $item . '</a>';
                }
            }
        }
        $next = $current_page + 1;
        $result .= '<div class="clear"></div></div>';

        return $result;
    }

    public function creatPaging($totalRecord, $numberRecordPerPage, $currentPage) {
        $result = array();
        $intval = intval($totalRecord / $numberRecordPerPage);
        $intval += $totalRecord % $numberRecordPerPage > 0 ? 1 : 0;
        if ($currentPage < 0 || $currentPage > $intval) {
            return $result;
        }
        //dich ve ben trai 4 buoc
        for ($i = $currentPage; $i > 0 && $i > $currentPage - 4; $i--) {
            $result [$i] = $i;
        }
        //dich ve ben phai 4 buoc
        for ($k = $currentPage + 1; $k <= $intval && $k < $currentPage + 4; $k++) {
            $result [$k] = $k;
        }
        ksort($result);
        return $result;
    }

    /**
     * Author: Nguyen Thanh Trung
     * @param $figures
     */
    static function getFigures() {
        $figures = new Zend_Config_Ini("./application/configs/application.ini", "figures");
        return $figures;
    }

    static function generateRandomID($figures) {
        $a = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789";
        for ($i = 0; $i < 13; $i++)
            $figures .= $a [rand(0, strlen($a) - 1)];
        return $figures;
    }

    static function getFullDomain($root=true) {
        $full_address = 'http';
        if (!empty($_SERVER["HTTPS"])) {
            $full_address .= "s";
        }
        $full_address .= "://";
        if ($root == true) {
            $full_address .= $_SERVER["SERVER_NAME"] . ROOT_URL;
        } else {
            $full_address .= $_SERVER["SERVER_NAME"];
        }
        return $full_address;
    }

    static function curPageURL() {
        $full_address = 'http';
        if (!empty($_SERVER["HTTPS"])) {
            $full_address .= "s";
        }
        $full_address .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }

        return $pageURL;
    }

    static function getAppConfig($section, $element='') {
        $config = new Zend_Config_Ini("./application/configs/application.ini", $section);

        if ($element != '') {
            $config = $config->$element;
        }
        return $config;
    }

   
    /*
     *  //example
      $headerArray=array("column1","column2");
      $data=array(
      array("data1","data2"),
      array("data1","data2"),
      array("data1","data2"),
      array("data1","data2"),
      );
      D_Utils::exportToCSVFile($headerArray,$data);
     */

    static function exportToCSVFile($headerArray, $data=null, $filename=null) {
        $headerArray = array_values($headerArray);
        $separator = "\t";
        $terminated = "\r\n";
        $csv = "";
        if ($filename == null)
            $filename = date('Y-m-d H:i:s') . "_csv_file.csv";

        for ($i = 0; $i < count($headerArray); $i++) {
            if ($i != count($headerArray) - 1)
                $csv .= $headerArray[$i] . $separator;
            else
                $csv .= $headerArray[$i] . $terminated;
        }

        if ($data != null)
            for ($i = 0; $i < count($data); $i++) {
                $j = 0;
                foreach ($data[$i] as $value) {
                    if ($j < count($headerArray) - 1)
                        $csv .= $value . $separator;
                    else
                        $csv .= $value . $terminated;
                    $j++;
                }
            }

        $csv = chr(255) . chr(254) . mb_convert_encoding($csv, "UTF-16LE", "UTF-8");

        header("Content-type: application/x-msdownload");
        header("Content-disposition: csv; filename=" . $filename . "; size=" . strlen($csv));
        echo $csv;
        exit();
    }

    /**
     * Author: Nguyen Minh Thanh
     * @param array
     * @param array $sort_by
     */
    static function multisort($array, $sort_by) {
        foreach ($array as $key => $value) {
            $evalstring = '';
            foreach ($sort_by as $sort_field) {
                $tmp[$sort_field][$key] = $value[$sort_field];
                $evalstring .= '$tmp[\'' . $sort_field . '\'], ';
            }
        }
        $evalstring .= '$array';
        $evalstring = 'array_multisort(' . $evalstring . ');';
        eval($evalstring);

        return $array;
    }
    
    static function getAfterServicerStatus($currentStatus)
    {
    	$result = array();    	
		switch ($currentStatus)
		{
			case 1: 
				$result[] = 5; 
				break;
			case 2: 
				$result[] =  3; 
				$result[] = 4; 
				break;			
			case 3: 
				$result[] = 2; 
				$result[] = 4; 
				break;	
			default:
				$result = array(1,2,3,4,5);
				break;
		}		
		return $result;
    }
    
    static function uploadImage($field, $fileName, $maxSize, $maxW, $fullPath, $relPath, $colorR, $colorG, $colorB, $maxH = null){
	    if(!is_dir($relPath))
	    	mkdir($relPath, 0777);
	    	
    	$folder = $relPath . '/';
		$maxlimit = $maxSize;
		$allowed_ext = "jpg,jpeg,gif";
		$match = "";
		$errorList = array();
		$filesize = $_FILES[$fileName]['size'];
		if($filesize > 0){	
			$filename = strtolower($_FILES[$fileName]['name']);
			$filename = preg_replace('/\s/', '_', $filename);
		   	if($filesize < 1){ 
				$errorList[] = "File size is empty.";
			}
			if($filesize > $maxlimit){ 
				$errorList[] = "File size is too big.";
			}
			if(count($errorList)<1){
				$file_ext = preg_split("/\./",$filename);
				$allowed_ext = preg_split("/\,/",$allowed_ext);
				foreach($allowed_ext as $ext){
					if($ext==end($file_ext)){
						$match = "1"; // File is allowed
						$NUM = time();
						$front_name = substr($file_ext[0], 0, 15).rand(1,1000000);
						$newfilename = $front_name.".".end($file_ext);
						$filetype = end($file_ext);
						$save = $folder.$newfilename;
						
						if (file_exists($save))
						{
							unlink($save);
						}
						
						if(!file_exists($save)){
							list($width_orig, $height_orig) = getimagesize($_FILES[$fileName]['tmp_name']);
							if($maxH == null){
								if($width_orig < $maxW){
									$fwidth = $width_orig;
								}else{
									$fwidth = $maxW;
								}
								$ratio_orig = $width_orig/$height_orig;
								$fheight = $fwidth/$ratio_orig;
								
								$blank_height = $fheight;
								$top_offset = 0;
									
							}else{
								if($width_orig <= $maxW && $height_orig <= $maxH){
									$fheight = $height_orig;
									$fwidth = $width_orig;
								}else{
									if($width_orig > $maxW){
										$ratio = ($width_orig / $maxW);
										$fwidth = $maxW;
										$fheight = ($height_orig / $ratio);
										if($fheight > $maxH){
											$ratio = ($fheight / $maxH);
											$fheight = $maxH;
											$fwidth = ($fwidth / $ratio);
										}
									}
									if($height_orig > $maxH){
										$ratio = ($height_orig / $maxH);
										$fheight = $maxH;
										$fwidth = ($width_orig / $ratio);
										if($fwidth > $maxW){
											$ratio = ($fwidth / $maxW);
											$fwidth = $maxW;
											$fheight = ($fheight / $ratio);
										}
									}
								}
								if($fheight == 0 || $fwidth == 0 || $height_orig == 0 || $width_orig == 0){
									die("FATAL ERROR REPORT ERROR CODE [add-pic-line-67-orig] to <a href='http://www.atwebresults.com'>AT WEB RESULTS</a>");
								}
								if($fheight < 45){
									$blank_height = 45;
									$top_offset = round(($blank_height - $fheight)/2);
								}else{
									$blank_height = $fheight;
								}
							}
							$image_p = imagecreatetruecolor($fwidth, $blank_height);
							$white = imagecolorallocate($image_p, $colorR, $colorG, $colorB);
							imagefill($image_p, 0, 0, $white);
							switch($filetype){
								case "gif":
									$image = @imagecreatefromgif($_FILES[$fileName]['tmp_name']);
								break;
								case "jpg":
									$image = @imagecreatefromjpeg($_FILES[$fileName]['tmp_name']);
								break;
								case "jpeg":
									$image = @imagecreatefromjpeg($_FILES[$fileName]['tmp_name']);
								break;
								case "png":
									$image = @imagecreatefrompng($_FILES[$fileName]['tmp_name']);
								break;
							}
							@imagecopyresampled($image_p, $image, 0, $top_offset, 0, 0, $fwidth, $fheight, $width_orig, $height_orig);
							switch($filetype){
								case "gif":
									if(!@imagegif($image_p, $save)){
										$errorList[]= "PERMISSION DENIED [GIF]";
									}
								break;
								case "jpg":
									if(!@imagejpeg($image_p, $save, 100)){										
										$errorList[]= "PERMISSION DENIED [JPG]";
									}
								break;
								case "jpeg":
									if(!@imagejpeg($image_p, $save, 100)){
										$errorList[]= "PERMISSION DENIED [JPEG]";
									}
								break;
								case "png":
									if(!@imagepng($image_p, $save, 0)){
										$errorList[]= "PERMISSION DENIED [PNG]";
									}
								break;
							}
							@imagedestroy($filename);
						}else{
							$errorList[]= "CANNOT MAKE IMAGE IT ALREADY EXISTS";
						}	
					}
				}		
			}
		}else{
			$errorList[]= "NO FILE SELECTED";
		}
		if(!$match){
		   	$errorList[]= "File type isn't allowed: $filename";
		}
		if(sizeof($errorList) == 0){
			return SERVICE_IMG_URL . '/' . $fullPath.'/'.$newfilename;
		}else{
			$eMessage = array();
			for ($x=0; $x<sizeof($errorList); $x++){
				$eMessage[] = $errorList[$x];
			}
		   	return $eMessage;
		}
	}
	
    public static function simpleHTMLEncode($value) {
        $value = str_replace('"', '&quot;', $value);
	$value = str_replace("'", '&#039;', $value);
	$value = str_replace("&", '&amp;', $value);
	$value = str_replace("<", '&lt;', $value);
	$value = str_replace(">", '&gt;', $value);
	return $value;
    }
		
		
		public static function resizeRatio($url,$width,$height){
			$size = getimagesize($url);
			
		$ratio1=($size[0])/$width;
    $ratio2=($size[1])/$height;
     if($ratio1>$ratio2)    {
         $thumb_w=$width;
         $thumb_h=($size[1])/$ratio1;
     }
     else {
         $thumb_h=$height;
         $thumb_w=($size[0])/$ratio2;
     }
		 return array($thumb_w,$thumb_h);
		}


    public static function replaceLinkRedirect($str,$game_id,$type='',$type_id=''){
        $host = 'game4t.com';

        if(isset($game_id)&&$game_id!=''){
            $params = 'game='.$game_id;
        }else{
            $params = 'game=none';
        }
        
        
        if(isset($type)&&$type!=''){
            $params .= '&type='.$type;
        }
        if(isset($type_id)&&$type_id!=''){
            $params .= '&type_id='.$type_id;
        }

        

        $str1 = str_replace('href="http://', 'href="http://'.$host.'/redirect?'.$params.'&url=http://', $str);
        $str2 = str_replace('href="http://'.$host.'/redirect?'.$params.'&url=http://'.$host, 'href="http://'.$host, $str1);

        return $str2;
    }
}
