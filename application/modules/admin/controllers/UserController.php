<?php

class Admin_UserController extends NHK_AdminController {

    protected $_controller = 'user';

    public function init() {
        $this->require_authenticated();
        $this->setLayout('index', 'admin');
        $this->_model = new User();

        $registry = Zend_Registry::getInstance();
        $config = $registry->get('config');
        $this->_itemPerPage = $config->itemPerPage;
        $this->_pageRange = $config->pageRange;

        $this->_section = $this->_controller;
        $this->view->section = $this->_section;

        $this->_link = $this->_controller;
        $this->view->link = $this->_link;


        $this->_properties = $registry->get('properties');
        $this->view->properties = $this->_properties;

        $this->_total = $this->_model->countAll();
        $this->view->total = $this->_total;

        $this->view->menu = $this->_controller;

        $logger = Zend_Registry::get("logger");
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
        $logger->info("------------" . $controller . "---" . $action);
    }

    public function indexAction() {
        
    }

    public function listAction() {

        $params = $this->_request->getParams();

        $currentPage = $params['page'];
        if (isset($params['search'])) {
            $search = json_decode(stripslashes($params['search']), true);
            unset($search['noitem']);
        } else {
            $search = array();
        }
        if (isset($params['sort']))
            $sort = json_decode(stripslashes($params['sort']), true);
        else
            $sort = array();





        $limit = array('start' => ($currentPage - 1) * $this->_itemPerPage, 'limit' => $this->_itemPerPage);

//$data = $this->_model->getAll($search, $sort, $limit)->toArray();
        $data = $this->_model->getAll($search, $sort, null)->toArray();


        $this->_total = $this->_model->countAll($search);
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

        $this->noLayout();
    }

