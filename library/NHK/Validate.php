<?php

/* ------------------------------------------
 * @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
 * @PHONE: +84933731173
 * ----------------------------------------- */

class NHK_Validate {

    protected $_properties;
    protected $_messages;
    private $_errors = array();

    const email_msg = "email not valid";
    const phone_msg = "phone number is not valid";
    const required_msg = " をご入力ください。";


    //------MESSAGE KEY = ERROR TYPE -----
    const mk_required = "required";
    const less = "less"; //it hon length
    const between = "between";
    const message_zip_code = "zip_code";
    const no_select = "no_select";
    const message_city = "city";
    const message_001 = "message_001";  //please input with 12 char
    const over_figures = "over_figures";
    const message_002 = "message_002"; //between
    const message_003 = "message_003"; //please input with 12 char kana
    const message_004 = "message_004";
    const message_005 = "message_005"; //to do not match
    const message_006 = "message_006"; // password
    const message_007 = "message_007"; // nick name
    const message_008 = "message_008";
    const message_009 = "message_009";
    const tmp_incorrect = "tmp_incorrect";

    //------ INDENT -------
    const indent_value = "value";
    const indent_value1 = "value1";
    const indent_value2 = "value2";
    const indent_len = "len";
    const indent_min = "min";
    const indent_max = "max";

    function __construct() {
        $this->_properties = new Zend_Config_Ini('./application/configs/validate.ini', 'properties');
        $this->_messages = new Zend_Config_Ini('./application/configs/validate.ini', 'messages');
    }

    function is_error() {
        if (count($this->_errors) > 0)
            return true;

        return false;
    }

    function add_error($key, $errors) {
        if (count($errors) > 0) {
            $this->_errors[$key] = $errors;
        }
        // $this->_errors[$key] = array();
    }

    function get_errors() {
        return $this->_errors;
    }

    static function is_email($email) {
        return Zend_validate::is($email, "EmailAddress");
    }

    static function is_number($num) {
        return preg_match("/^[0-9]+$/", $num);
    }

    static function is_username($username, $min=4, $max=20) {
        return preg_match("/^[a-zA-Z0-9_.]{{$min},{$max}}$/", $username);
    }

    static function is_password($password) {
        return preg_match("/^[a-zA-Z0-9_.\,\#\%\@\-\+\*]{4,40}$/", $password);
    }

    static function is_tel($tel) {
        return preg_match("/^[0-9_.]{10,11}$/", $tel);
    }

    static function is_phone($tel) {
        return preg_match("/^[0-9_.]{10,11}$/", $tel);
    }

    static function is_date($date, $format ='yyyy-MM-dd') {
        //yyyy-MM-dd hh:ii
        $dateValidator = new Zend_Validate_Date($format);
        if ($dateValidator->isValid($date)) {
            return true;
        } else {
            return false;
        }
    }

    //---------------------------------------
    // VALIDATE FUNCTION
    //-------------------------------------

    function required($key, $value) {
        $errors = array();

        $errors = self::validate_required($key, $value);

        $this->add_error($key, $errors);
    }

    function validate_required_and_between($key, $value, $min, $max) {
        $errors = array();

        $errors = self::required_and_between($key, $value, $min, $max);

        $this->add_error($key, $errors);
    }

    // array("username" => ["can't blank", "length than 6"] )
    function username($key, $value, $min = 6, $max = 50) {
        $errors = array();

        $errors = self::required_and_between($key, $value, $min, $max);

        $this->add_error($key, $errors);
    }

    // array("username" => ["can't blank", "length than 6"] )
    function password($key, $value, $min = 6, $max = 20) {
        $errors = array();

        $errors = self::required_and_between($key, $value, $min, $max);

        if (!NHK_Validate::is_password($value)) {
            //$errors[] = self::parse("mtk_password", array(NHK_Validate::indent_value => $key, 'min'=>$min,'max'=>$max ));
        }

        $this->add_error($key, $errors);
    }

    function email($key, $value) {

        $errors = array();
        $len = 100;
        if (trim($value) == '') {
            $errors[] = self::parse("mtk_required", array(NHK_Validate::indent_value => $key));
        }

        if (!NHK_Validate::is_email($value) || D_Utf8::len($value) > $len) {
            $errors[] = self::parse("mtk_email", array(NHK_Validate::indent_value => $key, NHK_Validate::indent_len => $len));
            ;
        }

        $this->add_error($key, $errors);
    }

    function required_and_date($key, $value) {
        $errors = array();
        if (trim($value) == '') {
            $errors[] = self::parse("mtk_required", array(NHK_Validate::indent_value => $key));
        }
        if (!self::is_date($value)) {
            $errors[] = self::parse("mtk_date", array(NHK_Validate::indent_value => $key));
        }

        $this->add_error($key, $errors);
    }

    function required_and_tel($key, $value) {
        $errors = array();
        if (trim($value) == '') {
            $errors[] = self::parse("mtk_required", array(NHK_Validate::indent_value => $key));
        }
        if (!self::is_tel($value)) {
            $errors[] = self::parse("mtk_tel", array(NHK_Validate::indent_value => $key));
        }

        $this->add_error($key, $errors);
    }

    function required_and_number($key, $value) {
        $errors = array();
        if (trim($value) == '') {
            $errors[] = self::parse("mtk_required", array(NHK_Validate::indent_value => $key));
        }
        if (!self::is_number($value)) {
            $errors[] = self::parse("mtk_number", array(NHK_Validate::indent_value => $key));
        }

        $this->add_error($key, $errors);
    }

    //---------------------------------------
    // END VALIDATE FUNCTION
    //-------------------------------------



    /* ------------------------------------------
     * PRIVATE MSG
     * @PHONE: +84933731173
     * ----------------------------------------- */
    function get_message_template($name) {
        //mkt = message template key
        return "mtk_{$name}";
    }

