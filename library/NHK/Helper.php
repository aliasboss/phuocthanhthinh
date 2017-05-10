<?php
/*------------------------------------------
* @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
* @PHONE: +84933731173
* -----------------------------------------*/
class NHK_Helper {
	
	static function form_item_input($label, $name, $params = null, $type='text', $class="input-xlarge", $value='',$id='',$disabled=''){
		$vars = array(
			"control_class" => "", 
			"label" => $label,
			"type" => $type,
			"name" => $name, 
			"input_class" => $class,
			"placeholder" => $label,
                        "id" => $id,
                        "disabled" => $disabled,
			"value" => $value
		);
		if ($params != null && isset($params[$name]) && $params[$name] != ''){
			 $vars["value"] = $params[$name];
		}
                
		$result = NHK_Helper::parse("form_input", $vars);		                
                
		return $result;
	}
	
	static function form_item_input_append($label, $name, $params = null, $type='text', $input_class="input-xlarge", $append=''){
		$vars = array(
			"append"=> $append,
			"type" => $type,
			"name" => $name, 
			"place_holder" => $label,
			"input_class" => $input_class,
			"value" => "",
		);
		if ($params != null && isset($params[$name]) && $params[$name] != ''){
			 $vars["value"] = $params[$name];
		}
		$input_append_html = NHK_Helper::parse("input_append", $vars);
		
		$result = NHK_Helper::form_item($label, $input_append_html);
		return $result;
	}
	
	static function form_item_button($label, $name, $type='btn-primary', $size=""){
		$vars = array(
			"type" => "", 
			"label" => $label,
			"size" => $type,
			"name" => $name
		);
		
		$result = NHK_Helper::parse("form_button", $vars);
		
		return $result;
	}
	
	static function js_button($label, $name, $onclick = "button_handle",$type="btn-warning", $size=""){
		$vars = array(
			"label" => $label,
			"size" => $size,
			"name" => $name,
			"type" => $type,
			"onclick" => $onclick
		);
		
		$result = NHK_Helper::parse("js_button", $vars);
		
		return $result;
	}
	
	static function form_item_textarea($label, $name, $params = null, $rows = 5, $cols=15,$value=''){
		$vars = array(
			"rows" => $rows,
			"name" => $name,
			"value" => $value,
			"cols" => $cols
		);
		
		if ($params != null && isset($params[$name]) && $params[$name] != ''){
			 $vars["value"] = $params[$name];
		}
		
		$result_text_area = NHK_Helper::parse("form_textarea", $vars);
		
		$result = NHK_Helper::form_item($label, $result_text_area);
		
		return $result;
	}
	
	static function form_item_select($label, $name, $data = array(), $params = null, $class='' ){
		$selects = "<select name='". $name."' class='".$class."'>";
		$options = "";
		foreach ($data as $key => $val) {
			if($params!=null && isset($params[$name]) && $params[$name] == $key){
				$options .=  "<option value='".$key."' selected='selected' >".$val."</option>";
			}else{
				$options .=  "<option value='".$key."'>".$val."</option>";
			}
		}
		$selects .= $options;
		$selects .= "</select>";
		
		$result = NHK_Helper::form_item($label, $selects);
		return $result;
	}
	
	static function select($name, $data = array(), $params = null, $class='' ){
		$selects = "<select class='".$class."' name='". $name."' id='". $name."'>";
		$options = "";
		foreach ($data as $key => $val) {
			if($params!=null && isset($params[$name]) && $params[$name] == $key){
				$options .=  "<option value='".$key."' selected='selected' >".$val."</option>";
			}else{
				$options .=  "<option value='".$key."'>".$val."</option>";
			}
		}
		$selects .= $options;
		$selects .= "</select>";
		
		return $selects;
	}
        
        static function form_item_input_checkbox($label, $name,$value, $params = null,$checked=true){
		$vars = array(			
			"label" => $label,			
			"name" => $name,						
			"value" => $value,
                        "checked" => ""
		);
		if ($params != null && isset($params[$name])){
                    if($params[$name] == $value)
			 $vars["checked"] = 'checked';
                    else
                        $vars["checked"] = '';
		}else{
                    if($checked)
                        $vars["checked"] = 'checked';
                }
                
                
		$result = NHK_Helper::parse("input_checkbox", $vars);
                
		return $result;
                //return print_r($params);
	}

