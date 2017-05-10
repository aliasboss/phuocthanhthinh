<?php 
class AboutController extends NHK_DefaultController{ 
    public function init(){        
        $this->setLayout('main', 'default');
        $this->view->menu = 'about';
        $this->_model = new News();
        $this->_itemPerPage = 15;
        $this->_pageRange = 4;
    }
    public function indexAction(){ 
      $this->view->headTitle("Liên Hệ");
      
      
      
    } 
    
   
    
    
    
}
?>