    public function addAction() {
        $this->noView();

        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();

            $validate = new NHK_Validate();
            $validate->required('username', $params['data']['username']);
            if ($validate->is_error()) {
                $reponse['status'] = 'error';
                $reponse['method'] = 'validate';
                $reponse['error'] = $validate->get_errors();
            } else {

                switch ($this->_model->checkExist($params['id'], $params['data']['username'])) {
                    case 'delete':
                        $reponse['status'] = 'error';
                        $reponse['method'] = 'checkExist';
                        $reponse['error'] = 'Username đã tồn tại và đã bị xóa. Vui lòng kiểm tra lại';
                        break;
                    case 'exist':
                        $reponse['status'] = 'error';
                        $reponse['method'] = 'checkExist';
                        $reponse['error'] = 'Username đã tồn tại. Vui lòng kiểm tra lại';
                        break;
                    default :
                        $data = $params['data'];
                        if ($data['time_expired'] == '') {
                            $data['time_expired'] = date("Y-m-d", time() + (86400 * 30));
                        } else {
                            $data['time_expired'] = date("Y-m-d", strtotime($data['time_expired']));
                        }
                        
                        if ($data['date_active'] == '') {
                            $data['date_active'] = date("Y-m-d H:i:s");
                        } else {
                            $data['date_active'] = date("Y-m-d", strtotime($data['date_active']));
                        }



                        if (isset($data['status'])) {
                            $data['status'] = 1;
                        } else {
                            $data['status'] = 0;
                        }
                        if (isset($data['user_type'])) {
                            $data['user_type'] = 2;
                        }

                        $data['password'] = md5($data['password_show']);
                        if ($params['id'] == '') {
//                            print_r($data);
//                            exit();
                            $id = $this->_model->insert($data);

                            $this->_total = $this->_model->countAll();
                            $reponse['status'] = 'success';
                            $reponse['method'] = 'add';
                            $reponse['id'] = $id;
                            $reponse['page'] = ceil($this->_total / $this->_itemPerPage);
                        } else {
                            $this->_model->update($data, "id={$params['id']}");
                            $this->_total = $this->_model->countAll();
                            $reponse['status'] = 'success';
                            $reponse['method'] = 'update';
                        }
                        break;
                }
            }
            echo json_encode($reponse);
        }
    }

    function deleteAction() {
        $this->noView();
        $params = $this->_request->getParams();
        $this->_model->update(array('deleted_at' => date('Y-m-d H:i:s')), "id = {$params['key']}");
    }

    function getinfoAction() {
        $this->noView();
        $params = $this->_request->getParams();

        $data = $this->_model->get($params['key'])->toArray();

        echo json_encode($data);
    }

    function deleteselectAction() {
        $this->noView();
        if ($this->_request->isPost()) {
            $keys = json_decode(stripslashes($this->_request->getPost('keys')), true);

            if (isset($keys) && $keys != null) {
                foreach ($keys as $k => $v) {
                    $this->_model->update(array('deleted_at' => date("Y-m-d H:i:s")), "id={$v}");
                }
                $reponse['status'] = 'success';
                $reponse['message'] = 'Delete success';
            } else {
                $reponse['status'] = 'error';
                $reponse['message'] = 'Do not value';
            }
            echo json_encode($reponse);
        }
    }

    function deleteallAction() {
        $this->noView();
        if ($this->_request->isPost()) {

            $reponse['status'] = 'success';
            $reponse['message'] = 'Delete all success';

            echo json_encode($reponse);
        }
    }

    function activeAction() {
        $this->noView();
        $reponse['status'] = "success";
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $this->_model->activeUser($params['id']);
        } else {
            $reponse['status'] = "error";
        }
        echo json_encode($reponse);
    }

    function activeallAction() {
        $this->noView();
        $reponse['status'] = "success";
        if ($this->_request->isPost()) {
            $this->_model->activeAll();
        } else {
            $reponse['status'] = "error";
        }
        echo json_encode($reponse);
    }

    function blockAction() {
        $this->noView();
        $reponse['status'] = "success";
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $this->_model->blockUser($params['id']);
        } else {
            $reponse['status'] = "error";
        }
        echo json_encode($reponse);
    }

    public function capnhatgiaAction() {
        $this->view->menu = "capnhatgia";
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $modelGiaVang = new GiaVang();
            $data = array();
            $data['bongphanmua'] = $params["bongphanmua"];
            $data['bongphanban'] = $params["bongphanban"];

            $data['bongdepmua'] = $params["bongdepmua"];
            $data['bongdepban'] = $params["bongdepban"];

            $data['ngoaitemua'] = $params["ngoaitemua"];
            $data['ngoaiteban'] = $params["ngoaiteban"];


            $modelGiaVang->insert($data);

            $cache = new NHK_Cache(400);
            $cache->clean();
            $dataRead = $modelGiaVang->getNow()->toArray();


            print_r($modelGiaVang->getNow()->toArray());
        }
    }

    function thaydoiwebsitelaygiaAction() {
        $modelConfig = new Config();
        $this->view->menu = 'laygia';
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
//link_get_gia
            $config = $modelConfig->get("link_get_gia");
            $data = array();
            $data['config_val'] = $params['web'];

            $sql = "update config set config_val = {$params['web']} where id={$config['id']}";
            $modelConfig->execute($sql);
            $this->view->info = "Cập Nhật Thành Công";
        }

        $this->view->config = $modelConfig->get("link_get_gia");
    }

    public function doimatkhauAction() {
        $modelAdminUser = new AdminUser();
        $params = $this->_request->getParams();
        $info = $modelAdminUser->get($params['id'])->toArray();

        $this->view->info = $info;
    }

    public function capnhatmatkhauAction() {
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $modelAdminUser = new AdminUser();
            $data['password'] = md5($params['matkhaumoi']);
            $modelAdminUser->update($data, "id={$params['id']}");
            $this->_redirect(NHK_URL::getRouteAdmin('index'));
        }
    }
    
    public function checkOnlineAction(){
        $modelOnline = new Online();
        $params = $this->_request->getParams();
        $data = $modelOnline->getAll($params['username']);
        $this->view->data = $data;
    }
    public function deleteOnlineAction(){
        $modelOnline = new Online();
        $params = $this->_request->getParams();
        $data = $modelOnline->delete(array('id'=>$params['id']));
        $this->_redirect(NHK_URL::getRouteAdmin('user').'/check-online/username/'.$params['username']);
    }

}
?>