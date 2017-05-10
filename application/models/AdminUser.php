<?php
class AdminUser extends NHKModel{
    protected $_name="admin_users";    

    static function is_login() {
        return NHK_Auth_Admin::getInstance()->hasIdentity();
    }

    static function logout() {
        return NHK_Auth_Admin::getInstance()->clearIdentity();
    }

   function getAll($filter) {
        $sql = $this->select()->setIntegrityCheck(false)
                        ->from(array('u' => $this->_name), array('*'))
                        ->where("deleted_at is null");
        //Search
        if(isset($filter["name"])&&$filter["name"] != ''){
            $sql->where("name like ?","%".$filter["name"]."%");
        }
        //Sort
        if(isset($filter["sort"])&&isset($filter["order"])){
            $sql->order( array("{$filter["sort"]} {$filter["order"]}") );
        }
        return $this->fetchAll($sql);
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

}
?>