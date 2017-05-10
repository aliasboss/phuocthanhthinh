<?php

class TempController extends NHK_DefaultController {

  public function init() {

    $this->setLayout('main_temp', 'default');
    $this->view->menu = 'home';    
  }

  public function indexAction() {
    
      $this->view->headTitle(NHK_Properties::get('cty'));
      $modelConfig = new Config();
      $config = $modelConfig->get("link_get_gia");
      if($config['config_val']==1){
          $this->setView('index/index_taiem.phtml');
      }
    
  }

  function xml_attribute($object, $attribute) {
    if (isset($object[$attribute]))
      return (string) $object[$attribute];
  }

  
  
  public function getgiangoaiteAction() {
        $this->require_authenticated();
        require './phpQuery.php';
        $this->noRender();
        $this->noLayout();

        

        $cache = new NHK_Cache(250);
        $key = "ngoaite";
        if ($data = $cache->load($key)):
            $content = $data;
        else:
    
        $this->curl_login('http://taiem.com.vn/site/login.html', 'username=mquyen&password=156518', '', 'off');
            $content = $this->curl_grab_page('http://taiem.com.vn/site/currency.html?t='.time(), '', 'off');
            
            phpQuery::newDocumentHTML($content);                    
            
           $content = pq('table:eq(0)');
           //$content .= "</br>";
           $content.= pq('table:eq(2)');
           $content = str_replace("updateCBtn();", "", $content);
            $cache->save($key, $content);
        endif;
       
        echo $content;
    }
    
