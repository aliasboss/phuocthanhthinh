<?php

/* ------------------------------------------
 * @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
 * @PHONE: +84933731173
 * ----------------------------------------- */

class NHK_Auth {

    static function user() {
        if (NHK_Auth_Userco::getInstance()->hasIdentity()) {
            $user_id = NHK_Utils::getUserId();
            // NHK_Auth_Userco::getInstance()->getIdentity());
            $userco = new Userco();
            return $userco->get($user_id);
            //return NHK_Auth_Userco::getInstance()->getIdentity();
        }
        return false;
    }

    static function getUsercoId() {
        if (NHK_Auth_Userco::getInstance()->hasIdentity()) {
            return NHK_Auth_Userco::getInstance()->getIdentity()->UserCoID;
        }
    }

    static function servicer() {
        if (NHK_Auth_Servicer::getInstance()->hasIdentity()) {
            $id = NHK_Auth::getServicerId();
            $servicer = new Servicer();
            return $servicer->get($id);
        }
        return false;
    }

    static function haslogin() {
        if (NHK_Auth_Userco::getInstance()->hasIdentity()) {
            return true;
        } else {
            return false;
        }
    }

    static function hasAdminLogin() {
        if (NHK_Auth_Admin::getInstance()->hasIdentity()) {
            return true;
        } else {
            return false;
        }
    }
	
	static function hasSuperAdminLogin() {
        if (NHK_Auth_Admin::getInstance()->hasIdentity()) {
            if(NHK_Auth::getAdmin() && NHK_Auth::getAdmin()->role == Admin::$SUPER_ADMIN){
            	return true;
            }else{
            	return false;
            }
        } else {
            return false;
        }
    }
    
    
 	
    static function getAdmin(){
        if(NHK_Auth_Admin::getInstance()->hasIdentity()){
                return NHK_Auth_Admin::getInstance()->getIdentity();
        }
        return false;
    }

    static function getServicerId() {
        if (NHK_Auth_Servicer::getInstance()->hasIdentity()) {
            return NHK_Auth_Servicer::getInstance()->getIdentity()->ServicerID;
        }
    }

    static function storage() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity();
        }
    }

    static function needChangePass() {
        if (NHK_Auth::haslogin()) {
            $global_sesson = new Zend_Session_Namespace('global');
            if ($global_sesson->tempPwd) {
                return true;
            }
        }

        return false;
    }

    static function getAdminId() {
        if (NHK_Auth_Admin::getInstance()->hasIdentity()) {
            return NHK_Auth_Admin::getInstance()->getIdentity()->AdminID;
        }
    }
	static function checkLogin(){
		$user_session = new Zend_Session_Namespace('user');
		$profile = $user_session -> profile;
		if(isset($profile)){
			return $profile;
		}else{
			return false;
		}
	}
	
	static function getUser(){
          if(NHK_Auth_User::getInstance()->hasIdentity()){
                  return NHK_Auth_User::getInstance()->getIdentity();
          }
          return false;
      }

	
}

?>