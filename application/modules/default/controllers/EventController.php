<?php 
class EventController extends NHK_DefaultController{ 
    public function init(){
        //$this->require_authenticated();
        $this->setLayout('main', 'default');
        $this->view->menu = 'event';
        $this->_model = new Event();
        $this->_itemPerPage = 15;
        $this->_pageRange = 4;
    }
    public function indexAction(){ 
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
       
      $this->view->headTitle("Tin tức | Sự Kiện");
      $params = $this->_request->getParams();
      
      //$this->view->data = $this->_model->getAll()->toArray();

      if(isset($params['page'])&&$params['page']>1){
        $currentPage = $params['page'];
      }else{
        $currentPage = 1;
      }
      $limit = array('start' => ($currentPage - 1) * $this->_itemPerPage, 'limit' => $this->_itemPerPage);

      $sort = array('sort_name'=>'created_at','sort_type'=>'DESC');
      $cols = array('id','title','thumb_img','short_des','time_post','created_at');
      $data = $this->_model->getAll(null, $sort, $limit,$cols)->toArray();


      $this->_total = $this->_model->countAll();
      $paginator = Zend_Paginator::factory((int) $this->_total);

      //S? user trên m?t trang
      $paginator->setItemCountPerPage($this->_itemPerPage);

      //S? trang du?c hi?n ra d? click
      $paginator->setPageRange($this->_pageRange);

      //L?y trang hi?n t?i

      $paginator->setCurrentPageNumber($currentPage);

      $this->view->itemPerPage = $this->_itemPerPage;
      $this->view->current_page = $currentPage;

  //    print_r($paginator);
  //    exit();
      //Truy?n d? li?u ra view
      $this->view->paginator = $paginator;
      $this->view->data = $data;

      
      
    } 
    
    public function detailAction(){       
      $params = $this->_request->getParams();
      if(isset($params['id'])){
          $this->view->data = $this->_model->get($params['id']);
          $this->view->headTitle("{$this->view->data->title} | Cà Mau");

      }else{
        $this->redirect(NHK_URL::getLinkControllerDefault('event'));
      }
    }
    
    
    
}
?>