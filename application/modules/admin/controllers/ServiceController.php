<?php

class Admin_ServiceController extends NHK_AdminController {

  protected $_controller = 'bot';

  public function init() {
      $this->_model = new ChiTietLayHang();
      $logger = Zend_Registry::get("logger");    
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();     
        $logger->info("------------".$controller."---".$action);
  }

  public function indexAction() {
    $this->noView();
    if ($this->_request->isPost()) {
      $params = $this->_request->getParams();
      $data['macuahang'] = $params['macuahang'];
      $data['maphieunhap'] = $params['manhaphang'];
      $data['tongvanggiao'] = $params['tongvanggiao'];
      $data['trade'] = $params['trade'];
      $data['tienmat'] = $params['tienmat'];
      $data['congnocu'] = $params['congnocu'];
      $data['congnomoi'] = $params['congnomoi'];
      $data['ngay'] = $params['ngay'];
      $data['tongcong'] = $params['tongcong'];
      $data['tongvang18kgiao'] = $params['tongvang18kgiao'];
      $data['mato'] = $params['ma_to'];
      $data['tongtruhot'] = $params['tongtruhot'];
      $data['tonghot'] = $params['tonghot'];
      $data['tonghotkhac'] = $params['tonghotkhac'];
      $data['soluonggiao'] = $params['soluonggiao'];
      
      $this->_model->update(array('deleted_at'=>date('Y-m-d H:i:s')),"maphieunhap = {$params['manhaphang']} and macuahang={$params['macuahang']}");
      $this->_model->insert($data);
      
      $dataCuaHang['congno'] = $params['congnomoi'];
      $dataCuaHang['maphieunhap'] = $params['manhaphang'];
      $dataCuaHang['tongvanggiao'] = $params['tongvanggiao'];
      $dataCuaHang['trade'] = $params['trade'];
      $dataCuaHang['tienmat'] = $params['tienmat'];
      $dataCuaHang['congnocu'] = $params['congnocu'];
      $dataCuaHang['updated_at'] = date("Y-m-d H:i:s");
      
      //print_r($dataCuaHang);
      $modelCuaHang = new Store();
      $modelCuaHang->update($dataCuaHang,"ma='".$params['macuahang']."'");
      
      echo "Cập nhật thành công";
    }
    
  }

  

}
?>