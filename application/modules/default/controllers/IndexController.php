<?php

class IndexController extends NHK_DefaultController {

    public function init() {

        $this->setLayout('main_new', 'default');
        $this->view->headTitle("Thông Tin Giá Vàng - Ngoại Tệ");
        $this->view->menu = 'home';
    }

    public function indexAction() {
        $this->insertStatic();

        $modelConfig = new Config();
        $config = $modelConfig->get("link_get_gia");
        
        
        
        if (User::is_login()) {
            switch ($config['config_val']) {
                case 0:
                    break;
                case 1:
                    $this->setView('index/index_taiem.phtml');
                    break;
                case 2:
                    $this->setView('index/index_phuthinh.phtml');
                    break;
            }
        }
    }

    public function insertStatic() {
        $counter = new Counter();
        $data = array();
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $counter->insert($data);
    }

    public function checkOnlineAction() {
        //$this->noLayout();
        $this->noView();
        $online = new Online();
        $objSessionNamespace = new Zend_Session_Namespace('online');

        if (isset($objSessionNamespace->rand)) {
            $this->updateClientOnline($online, $objSessionNamespace);
        } else {
            $this->saveClientOnline($online, $objSessionNamespace);
        }

        $time = time() - 900;
        $online->deleteUserOut($time);

        $data = array();
        $modelCounter = new Counter();
        $data['statistics'] = $modelCounter->countAll();
        $data['today'] = $modelCounter->countAll(array('created_at' => date("Y-m-d")));
        $data['month'] = $modelCounter->countAll(array('month' => date("m")));

        $resultOnline = $online->countOnline();
        $data['online'] = $resultOnline['all'];
        $data['useronline'] = $resultOnline['useronline'];

        echo json_encode($data);
    }

    public function checkOnlineExist(Online $online, Zend_Session_Namespace $session) {
        $data = array();
        $data['time'] = time() - 900;
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['session'] = $session->rand;
        return $online->checkExist($data);
    }

    public function updateClientOnline(Online $online, Zend_Session_Namespace $session, $username = null) {
        $result = $this->checkOnlineExist($online, $session);
        if ($result != null) {
            if ($username != null && User::is_login()) {
                $online->update(array('username' => NHK_Auth::getUser()->username), 'id = ' . $result->id);
            } else {
                $online->update(array('time' => time()), 'id = ' . $result->id);
            }
        } else {
            $this->saveClientOnline($online, $session);
        }
    }

