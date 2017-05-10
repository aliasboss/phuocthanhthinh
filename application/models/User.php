<?php

class User extends NHKModel {

    protected $_name = "users";

    static function is_login() {
        return NHK_Auth_User::getInstance()->hasIdentity();
    }

    static function logout() {
        return NHK_Auth_User::getInstance()->clearIdentity();
    }

    function getAll($search = null, $sort = null, $paginator = null, $columns = null) {
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                $cols[$i++] = 'u.' . $item;
            }
        } else {
            $cols = array('*');
        }
        $sql = $this->select()->setIntegrityCheck(false)
                ->from(array('u' => $this->_name), $cols)
                ->where("u.deleted_at is null");
        //Search
        if (isset($search['username']) && $search['username'] != '')
            $sql = $sql->where("u.username like '%{$search['username']}%' ");
        if (isset($search['fullname']) && $search['fullname'] != '')
            $sql = $sql->where("u.fullname like '%{$search['fullname']}%' ");
        if (isset($search['phone']) && $search['phone'] != '')
            $sql = $sql->where("u.phone like '%{$search['phone']}%' ");

        //Sort
       // if (isset($sort) && $sort != null) {
        //    if (isset($sort['sort_name']) && isset($sort['sort_type'])) {
         //       $sql->order(array("u.{$sort['sort_name']} {$sort['sort_type']}"));
        //    }
       // }
            $sql->order(array("u.time_expired DESC"));

        //Limit
        if (isset($paginator) && $paginator != null) {
            $sql = $sql->limit($paginator['limit'], $paginator['start']);
        }

//        echo $sql;
//        exit();
        return $this->fetchAll($sql);
    }

    function countAll($search = null) {
        $sql = $this->select()->setIntegrityCheck(false)
                ->from(array('u' => $this->_name), array('count(u.id) as total'))
                ->where("u.deleted_at is null");
        //Search
        if (isset($search['username']) && $search['username'] != '')
            $sql = $sql->where("u.username like '%{$search['username']}%' ");
        if (isset($search['fullname']) && $search['fullname'] != '')
            $sql = $sql->where("u.fullname like '%{$search['fullname']}%' ");
        if (isset($search['phone']) && $search['phone'] != '')
            $sql = $sql->where("u.phone like '%{$search['phone']}%' ");

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

    function get($id, $columns = null) {
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                $cols[$i++] = 'u.' . $item;
            }
        } else {
            $cols = array('*');
        }
        $sql = $this->select()->setIntegrityCheck(false)
                ->from(array('u' => $this->_name), $cols)
                ->where("u.id = ?", $id);
        ;



        return $this->fetchRow($sql);
    }

    function login($user, $password) {
        $sql = $this->select()->setIntegrityCheck(false)
                ->from(array('u' => $this->_name), array('*'))
                ->where("u.username = ?", $user)
                ->where("u.password = ?", $password)

        ;



        $result = $this->fetchRow($sql);

//        echo time()."---".strtotime($result->time_expired)."====";
//        echo time()-strtotime($result->time_expired);
//        echo $result->time_expired;
//        $dateExpired = new DateTime($result->time_expired);
//        echo "--fff--".$dateExpired->;
        //exit();
        if ($result) {
            $this->update(array("login_date" => date("Y-m-d H:i:s")), "id={$result->id}");
            if ($result->status == 1)
                return 1; // user bị khóa
            else if (time() - strtotime($result->time_expired) > 0)
                return 2;
            else
                return 3;
        }else {
            return 0;
        }
    }

    function blockUser($id) {
        $this->update(array('status' => 1), "id = {$id}");
    }

//    function activeUser($id) {
//        $time_expired = date("Y-m-d", time() + (86400 * 30));
//        $time_active = date("Y-m-d");
//        $this->update(array('status' => 0, 'date_active' => $time_active, 'time_expired' => $time_expired), "id = {$id}");
//    }

    function activeUser($id) {
        $time_active = date("Y-m-d");
        $this->update(array('status' => 0, 'date_active' => $time_active), "id = {$id}");
    }
    function activeAll() {
        $time_expired = date("Y-m-d", time() + (86400 * 30));
        $time_active = date("Y-m-d");
        $this->update(array('status' => 0, 'date_active' => $time_active, 'time_expired' => $time_expired), "username <> 'admin' and username <> 'mq'");
    }

    function getUserExpired($search = null, $sort = null, $paginator = null, $columns = null) {
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                $cols[$i++] = 'u.' . $item;
            }
        } else {
            $cols = array('*');
        }
        $sql = $this->select()->setIntegrityCheck(false)
                ->from(array('u' => $this->_name), $cols)
                ->where("u.time_expired >= ?", date("Y-m-d H:i:s"))
                ->where("u.deleted_at is null");



        //Sort
        if (isset($sort) && $sort != null) {
            if (isset($sort['sort_name']) && isset($sort['sort_type'])) {
                $sql->order(array("u.{$sort['sort_name']} {$sort['sort_type']}"));
            }
        }

        //Limit
        if (isset($paginator) && $paginator != null) {
            $sql = $sql->limit($paginator['limit'], $paginator['start']);
        }

//        echo $sql;
//        exit();
        return $this->fetchAll($sql);
    }
    
    function countUserActive($month) {
        $sql = $this->select()->setIntegrityCheck(false)
                ->from(array('u' => $this->_name), array('count(u.id) as total'))
                ->where("MONTH(u.date_active) = {$month}")
                ->where("u.deleted_at is null");
       

        return $this->fetchRow($sql)->total;
    }

}

?>