	static function input($name, $params, $place_holder, $type="text", $class=''){
		$vars = array(
			"type" => $type, 
			"name" => $name,
			"place_holder" => $place_holder,
			"class" => $class,
			"value" => "",
		);
		if ($params != null && isset($params[$name]) && $params[$name] != ''){
			 $vars["value"] = $params[$name];
		}
		
		$result = NHK_Helper::parse("input", $vars);
		
		return $result;
	}
	
	static function input_text_date_picker($name, $params, $place_holder){
		return NHK_Helper::input($name, $params, $place_holder, "text", "date_picker");
	}
	
	static function inputText($name,$class,$defaul_value,$more=''){
		$result = "<input name=\"{$name}\" id=\"{$name}\" type=\"text\" class=\"{$class}\" ";
		if ($defaul_value){ $result.=" value=\"{$defaul_value}\" ";}
		if ($more!=''){ $result.=" {$more} ";}
		$result .=" />";
		return $result;
	}

	static function inputText2($name,$id,$class,$defaul_value,$more=''){
		$result = "<input name=\"{$name}\" id=\"{$id}\" type=\"text\" class=\"{$class}\" ";
		if ($defaul_value){ $result.=" value=\"{$defaul_value}\" ";}
		if ($more!=''){ $result.=" {$more} ";}
		$result .=" />";
		return $result;
	}
	
	static function inputFile($name,$class,$defaul_value='',$more=''){
		$result = "<input name=\"{$name}\" id=\"{$name}\" type=\"file\" class=\"{$class}\" ";
		if ($defaul_value){ $result.=" value=\"{$defaul_value}\" ";}
		if ($more!=''){ $result.=" {$more} ";}
		$result .=" />";
		return $result;
	}
	
	static function inputPassword($name,$class,$defaul_value,$more=''){
		$result = "<input name=\"{$name}\" id=\"{$name}\" type=\"password\" class=\"{$class}\" ";
		if ($defaul_value){ $result.=" value=\"{$defaul_value}\" ";}
		if ($more!=''){ $result.=" {$more} ";}
		$result .=" />";
		return $result;
	}
	
	static function textArea($name,$class,$defaul_value,$title="",$style=""){
		$result = "<textarea name=\"{$name}\" id=\"{$name}\" class=\"{$class}\" >";
		if (isset($defaul_value)){ $result.="{$defaul_value}";}
		$result .="</textarea>";
		return $result;
	}
	
	static function inputCheckbox ($name,$class,$defaul_value,$id="",$title="",$style=""){
		$result = "<input name=\"{$name}\" type=\"checkbox\" class=\"{$class}\" ";
		if($id!=''){$result.=" id=\"{$id}\" ";}
                
               
		if ($defaul_value&&$defaul_value!=''){ $result.=" checked=\"\" ";}
		
		$result .=" />";
		return $result;
	}
	
	static function inputRadio($name,$class,$value='',$select_value='',$id="",$more=''){
		$result = "<input name=\"{$name}\" value=\"{$value}\" type=\"radio\" class=\"{$class}\" ";
		if($id!=''){$result.=" id=\"{$id}\" ";}
		if($value!=''&&$value==$select_value){$result.=" checked=\"checked\" ";}
		if ($more!=''){ $result.=" {$more} ";}
		$result .=" />";
		return $result;
	}
	
	
	
	static function form_item($label, $html){
		$vars = array(
			"label" => $label,
			"html" => $html, 
		);
		
		$result = NHK_Helper::parse("form_item", $vars);
		
		return $result;
	}
	
	
	
	static function parse($templates = '', $variables = array() ){
		$html = NHK_Properties::get($templates, "html_templates");                                
                
		foreach ($variables as $key=>$value) {                        
                        $html = str_replace("%{$key}%", $value, $html);
		}
		
		return $html;
	}
}

?>