    public function saveClientOnline(Online $online, Zend_Session_Namespace $session) {
        $session->rand = $this->generateRandomString(10);
        $data = array();
        if (User::is_login()) {
            $data['username'] = NHK_Auth::getUser()->username;
        }
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['session'] = $session->rand;
        $data['time'] = time();
        $online->insert($data);
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function xml_attribute($object, $attribute) {
        if (isset($object[$attribute]))
            return (string) $object[$attribute];
    }

    public function getGiaVangAction() {

        //$this->require_authenticated();

        require './phpQuery.php';

        $this->noRender();

        $this->noLayout();

        if ($this->_request->isPost()) {

            $cache = new NHK_Cache(250);

            $key = "giavang";

            if ($data = $cache->load($key)):
                $content = $data;
            else:
                $this->curl_login('http://taiem.com.vn/site/loginV.html', 'username=hoangkhanh&password=123456', '', 'off');
                $content = $this->curl_grab_page('http://taiem.com.vn/site/gold.html?t=' . time(), '', 'off');

                phpQuery::newDocumentHTML($content);

                $content = pq('table:eq(2)');
                $content .= pq('table:eq(5)');

                $content = str_replace("updateGBtn();", "", $content);

                $cache->save($key, $content);

            endif;



            // $this->curl_login('http://taiem.com.vn/site/loginV.html', 'username=hoangkhanh&password=123456', '', 'off');
            //$content = $this->curl_grab_page('http://taiem.com.vn/site/gold.html?t=' . time(), '', 'off');



            header('Content-Type: text/html; charset=utf-8');

            echo $content;
        }
    }

    public function getNgoaiTeAction() {
        //$this->require_authenticated();
        require './phpQuery.php';
        $this->noRender();
        $this->noLayout();



        $cache = new NHK_Cache(250);
        $key = "ngoaite";
        if ($data = $cache->load($key)):
            $content = $data;
        else:

            $this->curl_login('http://taiem.com.vn/site/login.html', 'username=hoangkhanh&password=123456', '', 'off');
            $content = $this->curl_grab_page('http://taiem.com.vn/site/currency.html?t=' . time(), '', 'off');

            phpQuery::newDocumentHTML($content);

            $content = pq('table:eq(0)');
            //$content .= "</br>";
            $content .= pq('table:eq(2)');
            $content = str_replace("updateCBtn();", "", $content);
            $cache->save($key, $content);
        endif;

        echo $content;
    }

    public function displaygoldAction() {
        //$this->require_authenticated();
        $this->noRender();
        $this->noLayout();
        if ($this->_request->isPost()) {
            $cache = new NHK_Cache(null);
            $key = "giavangphuthinh";
            if ($data = $cache->load($key)):
                $content = $data;
            else:
                $content = null;
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
            $cache = new NHK_Cache(null);
            $key = "giangoaitephuthinh";
            if ($data = $cache->load($key)):
                $content = $data;
            else:
                $content = null;
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

    public function getinfoAction() {
        $this->noRender();
        $this->noLayout();
        $cache = new NHK_Cache(400);
        $data = $cache->load("infogiavang");
        print_r($data);
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

    public function loginAction() {
        //$this->setLayout('simple');
        // $this->noLayout();
        //$this->view->headTitle("Đăng nhập | Mai Quyên");

        if (NHK_Auth_User::getInstance()->hasIdentity()) {
            $this->_redirect(NHK_URL::getRoute('index'));
        }
        //$this->noRender();
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();



            $validate = new NHK_Validate();
            $validate->required('username', $params['username']);
            $validate->required('password', $params['password']);

            if ($validate->is_error()) {
                //$this->setView('index/index.phtml');
                $this->view->errors = $validate->get_errors();
            } else {

                $model_user = new User();

                $online = new Online();
                $objSessionNamespace = new Zend_Session_Namespace('online');




                $flag = $model_user->login($params['username'], md5($params['password']));

                switch ($flag) {
                    case 1:
                        $this->view->result = NHK_Properties::get('user_locked');
                        break;
                    case 2:
                        $this->view->result = NHK_Properties::get('user_time_expired');
                        break;

                    default :
                        $data = array();
                        $data['time'] = time() - 900;
                        $data['username'] = $params['username'];
                        $result = $online->checkUserExist($data);
                        $arrUserUnlimited = array('phuocthanh', 'kimhoa');
                        $userManager = array_search($data['username'], $arrUserUnlimited);
                        //kiểm tra user đã đăng nhập chưa?
                        if ($result->total > 1 && $userManager == false) {
                            $this->setView('index/index.phtml');
                            $this->view->result = "Tài khoản này hiện đã đăng nhập ở 2 thiệt bị. Vui lòng kiểm tra lại.";
                        } else {
                            $result = $this->login($params['username'], $params['password']);
                            if (!$result) {
                                //$this->setView('index/index.phtml');
                                $this->view->result = NHK_Properties::get("login_failed");
                            } else {

                                //lưu lại thông tin user đăng nhâp vào table Online để check user ONline
                                $this->updateClientOnline($online, $objSessionNamespace, $params['username']);
                                $this->_redirect(NHK_URL::getRoute('index'));
                            }
                        }



                        break;
                }
            }
            $this->setView('index/index.phtml');




            $this->view->params = $params;
        } else {
            $this->_redirect(NHK_URL::getRoute('index'));
        }
    }

    function logoutAction() {
        //$this->view->page_title = "Logout";
        if (User::is_login()) {
            $objSessionNamespace = new Zend_Session_Namespace('online');

            $online = new Online();
            $username = NHK_Auth::getUser()->username;
            $online->delete("session='{$objSessionNamespace->rand}'");

            unset($objSessionNamespace->rand);

            User::logout();

            //unset all session                
        }
        $this->_redirect(NHK_URL::getRoute('index'));
    }

    private function login($name, $password) {
        try {
            $authAdapter = $this->getAuthAdapter();
            $password = md5($password);
            $authAdapter->setIdentity($name)->setCredential($password);



            //use auth of D_Auth_Userco
            $auth = NHK_Auth_User::getInstance();




            $result = $auth->authenticate($authAdapter);



            if (!$result->isValid()) {
                //login k thanh cong
                return false;
            }

            $arrUserUnlimited = array('khanh', 'admin', 'mq');
            if (array_search($name, $arrUserUnlimited) === false) {
                $objSessionNamespace = new Zend_Session_Namespace('user');
                $objSessionNamespace->setExpirationSeconds(3600);
            }



            $userInfo = $authAdapter->getResultRowObject(null, 'password');


            $authStorage = $auth->getStorage();
            $authStorage->write($userInfo);

            return true;
        } catch (Zend_Exception $e) {
            return false;
        }
    }

    private function getAuthAdapter() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($db, array("users"), 'username', 'password');
        $select = $authAdapter->getDbSelect();
        $select->where('deleted_at is null'); //


        return $authAdapter;
    }

    function infoAction() {

        $this->require_authenticated();
        $this->setLayout('main', 'default');
        $params = $this->_request->getParams();
        if ($params['id'] != '') {
            $model_user = new User();
            $this->view->data = $model_user->get($params['id']);
        } else {
            $this->view->data = null;
        }
    }

    function changepassAction() {
        $this->require_authenticated();

        $this->noView();
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $model = new User();
            $user = $model->get($params['id']);
            if ($user->password != md5($params['password'])) {
                $reponse['status'] = 'error';
                $reponse['error'] = 'Mật khẩu cũ không đúng.';
            } else if ($params['passnew'] != $params['passnewconfirm']) {
                $reponse['status'] = 'error';
                $reponse['error'] = 'Mật khẩu nhập lại không đúng.';
            } else {
                $model->update(array('password' => md5($params['passnew']), 'password_show' => $params['passnew']), "id = {$params['id']}");
                $reponse['status'] = 'success';
            }
            echo json_encode($reponse);
        } else {
            $this->redirect('/');
        }
    }

    public function testAction() {
        $this->noView();
       
        $data = $this->getgiasjccamau();
        print_r($data);
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
//             $data['v999']['m'] = number_format($data['v999']['m'] , 0, '', '.');
//            $data['v999']['b'] = number_format($data['v999']['b'] , 0, '', '.');
//            $data['v980']['m'] = number_format($data['v980']['m'] , 0, '', '.');
//            $data['v980']['b'] = number_format($data['v980']['b'] , 0, '', '.');
//            $data['v960']['m'] = number_format($data['v960']['m'] , 0, '', '.');
//            $data['v960']['b'] = number_format($data['v960']['b'] , 0, '', '.');
//            $data['v750']['m'] = number_format($data['v750']['m'] , 0, '', '.');
//            $data['v750']['b'] = number_format($data['v750']['b'] , 0, '', '.');
//            $data['v680']['m'] = number_format($data['v680']['m'] , 0, '', '.');
//            $data['v680']['b'] = number_format($data['v680']['b'] , 0, '', '.');
//            $data['v610']['m'] = number_format($data['v610']['m'] , 0, '', '.');
//            $data['v610']['b'] = number_format($data['v610']['b'] , 0, '', '.');
//            $data['v585']['m'] = number_format($data['v585']['m'] , 0, '', '.');
//            $data['v585']['b'] = number_format($data['v585']['b'] , 0, '', '.');
        } else {
            $data = null;
        }
        
        $sjcCaMau = $this->getgiasjccamau();
        $data['sjc']['m'] = number_format((int)str_replace(".", "", $sjcCaMau['buy']) *100 , 0, '', '.') ;
        $data['sjc']['b'] = number_format((int)str_replace(".", "", $sjcCaMau['sell']) *100 , 0, '', '.') ;


        if ($this->_request->isPost()) {
            $this->noRender();
            echo json_encode($data);
        } else {

            $this->view->data = $data;
        }
    }

