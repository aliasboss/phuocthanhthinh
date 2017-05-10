<?php
class LichSuGiaVang extends NHKModel{
    protected $_name="lich_su_gia_vang";    

  
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
        if(isset($search['ngay'])&&$search['ngay']!='')       
          $sql = $sql->where('DATE(u.created_at) = ?',date('Y-m-d',  strtotime ($search['ngay'])));
        
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
        if(isset($search['ten'])&&$search['ten']!='')
          $sql = $sql->where("u.ten like '%{$search['ten']}%' ");
      
        
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
    function checkExist(array $option){
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name),array('*'))                       
                        ->where("u.session = ?",$option['session'])
                        ->where("u.ip = ?", $option['ip'] )
                        ->where("u.time >= ?", $option['time'])
                        ;
             
        return $this->fetchRow($sql);
                        //return $sql;
    }
    
    function checkUserExist(array $option){
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name),array('*'))                       
                        ->where("u.username = ?",$option['username'])                        
                        
                        ;
        
        if($option['time']!= null && $option['time']!= ''){
            $sql = $sql->where("u.time >= ?", $option['time']);
        }
             
        return $this->fetchRow($sql);
                        //return $sql;
    }
    
    function deleteUser($username){
        $this->delete("username = {$username}");
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