    function required_and_between($key, $value, $min=4, $max=20) {
        $errors = array();
        if (trim($value) == '') {
            $message_template = self::get_message_template("required");
            $errors[] = self::parse($message_template, array(NHK_Validate::indent_value => $key));
        }
        if (NHK_Utf8::len(trim($value)) > $max || NHK_Utf8::len(trim($value)) < $min) {
            $message_template = self::get_message_template("between");
            $errors[] = self::parse($message_template, array(NHK_Validate::indent_value => $key, NHK_Validate::indent_min => $min, NHK_Validate::indent_max => $max));
        }
        return $errors;
    }

//END function

    function validate_required($key, $value) {
        $errors = array();
        if (trim($value) == '') {
            $message_template = self::get_message_template("required");
            $errors[] = self::parse($message_template, array(NHK_Validate::indent_value => $key));
        }

        return $errors;
    }

//END function

    function not_number_or_not_between_figures($field, $property, $min=10, $max=11) {
        $errors = array();
        $property = self::get_property($property);
        if (!NHK_Validate::is_number($field) || D_Utf8::len(trim($field)) > $max || D_Utf8::len(trim($field)) < $min) {
            $errors[] = self::parse(NHK_Validate::message_001, array(NHK_Validate::indent_value => $property));
        }
        return $errors;
    }

    function required_not_number_or_not_between_figures($field, $property, $min=10, $max=11) {
        $errors = array();
        $property = self::get_property($property);
        if (trim($field) == '') {
            $errors[] = self::parse(NHK_Validate::mk_required, array(NHK_Validate::indent_value => $property));
        } elseif (!NHK_Validate::is_number($field) || D_Utf8::len(trim($field)) > $max || D_Utf8::len(trim($field)) < $min) {
            $errors[] = self::parse(NHK_Validate::message_002, array(NHK_Validate::indent_value => $property, NHK_Validate::indent_min => $min, NHK_Validate::indent_max => $max));
        }
        return $errors;
    }

    static function validate_required_array($array) {
        $errors = array();
        foreach ($array as $property => $field) {
            if (trim($field) == '') {
                $message = self::parse(NHK_Validate::mk_required, array(NHK_Validate::indent_value => self::get_message($property)));
                $errors[] = $message;
            }
        }
        return $errors;
    }

    static function validate_phone($tel, $field_name) {
        $errors = array();
        if (NHK_Validate::mk_required($tel, $field_name)) {
            $errors[] = NHK_Validate::mk_required($tel, $field_name);
        } elseif (!NHK_Validate::phone($tel)) {
            $errors[] = NHK_Validate::phone_msg;
        }
        return $errors;
    }

    static function is_mobile_email($email) {
        $result = false;
        if (Zend_Validate::is($email, "EmailAddress")) {
            $file = './library/mobile_email.txt';
            $txt = file($file);
            foreach ($txt as $line):
                if (strpos($email, trim($line))) {
                    return true;
                }
            endforeach;
        }
        return $result;
    }

    static function full_width_text($input) {
        /*
          mb_internal_encoding('EUC-JP');
          $sl = strlen($input);
          $jl = mb_strwidth($input);
          if ( ($sl!= $jl && !D_Utf8::is_katakana($input)) || preg_match("/^[0-9\-\s]*$/", $input) ) {
          return true;
          } */
        $zen = new D_Validate_Zenkaku();
        return $zen->isValid($input);
        //return false;
    }

    static function full_width_kana($input) {
        /* $input = trim($input);
          mb_internal_encoding('EUC-JP');
          $sl = strlen($input);
          $jl = mb_strwidth($input);
          if ($sl!= $jl || D_Utf8::is_katakana($input)) {
          return true;
          } */
        $obj = new D_Validate_ZenkakuKana();
        return $obj->isValid($input);
    }

    static function half_width_text($input) {
        /* mb_internal_encoding('EUC-JP');
          $sl = strlen($input);
          $jl = mb_strwidth($input);
          if ($sl == $jl) {
          return true;
          } */
        $obj = new D_Validate_Hankaku();
        return $obj->isValid($input);
    }

    static function check_2byte($input) {
        return true;
    }

    function is_2byte_text($input) {
        return true;
    }

    static function is_2byte_kana($input) {
        $va = new D_Validate_ZenkakuKana();
        return $va->isValid($input);
        return D_Utf8::is_katakana($input);
    }

    function is_1byte_text($input) {
        return true;
    }

    function is_nick_name($input) {
        return preg_match("/^[a-zA-Z0-9_]{0,20}$/", $input);
    }

    function check_number($input) {
        return preg_match("/^[0-9_.]+$/", $input);
    }

    function is_text_number($input) {
        return preg_match("/^[a-zA-Z0-9]+$/", $input);
    }

    /* ------------------------------------------
     * error type : required,.....
     * vars = array('value' => 'company_name','ident key' => 'property key' )
     *
     * ----------------------------------------- */

    function parse($message_template, $indents, $custom_message='') {

        if ($custom_message == '') {
            $message = $this->_messages->$message_template;
        } else {
            $message = $custom_message;
        }

        foreach ($indents as $indent => $value) {
            $value = self::parse_name_from_key($value);
            $message = str_replace("%$indent%", $value, $message);
        }

        return $message;
    }

    function get_property($key) {
        return $this->_properties->$key;
    }

    function parse_name_from_key($key) {
        $result = str_replace("-", " ", $key);
        $result = ucfirst($result);
        return $result;
    }

    function get_message($key) {
        return $this->_messages->$key;
    }

    static function message($key) {
        $_messages = new Zend_Config_Ini('./application/configs/validate.ini', 'messages');
        return $_messages->$key;
    }

}

?>