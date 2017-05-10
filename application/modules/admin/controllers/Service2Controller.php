<?php

class Admin_Service2Controller extends NHK_AdminController {

  protected $_controller = 'service2';

  public function init() {
     
      $logger = Zend_Registry::get("logger");    
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();     
        $logger->info("------------".$controller."---".$action);
  }

  public function indexAction() {
    $this->noView();
    
    //if ($this->_request->isPost()) {
      $params = $this->_request->getParams();      
      
//      $model = new Datacache();
//      $data = array();
//      if($params['key']=="giavangphuthinh"){
//          $data['data']= $params['data'];
//          $model->update($data, "id=1");
//      }else{
//          $data['data']= $params['data'];
//          $model->update($data, "id=2");
//      }
      $cache = new NHK_Cache(null);        
                               
        $cache->save($params['key'], $params['data']);
     
    //}
    
  }

  

}
?>