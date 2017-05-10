<?php
class Online extends NHKModel{
    protected $_name="online";    

  
   function getAll($username) {
        
          $cols = array('*');
        
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), $cols);
        //Search
        $sql = $sql->where("u.username = '{$username}' ");
      
        
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
                        ->from(array('u' => $this->_name),array('count(u.id) as total'))                       
                        ->where("u.username = ?",$option['username'])                        
                        
                        ;
        
        if($option['time']!= null && $option['time']!= ''){
            $sql = $sql->where("u.time >= ?", $option['time']);
        }
             
        return $this->fetchRow($sql);
                        //return $sql;
    }
    
    function countOnline(){
        $result = array();
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), array('count(u.id) as total'));
        
        $result['all'] = $this->fetchRow($sql)->total;
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), array('count(u.id) as total'))
                        ->where('u.username <> ""');
      
        
        $result['useronline'] = $this->fetchRow($sql)->total;
        
        return $result;
    }
    
    function deleteUser($username){
        $this->delete("username = {$username}");
    }
    
    function deleteUserOut($time){
        $this->delete("time < {$time}");
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