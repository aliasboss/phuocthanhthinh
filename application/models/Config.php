<?php
class Config extends NHKModel{
    protected $_name="config";    

  
  
    
   
  function get($key) {
      
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), "*")                       
                        ->where("u.config_key = ?",$key);
                        ;
        
        
       
        return $this->fetchRow($sql)->toArray();
    }
    
   
   

}
?>