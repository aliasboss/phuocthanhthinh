<?php
class Counter extends NHKModel{
    protected $_name="counter";    

  
   function getAll($id) {
        
          $cols = array('*');
        
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), $cols)
                        ->where("u.deleted_at is null");
        //Search
        $sql = $sql->where("u.idtho = {$id} ");
      
        //Sort
        $sql->order( array("u.created_at DESC") );
        
        //Limit
        if(isset($paginator)&&$paginator!=null){          
          $sql=$sql->limit(10,0);
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
        if(isset($search['created_at'])&&$search['created_at']!='')
          $sql = $sql->where("DATE(u.created_at) = '{$search['created_at']}' ");
        if(isset($search['month'])&&$search['month']!='')
            $sql = $sql->where("MONTH(u.created_at) = '{$search['month']}' ");
        
        return $this->fetchRow($sql)->total;
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
    
   function getNow() {
       
          $cols = array('*');
        
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), $cols)                       
                        ->order( array("u.created_at DESC") )
                        ->limit(1,0);
                        ;
        
        
       
        return $this->fetchRow($sql);
    }
   

}
?>