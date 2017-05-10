<?php
class News extends NHKModel{
    protected $_name="news";    

    
    
   function getAll($search=null,$sort=null,$paginator=null,$columns=null) {
        if($columns){
          $i=0;
          foreach($columns as $item){
            $cols[$i++] = 'u.'.$item;
          } 
        }else{
          $cols = array('*');
        }
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), $cols)
                        ->where("u.deleted_at is null");
        //Search
        if(isset($search['title'])&&$search['title']!='')
          $sql = $sql->where("u.title like '%{$search['title']}%' ");
        if(isset($search['created_at'])&&$search['created_at'] !='')
          $sql = $sql->where('DATE(u.created_at) = ?',date('Y-m-d',  strtotime ($search['created_at'])));
        
        //Sort
        if(isset($sort)&&$sort!=null){
            if(isset($sort['sort_name'])&& isset($sort['sort_type'])){
                $sql->order( array("u.{$sort['sort_name']} {$sort['sort_type']}") );
            }            
        }
        
        //Limit
        if(isset($paginator)&&$paginator!=null){          
          $sql=$sql->limit($paginator['limit'],$paginator['start']);
        }
        
//        echo $sql;
//        exit();
        return $this->fetchAll($sql);
    }
    
    
    
    function countAll($search=null) {
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), array('count(u.id) as total'))
                        ->where("u.deleted_at is null");
        //Search
        if(isset($search['title'])&&$search['title']!='')
          $sql = $sql->where("u.title like '%{$search['title']}%' ");
        if(isset($search['created_at'])&&$search['created_at'] !='')
          $sql = $sql->where('DATE(u.created_at) = ?',date('Y-m-d',  strtotime ($search['created_at'])));
        
        
        return $this->fetchRow($sql)->total;
    }
    
    function checkExist($id, $name) {
    if (isset($id) && !empty($id)) {
      $exist = $this->fetchRow("username = '{$name}' and id != '{$id}'");
    } else {
      $exist = $this->fetchRow("username = '{$name}'");
    }

    if ($exist == null)
      return 'ok';
    else {
      if ($exist['deleted_at'] != '') {
        return 'delete';
      } else {
        return 'exist';
      }
    }
  }
  function get($id,$columns=null) {
        if($columns){
          $i=0;
          foreach($columns as $item){
            $cols[$i++] = 'u.'.$item;
          } 
        }else{
          $cols = array('*');
        }
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), $cols)                       
                        ->where("u.id = ?",$id);
                        ;
        
        
       
        return $this->fetchRow($sql);
    }
    
    function getNotification(){
        
          $cols = array('*');
        
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), $cols)                       
                        ->where("u.id = 1")
                        ->where("deleted_at >= ?",date("Y-m-d")." 00:00:00")
                        ;
        
        
       //print_r($sql);
      // exit();
        return $this->fetchRow($sql);
    }

}
?>