    public function getNotificationAction() {
        $this->noLayout();
        $this->noRender();


        $model = new News();
        $data = $model->getNotification();
        $result = array();
        if ($data) {
            $result['show'] = true;
            $result['content'] = $data->description;
            $result['title'] = $data->title;
        } else {
            $result['show'] = false;
            $result['content'] = '';
        }
//        $result['show']= true;
//            $result['content'] = "àdfsdfsfsdfsd";
        echo json_encode($result);
    }

    public function giathegioiAction() {

        $this->noLayout();
        if ($this->_request->isPost()) {
            $this->noRender();
            $homepage = file_get_contents('http://pricing.fxdevpro.com/api/Quotes/GetQuote?name=GOLD');
            $data = json_decode($homepage);
            echo $homepage;
        }
    }

    public function getgiasjcAction() {

        $this->noLayout();
        $data = array();
        $url = "http://www.sjc.com.vn/xml/tygiavang.xml?t=" . time();
        $xml = simplexml_load_file($url);
        //$xml = simplexml_load_string('<root><title>Cong ty TNHH MTV Vang bac da quy Sai gon : Bang Ty gia vang SJC</title><url>http://www.sjc.com.vn/</url><ratelist updated="08:58:32 AM 22/05/2013" unit="ngàn đồng/lượng"><city name="Hồ Chí Minh"><item buy="40.720" sell="40.820" type="Vàng SJC 1L"/><item buy="38.020" sell="38.520" type="Vàng nhẫn SJC 99,99 5p,1c,2c,5c"/><item buy="36.820" sell="38.520" type="Vàng nữ trang 24 K"/><item buy="26.843" sell="29.043" type="Vàng nữ trang 18 K"/><item buy="20.409" sell="22.609" type="Vàng nữ trang 14 K"/><item buy="14.014" sell="16.214" type="Vàng nữ trang 10 K"/></city><city name="Hà Nội"><item buy="40.720" sell="40.840" type="Vàng SJC"/></city><city name="Đà  Nẵng"><item buy="40.720" sell="40.840" type="Vàng SJC"/></city><city name="Nha Trang"><item buy="40.710" sell="40.840" type="Vàng SJC"/></city><city name="Cần Thơ"><item buy="40.720" sell="40.820" type="Vàng SJC"/></city><city name="Cà Mau"><item buy="40.720" sell="40.840" type="Vàng SJC"/></city><city name="Kiên Giang"><item buy="40.720" sell="40.840" type="Vàng SJC"/></city><city name="Buôn Ma Thuột"><item buy="40.710" sell="40.840" type="Vàng SJC"/></city></ratelist></root>');
        // $ratelist = new SimpleXMLElement();
        $ratelist = $xml->ratelist;
        //$ratelist_attributes = $ratelist->attributes();
        foreach ($ratelist[0]->attributes() as $k => $v) {
            $data[$k] = (string) $v[0];
        }

        $citys = $xml->ratelist->city;
        $j = 0;
        for ($i = 0; $i < count($citys); $i++) {

            $data['city'][$j]['name'] = $this->xml_attribute($citys[$i]->attributes(), 'name');
            $arr_item = array();
            foreach ($citys[$i]->item as $item) {
                $arr_item[] = array(
                    'type' => $this->xml_attribute($item->attributes(), 'type'),
                    'buy' => $this->xml_attribute($item->attributes(), 'buy'),
                    'sell' => $this->xml_attribute($item->attributes(), 'sell')
                );
            }
            $data['city'][$j]['item'] = $arr_item;
            $j++;
        }


        //print_r($data);
        //exit();

        $this->view->data = $data;
        $this->setView('index/giavangsjc.phtml');


        //echo str_replace('/images/', $url_img.'/', $content);
    }

