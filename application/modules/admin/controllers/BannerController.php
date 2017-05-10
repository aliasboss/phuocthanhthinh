<?php

class Admin_BannerController extends NHK_AdminController {

    protected $_controller = 'banner';

    public function init() {
        $this->require_authenticated();
        $this->setLayout('index', 'admin');
        $this->_model = new Banner();

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

        $sort = json_decode(stripslashes($params['sort']), true);
        if (!$sort)
            $sort = array('sort_name' => 'created_at', 'sort_type' => 'DESC');





        $limit = array('start' => ($currentPage - 1) * $this->_itemPerPage, 'limit' => $this->_itemPerPage);

        $data = $this->_model->getAll($search, $sort, $limit)->toArray();

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
            $validate->required('name', $params['data']['name']);
            if ($validate->is_error()) {
                $reponse['status'] = 'error';
                $reponse['method'] = 'validate';
                $reponse['error'] = $validate->get_errors();
            } else {
                $data = $params['data'];
                $data['image_name'] = NHK_Utils::generateUrlSlug($data['name']);
                $data['date_expired'] = date('Y-m-d H:i:s', strtotime($data['date_expired']));

                if ($params['id'] == '') {
                    $data['date_active'] = date("Y-m-d H:i:s");
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
            }
        }
        echo json_encode($reponse);
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

            $this->_model->execute("truncate event");
            $reponse['status'] = 'success';
            $reponse['message'] = 'Delete all success';

            echo json_encode($reponse);
        }
    }

   function activeAction(){
    $this->noView();
    $reponse['status']="success";
    if ($this->_request->isPost()) {
      $params = $this->_request->getParams();
      $this->_model->activeBanner($params['id']);
    }else{
      $reponse['status']="error";
    }
    echo json_encode($reponse);
  }
  
  function activeallAction(){
    $this->noView();
    $reponse['status']="success";
    if ($this->_request->isPost()) {      
      $this->_model->activeAll();
    }else{
      $reponse['status']="error";
    }
    echo json_encode($reponse);
  }
}
?>