    public function getGiaVangPhuThinh(){
         require './phpQuery.php';    
            
            $url = 'http://ngoaite.phuthinh.com.vn/Account/Login';
            
            $loginUrl     = 'http://ngoaite.phuthinh.com.vn/Account/Login';

            //on windows server you need full path or its not even used.
            $cookie  = 'cookies_giavang.txt';
            $fp = fopen($cookie, "w");
            fclose($fp);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $loginUrl );
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE );
            curl_setopt($ch, CURLOPT_COOKIEJAR , $cookie);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_HEADER, FALSE );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40);
            $ret = curl_exec($ch); //access login page
            //return $ret;
            //check the contents of the cookie file. the sessionID, visitorID and kalaharishooperID should be the same with each refresh.        

            //now get the viewstate from the value
            //could be better with regex or anything, but this works.
            $content = explode('value="', stristr($ret, 'id="__VIEWSTATE"'));
            $viewstate = substr($content[1], 0, strpos($content[1], '"'));
            //var_dump($viewstate);


            //now get the EVENTVALIDATION from the value
            //could be better with regex or anything, but this works.
            $content = explode('value="', stristr($ret, 'id="__EVENTVALIDATION"'));
            $validation = substr($content[1], 0, strpos($content[1], '"'));
            //var_dump($validation);

            //define post fields
                //i removed all fields that are not required for the post
            $postfields = array(
                "__VIEWSTATE" => rawurlencode($viewstate),
                "__EVENTVALIDATION" => rawurlencode($validation),
                "ctl00%24MainContent%24UserName" => "maiquyen",
                "ctl00%24MainContent%24Password" => "123456",
                "ctl00%24MainContent%24ctl04" => "Đăng+nhập",
                "ctl00%24txtNewsletter" => "",
            );
            //var_dump($postfields);

                //I created the string myself for the post, else I got an error because we already encoded the variable names.
            $p = "";
            foreach($postfields as $k=>$v) {
                $p .= $k.'='.$v.'&';
            }


            //do the new post
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
            $ret = curl_exec($ch);//Get result after login page.
                
            curl_setopt($ch, CURLOPT_URL, "http://ngoaite.phuthinh.com.vn" );
            $ret = curl_exec($ch);
            
            $content = explode('value="', stristr($ret, 'id="__VIEWSTATE"'));
            $viewstate = substr($content[1], 0, strpos($content[1], '"'));
            //var_dump($viewstate);

             $content = explode('value="', stristr($ret, 'id="__VIEWSTATEGENERATOR"'));
            $viewstategenerator = substr($content[1], 0, strpos($content[1], '"'));
            
            $content = explode('value="', stristr($ret, 'id="__VIEWSTATEGENERATOR"'));
            $viewstategenerator = substr($content[1], 0, strpos($content[1], '"'));
            
            $content = explode('value="', stristr($ret, 'id="MainContent_goldRateGrid_State"'));
            $goldRateGrid = @substr($content[1], 0, strpos($content[1], '"'));
            
            $content = explode('value="', stristr($ret, 'id="MainContent_fxRateGrid_State"'));
            $fxRateGrid = @substr($content[1], 0, strpos($content[1], '"'));
            
            $content = explode('value="', stristr($ret, 'id="__EVENTVALIDATION"'));
            $eventvalidation = substr($content[1], 0, strpos($content[1], '"'));
                     
            
            //DXScript
            $dxscript = "1_230,1_134,1_223,1_168,1_153,1_193,1_195,1_166,1_174";
            //DXCss
            $dxcss = "1_22,1_29,1_33,1_21,1_3,1_17,1_18,1_16,/Content/bootstrap.css,/Content/Site.css,favicon.ico,Content/bootstrap.min.css,https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css";
            //__CALLBACKPARAM
            $callbackparam = "c0:KV|2;[];GB|9;7|REFRESH;";
            
            $postfields = array(
                "__EVENTTARGET" => "",
                "__EVENTARGUMENT"=>"",
                "__VIEWSTATE" => rawurlencode($viewstate),
                "__VIEWSTATEGENERATOR" => $viewstategenerator,
                "ctl00%24MainContent%24goldRateGrid" => $goldRateGrid,
                "ctl00%24MainContent%24fxRateGrid" => $fxRateGrid,
                "ctl00%24txtNewsletter" => "",
                "DXScript" => $dxscript,
                "DXCss" => $dxcss,
                "__CALLBACKID" => "ctl00%24MainContent%24goldRateGrid",
                "__CALLBACKPARAM" => $callbackparam,
                
                "__EVENTVALIDATION" => rawurlencode($eventvalidation),
                
            );
            //var_dump($postfields);

                //I created the string myself for the post, else I got an error because we already encoded the variable names.
            $p = "";
            foreach($postfields as $k=>$v) {
                $p .= $k.'='.$v.'&';
            }
            
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
            $ret = curl_exec($ch);//Get result after login page.
            
             phpQuery::newDocumentHTML($ret);                    

            $content = pq('#MainContent_goldRateGrid_DXMainTable');                   
            return $content;
    }
    
    public function getGiaNgoaiTePhuThinh(){
        require './phpQuery.php'; 
        $url = 'http://ngoaite.phuthinh.com.vn/Account/Login';
            


            $loginUrl     = 'http://ngoaite.phuthinh.com.vn/Account/Login';

            //on windows server you need full path or its not even used.
            $cookie  = 'cookies_ngoaite.txt';
            $fp = fopen($cookie, "w");
            fclose($fp);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $loginUrl );
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE );
            curl_setopt($ch, CURLOPT_COOKIEJAR , $cookie);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_HEADER, FALSE );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40);
            $ret = curl_exec($ch); //access login page

            //check the contents of the cookie file. the sessionID, visitorID and kalaharishooperID should be the same with each refresh.        

            //now get the viewstate from the value
            //could be better with regex or anything, but this works.
            $content = explode('value="', stristr($ret, 'id="__VIEWSTATE"'));
            $viewstate = substr($content[1], 0, strpos($content[1], '"'));
            //var_dump($viewstate);


            //now get the EVENTVALIDATION from the value
            //could be better with regex or anything, but this works.
            $content = explode('value="', stristr($ret, 'id="__EVENTVALIDATION"'));
            $validation = substr($content[1], 0, strpos($content[1], '"'));
            //var_dump($validation);

            //define post fields
                //i removed all fields that are not required for the post
            $postfields = array(
                "__VIEWSTATE" => rawurlencode($viewstate),
                "__EVENTVALIDATION" => rawurlencode($validation),
                "ctl00%24MainContent%24UserName" => "maiquyen",
                "ctl00%24MainContent%24Password" => "123456",
                "ctl00%24MainContent%24ctl04" => "Đăng+nhập",
                "ctl00%24txtNewsletter" => "",
            );
            //var_dump($postfields);

                //I created the string myself for the post, else I got an error because we already encoded the variable names.
            $p = "";
            foreach($postfields as $k=>$v) {
                $p .= $k.'='.$v.'&';
            }


            //do the new post
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
            $ret = curl_exec($ch);//Get result after login page.

            curl_setopt($ch, CURLOPT_URL, "http://ngoaite.phuthinh.com.vn" );
            $ret = curl_exec($ch);
            
            $content = explode('value="', stristr($ret, 'id="__VIEWSTATE"'));
            $viewstate = substr($content[1], 0, strpos($content[1], '"'));
            //var_dump($viewstate);

             $content = explode('value="', stristr($ret, 'id="__VIEWSTATEGENERATOR"'));
            $viewstategenerator = substr($content[1], 0, strpos($content[1], '"'));
            
            $content = explode('value="', stristr($ret, 'id="__VIEWSTATEGENERATOR"'));
            $viewstategenerator = substr($content[1], 0, strpos($content[1], '"'));
            
            $content = explode('value="', stristr($ret, 'id="MainContent_goldRateGrid_State"'));
            $goldRateGrid = @substr($content[1], 0, strpos($content[1], '"'));
            
            $content = explode('value="', stristr($ret, 'id="MainContent_fxRateGrid_State"'));
            $fxRateGrid = @substr($content[1], 0, strpos($content[1], '"'));
            
            $content = explode('value="', stristr($ret, 'id="__EVENTVALIDATION"'));
            $eventvalidation = substr($content[1], 0, strpos($content[1], '"'));
                     
            
            //DXScript
            $dxscript = "1_230,1_134,1_223,1_168,1_153,1_193,1_195,1_166,1_174";
            //DXCss
            $dxcss = "1_22,1_29,1_33,1_21,1_3,1_17,1_18,1_16,/Content/bootstrap.css,/Content/Site.css,favicon.ico,Content/bootstrap.min.css,https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css";
            //__CALLBACKPARAM
            $callbackparam = "c0:KV|2;[];GB|9;7|REFRESH;";
            
            $postfields = array(
                "__EVENTTARGET" => "",
                "__EVENTARGUMENT"=>"",
                "__VIEWSTATE" => rawurlencode($viewstate),
                "__VIEWSTATEGENERATOR" => $viewstategenerator,
                "ctl00%24MainContent%24goldRateGrid" => $goldRateGrid,
                "ctl00%24MainContent%24fxRateGrid" => $fxRateGrid,
                "ctl00%24txtNewsletter" => "",
                "DXScript" => $dxscript,
                "DXCss" => $dxcss,
                "__CALLBACKID" => "ctl00%24MainContent%24fxRateGrid",
                "__CALLBACKPARAM" => $callbackparam,
                
                "__EVENTVALIDATION" => rawurlencode($eventvalidation),
                
            );
            //var_dump($postfields);

                //I created the string myself for the post, else I got an error because we already encoded the variable names.
            $p = "";
            foreach($postfields as $k=>$v) {
                $p .= $k.'='.$v.'&';
            }
            
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
            $ret = curl_exec($ch);//Get result after login page.
            
  
            
             phpQuery::newDocumentHTML($ret);                    

            $content = pq('#MainContent_fxRateGrid_DXMainTable');       
            
            return $content;
    }
    
    public function savecachegiavang($data,$key){
        $strParams ="";
        $strParams  .=  "key=".$key."&";
        $strParams  .=  "data=".$data;
       
        $ch = curl_init();

       //curl_setopt($ch, CURLOPT_URL,"http://localhost/sonthuyngantien/admin/service2");
        curl_setopt($ch, CURLOPT_URL,"http://sonthuyngantien.com/admin/service2");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //curl_setopt($ch, CURLOPT_NOBODY, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    $strParams);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        if ($server_output === false) {
            return "nok";
        }       
        curl_close ($ch);
        
        return  $data;
    }

    public function displaygoldAction() {
        //$this->require_authenticated();
        $this->noRender();
        $this->noLayout();
        if ($this->_request->isPost()) {
            $cache = new NHK_Cache(290);
            $key = "giavangphuthinh";
            if ($data = $cache->load($key)):
                $content = $data;
            else:
                $content = $this->getGiaVangPhuThinh();
            
               $content = str_replace(array("\\r","\\n","\\t"), "", $content);                              
                $cache->save($key, $content);
            endif;
            //--khắc phục sự cố
            
            //$this->savecachegiavang($content,'giavangphuthinh');
        
            echo $content;
        }
    }

    public function displaycurrenceAction() {
        //$this->require_authenticated();
        $this->noRender();
        $this->noLayout();

        if ($this->_request->isPost()) {
            $cache = new NHK_Cache(590);
            $key = "giangoaitephuthinh";
            if ($data = $cache->load($key)):
                $content = $data;
            else:
                $content = $this->getGiaNgoaiTePhuThinh();
                $content = str_replace(array("\\r","\\n","\\t"), "", $content);
                $cache->save($key, $content);
            endif;
            //--khắc phục sự cố
            //$this->savecachegiavang($content,'giangoaitephuthinh');
            //--
            echo $content;
        }
        //echo $giavang;
    }
    
    public function getcachegiavangAction() {
    
        $this->noRender();
        $this->noLayout();
        if ($this->_request->isPost()) {
            $cache = new NHK_Cache();
            $key = "giavangphuthinh";
            $content = '';
            if ($data = $cache->load($key))
                $content = $data;
           
         
            echo $content;
            //echo $content;
        }
    }

    public function getcachengoaiteAction() {
        
        $this->noRender();
        $this->noLayout();

        if ($this->_request->isPost()) {
            $cache = new NHK_Cache();
            $key = "giangoaitephuthinh";
            $content = '';
            if ($data = $cache->load($key))
                $content = $data;
            
            echo $content;
        }
        //echo $giavang;
    }

   
  

  function curl_login($url, $data, $proxy, $proxystatus) {
    $fp = fopen("cookie.txt", "w");
    fclose($fp);
    $login = curl_init();
    curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($login, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt($login, CURLOPT_TIMEOUT, 40);
    curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    if ($proxystatus == 'on') {
      curl_setopt($login, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($login, CURLOPT_HTTPPROXYTUNNEL, TRUE);
      curl_setopt($login, CURLOPT_PROXY, $proxy);
    }
    curl_setopt($login, CURLOPT_URL, $url);
    curl_setopt($login, CURLOPT_HEADER, TRUE);
    curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    //curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($login, CURLOPT_POST, TRUE);
    curl_setopt($login, CURLOPT_POSTFIELDS, $data);
    ob_start();      // prevent any output
    return curl_exec($login); // execute the curl command
    ob_end_clean();  // stop preventing output
    curl_close($login);
    unset($login);
  }

  function curl_grab_page($site, $proxy, $proxystatus) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    if ($proxystatus == 'on') {
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
      curl_setopt($ch, CURLOPT_PROXY, $proxy);
    }
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt($ch, CURLOPT_URL, $site);
    ob_start();      // prevent any output
    return curl_exec($ch); // execute the curl command
    ob_end_clean();  // stop preventing output
    curl_close($ch);
    unset($ch);
  }
  
    public function banggiavangcamauAction() {
        $this->noLayout();
        $data = array();
        $cache = new NHK_Cache(null);
        $key = "data_giavang_camau";


        if ($data = $cache->load($key)) {
            $data['v999']['m'] = number_format($data['v999']['m'] * 1000, 0, '', '.');
            $data['v999']['b'] = number_format($data['v999']['b'] * 1000, 0, '', '.');
            $data['v980']['m'] = number_format($data['v980']['m'] * 1000, 0, '', '.');
            $data['v980']['b'] = number_format($data['v980']['b'] * 1000, 0, '', '.');
            $data['v960']['m'] = number_format($data['v960']['m'] * 1000, 0, '', '.');
            $data['v960']['b'] = number_format($data['v960']['b'] * 1000, 0, '', '.');
            $data['v750']['m'] = number_format($data['v750']['m'] * 1000, 0, '', '.');
            $data['v750']['b'] = number_format($data['v750']['b'] * 1000, 0, '', '.');
            $data['v680']['m'] = number_format($data['v680']['m'] * 1000, 0, '', '.');
            $data['v680']['b'] = number_format($data['v680']['b'] * 1000, 0, '', '.');
            $data['v610']['m'] = number_format($data['v610']['m'] * 1000, 0, '', '.');
            $data['v610']['b'] = number_format($data['v610']['b'] * 1000, 0, '', '.');
            $data['v585']['m'] = number_format($data['v585']['m'] * 1000, 0, '', '.');
            $data['v585']['b'] = number_format($data['v585']['b'] * 1000, 0, '', '.');
        } else {
            $data = null;
        }


        if ($this->_request->isPost()) {
            $this->noRender();
            echo json_encode($data);
        } else {

            $this->view->data = $data;
        }
    }

    public function checkbanggiacamauAction() {
        
    }

    public function capnhatgiavangcamauAction() {
        $this->noLayout();
        $data = array();
        $cache = new NHK_Cache(null);
        $key = "data_giavang_camau";
        $data = $cache->load($key);
//      $data['v999']['m']=3580;
//      $data['v999']['b']=3660;
//      $data['v980']['m']=3520;
//      $data['v980']['b']=3600;
//      $data['v960']['m']=3440;
//      $data['v960']['b']=3580;
//      $data['v750']['m']=2700;
//      $data['v750']['b']=2900;
//      $data['v680']['m']=2440;
//      $data['v680']['b']=2640;
//      $data['v610']['m']=2190;
//      $data['v610']['b']=2430;
//      $data['v585']['m']=2100;
//      $data['v585']['b']=2340;
//      $cache->save($key, $data);
        // print_r($data);
        //exit();


        $this->view->data = $data;
    }

    public function capnhat1Action() {
        $this->noLayout();
        $this->noView();

        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $cache = new NHK_Cache(null);
            $key = "data_giavang_camau";
            $gia_ban_v999 = trim($params['gia_ban_v999']);
            if (is_numeric($gia_ban_v999)) {
                if($data = $cache->load($key)){
                    $valueChange = $gia_ban_v999 - $data['v999']['b'];
                    $data['v999']['m'] = $data['v999']['m'] + $valueChange;
                    $data['v999']['b'] = $gia_ban_v999;
                    $data['v980']['m'] = $data['v980']['m'] + $valueChange;
                    $data['v980']['b'] = $data['v980']['b'] + $valueChange;
                    $data['v960']['m'] = $data['v960']['m'] + $valueChange;
                    $data['v960']['b'] = $data['v960']['b'] + $valueChange;
                    $giaChange = round($gia_ban_v999 * 0.075 + 15) * 10;
                    $valueChange = $giaChange - $data['v750']['b'];
                    $data['v750']['m'] = $data['v750']['m'] + $valueChange;
                    $data['v750']['b'] = $giaChange;
                    $giaChange = round($gia_ban_v999 * 0.068 + 15) * 10;
                    $valueChange = $giaChange - $data['v680']['b'];
                    $data['v680']['m'] = $data['v680']['m'] + $valueChange;
                    $data['v680']['b'] = $giaChange;
                    $giaChange = round($gia_ban_v999 * 0.061 + 20) * 10;
                    $valueChange = $giaChange - $data['v610']['b'];
                    $data['v610']['m'] = $data['v610']['m'] + $valueChange;
                    $data['v610']['b'] = $giaChange;
                    $giaChange = round($gia_ban_v999 * 0.0585 + 20) * 10;
                    $valueChange = $giaChange - $data['v585']['b'];
                    $data['v585']['m'] = $data['v585']['m'] + $valueChange;
                    $data['v585']['b'] = $giaChange;

                    $cache->save($key, $data);
                    $this->redirect('/temp/banggiavangcamau');
                }else{
                    echo "Đã có lỗi trong quá trình cập nhật vui lòng thử lại";
                    echo "<a href='../temp/capnhatgiavangcamau'>Trở về trang cập nhật giá</a>";
                }
                
            } else {
                echo "Giá trị bạn vừa nhập không phải là số. Vui lòng kiểm tra lại.";
                echo "<a href='../temp/capnhatgiavangcamau'>Trở về trang cập nhật giá</a>";
            }
        }
        //print_r($data);
        //$this->redirect('/temp/capnhatgiavangcamau');
    }

    public function capnhat2Action() {
        $this->noLayout();
        $this->noView();
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $data = $params['data'];
            //print_r($data);
            $cache = new NHK_Cache(null);
            $key = "data_giavang_camau";
            $cache->save($key, $data);
        }
        $this->redirect('/temp/banggiavangcamau');
    }

}
?>