    public function getgiasjccamau() {

        $this->noLayout();
        $data = array();
        $url = "http://www.sjc.com.vn/xml/tygiavang.xml?t=" . time();
        $xml = simplexml_load_file($url);
        //$xml = simplexml_load_string('<root><title>Cong ty TNHH MTV Vang bac da quy Sai gon : Bang Ty gia vang SJC</title><url>http://www.sjc.com.vn/</url><ratelist updated="08:58:32 AM 22/05/2013" unit="ngàn đồng/lượng"><city name="Hồ Chí Minh"><item buy="40.720" sell="40.820" type="Vàng SJC 1L"/><item buy="38.020" sell="38.520" type="Vàng nhẫn SJC 99,99 5p,1c,2c,5c"/><item buy="36.820" sell="38.520" type="Vàng nữ trang 24 K"/><item buy="26.843" sell="29.043" type="Vàng nữ trang 18 K"/><item buy="20.409" sell="22.609" type="Vàng nữ trang 14 K"/><item buy="14.014" sell="16.214" type="Vàng nữ trang 10 K"/></city><city name="Hà Nội"><item buy="40.720" sell="40.840" type="Vàng SJC"/></city><city name="Đà  Nẵng"><item buy="40.720" sell="40.840" type="Vàng SJC"/></city><city name="Nha Trang"><item buy="40.710" sell="40.840" type="Vàng SJC"/></city><city name="Cần Thơ"><item buy="40.720" sell="40.820" type="Vàng SJC"/></city><city name="Cà Mau"><item buy="40.720" sell="40.840" type="Vàng SJC"/></city><city name="Kiên Giang"><item buy="40.720" sell="40.840" type="Vàng SJC"/></city><city name="Buôn Ma Thuột"><item buy="40.710" sell="40.840" type="Vàng SJC"/></city></ratelist></root>');
        // $ratelist = new SimpleXMLElement();
        $ratelist = $xml->ratelist;
        //$ratelist_attributes = $ratelist->attributes();
        foreach ($ratelist[0]->attributes() as $k => $v) {
            $data[$k] = (string) $v[0];
        }

        $citys = $xml->ratelist->city;
        $j = 0;
        $arr_item = array();
        for ($i = 0; $i < count($citys); $i++) {

            $name = $this->xml_attribute($citys[$i]->attributes(), 'name');
            if ($name != "Cà Mau")
                continue;
            else {
                $arr_item['name'] = $name;
                foreach ($citys[$i]->item as $item) {

                    $arr_item['buy'] = $this->xml_attribute($item->attributes(), 'buy');
                    $arr_item['sell'] = $this->xml_attribute($item->attributes(), 'sell');
                }
                break;
            }
        }
        return $arr_item;
    }

}
?>