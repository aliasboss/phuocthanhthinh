<?php

class Admin_IndexController extends NHK_AdminController {

    public function init() {
        $this->setLayout('index', 'admin');
    }

    public function indexAction() {

        if(AdminUser::is_login()){
            $this->view->headTitle("Admin Index--nhkhanh");
             echo NHK_Properties::get('text');                  
             
        }else{              
            $this->_redirect(NHK_URL::getRouteAdmin('index').'/login/');
            //echo "Hệ thống đang bảo trì";
        }

        
        
    }

    function loginAction() {
        //$this->setLayout('simple');
        //$this->noLayout();
        $modelAdminUser = new AdminUser();
        $users = $modelAdminUser->getAll(null)->toArray();
        $data_users = array();
        foreach ($users as $user){
            $data_users[$user['username']] = $user['fullname'];
        }
        $this->view->users = $data_users;
        if (NHK_Auth_Admin::getInstance()->hasIdentity()) {
            $this->_redirect(NHK_URL::getRouteAdmin('index'));
        }
        //$this->noRender();
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            
            $validate = new NHK_Validate();
            $validate->required('username', $params['username']);
            $validate->required('password', $params['password']);

            if ($validate->is_error()) {
                $this->setView('index/login.phtml');
                $this->view->errors = $validate->get_errors();
            } else {
               $result = $this->login($params['username'], $params['password']);            
              if (!$result) {                  
                  $this->setView('index/login.phtml');
                  $this->view->result = NHK_Properties::get("login_failed");
                  //print_r()
              } else {
                  $this->_redirect(NHK_URL::getRouteAdmin('index'));
              }
            }                                               
            $this->view->params = $params;
        }
    }

    function logoutAction(){
        //$this->view->page_title = "Logout";
        if (AdminUser::is_login()) {
                AdminUser::logout();
                //unset all session                
        }
        $this->_redirect ( NHK_URL::getRouteAdmin('index') );
    }



    private function login($name, $password) {
        try {
            $authAdapter = $this->getAuthAdapter();

           
            $password = md5($password);
            $authAdapter->setIdentity($name)->setCredential($password);

            
            
            //use auth of D_Auth_Userco
            $auth = NHK_Auth_Admin::getInstance ();
            $objSessionNamespace = new Zend_Session_Namespace( 'admin' );
            $objSessionNamespace->setExpirationSeconds(3600);

            $result = $auth->authenticate($authAdapter);
            
          
           
            if (!$result->isValid()) {
                //login k thanh cong
                return false;
            }

            $userInfo = $authAdapter->getResultRowObject(null, 'password');

            
            $authStorage = $auth->getStorage();
            $authStorage->write($userInfo);
            return true;
        } catch (Zend_Exception $e) {            
            return false;
        }
    }

    private function getAuthAdapter() {
        $db = Zend_Db_Table::getDefaultAdapter ();
        $authAdapter = new Zend_Auth_Adapter_DbTable($db, array("admin_users"), 'username', 'password');
        $select = $authAdapter->getDbSelect();
        $select->where('deleted_at is null'); //
        
       
        return $authAdapter;
    }



}
?>