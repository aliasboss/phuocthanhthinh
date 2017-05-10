<?php

class Admin_CapnhatgiaController extends NHK_DefaultController {

    protected $_model = null;
    protected $_controller = 'capnhatgia';

    public function init() {



        $this->setLayout('index', 'admin');
        $this->_model = new LichSuGiaVang();
        $this->view->menu = 'capnhatgia';
    }

    public function indexAction() {


//        $items = $this->_model->getAll();
//        foreach($items as $item){
//            print_r(json_decode($item->giavang));
//        }
//        
//        exit();
        $data = array();
        $cache = new NHK_Cache(null);
        $key = "data_giavang_camau";
        $data = $cache->load($key);
        $this->view->data = $data;
        //$this->noLayout();
    }

    public function camauAction() {
        $this->noLayout();
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
                if ($data = $cache->load($key)) {
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
                    // $this->redirect('/phuocthanhthinh/banggiavangcamau');
                } else {
                    echo "Đã có lỗi trong quá trình cập nhật vui lòng thử lại";
                    echo "<a href='../phuocthanhthinh/capnhatgiavangcamau'>Trở về trang cập nhật giá</a>";
                }
            } else {
                echo "Giá trị bạn vừa nhập không phải là số. Vui lòng kiểm tra lại.";
                echo "<a href='../phuocthanhthinh/capnhatgiavangcamau'>Trở về trang cập nhật giá</a>";
            }
        }
        //print_r($data);
        //$this->redirect('/phuocthanhthinh/capnhatgiavangcamau');
    }

    public function capnhat2Action() {

        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $data = $params['data'];
            //print_r($data);
            $cache = new NHK_Cache(null);
            $key = "data_giavang_camau";
            $cache->save($key, $data);

            $sjcCaMau = $this->getgiasjccamau();
            $data['sjc']['m'] = (int) str_replace(".", "", $sjcCaMau['buy']) / 10;
            $data['sjc']['b'] = (int) str_replace(".", "", $sjcCaMau['sell']) / 10;

            $this->_model->insert(array('giavang' => json_encode($data)));
            //json_encode($data)

            $this->view->data = $data;
        }
        // $this->redirect('/index');
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

    function xml_attribute($object, $attribute) {
        if (isset($object[$attribute]))
            return (string) $object[$attribute];
    }

    public function taskAction() {
        $this->noView();
        require 'library/sendmail/smtp.php';

        $model = new LichSuGiaVang();

        $item = $model->getNow();
        $data = (array) json_decode($item->giavang, true);
        $sjc = $this->getgiasjccamau();
        $data['sjc']['m'] = (int) str_replace(".", "", $sjc['buy']) / 10;
        $data['sjc']['b'] = (int) str_replace(".", "", $sjc['sell']) / 10;
        $model->insert(array('giavang' => json_encode($data)));
        

        $v980m = number_format($data['v980']['m'] * 1000, 0, '', '.');
        $v980b = number_format($data['v980']['b'] * 1000, 0, '', '.');
        $vsjcm = number_format($data['sjc']['m'] * 1000, 0, '', '.');
        $vsjcb = number_format($data['sjc']['b'] * 1000, 0, '', '.');
        $time = date("d-m-Y");
        $frommail   = "hoikimhoancamau@gmail.com";
        $tomail     = "anhtuanctv@yahoo.com.vn";
        //$tomail     = "nhkhanh294@gmail.com";
        $subject    = "Hội Kim Hoàn Cà Mau - Gửi Thông Báo Giá Vàng - ".date("H:i:s d-m-Y");
        
        $fromname   = "Hội Kim Hoàn Cà Mau";
        $content    = "<table style='font-size: 18px;' width='900'>
          <tr>
              <td align='center'>
                  HỘI MỸ NGHỆ  KIM HOÀN <br/>
                  Tỉnh Cà Mau<br/>
                  *****<br/>
                  - Điện thoại      :   3.811911<br/>
                  - Fax             :   3.811911<br/>
                  - Di động         :  0918.355.155<br/>
              </td>
              <td align='center'>
                  CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM <br/>
                  Độc Lập – Tự  Do – Hạnh Phúc <br/>
                  *******<br/>
              </td>
          </tr>
          <tr>
              <td style='font-weight: bold' align='center' colspan='2'>
                  <br/>
                  <h2>THÔNG BÁO GIÁ VÀNG</h2>
Tại Thành Phố Cà Mau lúc 9h00 giờ ngày  {$time} <br/>
******<br/>

1-	Vàng SJC<br/><br/>

Mua vào	:	{$vsjcm}đ/ Chỉ.<br/>
Bán ra 	:	{$vsjcb}đ/ Chỉ.<br/><br/><br/>

2-	Vàng thị trường 98%<br/><br/>

Mua vào	:	{$v980m}đ/ Chỉ.<br/>
Bán ra 	:	{$v980b}đ/ Chỉ.<br/>

              </td>
          </tr>
          <tr>
              <td align='center'>
                  <b><i><u>Kính chuyển đến Anh Phong.</u></i></b><br/>
                  Phòng chương trình - Đài truyền hình<br/>
                  Tỉnh Cà Mau.<br/>
              </td>
              <td align='center'>
                  <br/><br/><br/><br/>
                  HỘI MỸ NGHỆ  KIM HOÀN<br/>
                  UV BAN CHẤP HÀNH<br/>
                  <br/>
 <img src='https://lh5.googleusercontent.com/L09R8_YqyB4Zoqj1bIHaGsJmCAfBrcKbvZGwx0MFnWS9ujkOp9xwSv52CIs_L6tBqfRbHnmtwA-of8o=w1920-h918' />                  
<br/><br/>
                  
                  <strong>NGUYỄN PHƯỚC KHÁNH</strong>
              </td>
          </tr>
      </table>";
        SendMail($frommail, $tomail, $subject, $content,$fromname);
        
        
    }

}
?>