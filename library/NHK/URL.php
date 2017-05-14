<?php

/* ------------------------------------------
 * @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
 * @PHONE: +84933731173
 * ----------------------------------------- */

class NHK_URL {


    static function getLinkPublic($key) {
        $urls = array(
            "root" => ROOT_URL. "/public",
            "js" => ROOT_URL. "/public" . "/js",
            "css" => ROOT_URL. "/public" . "/css",
            "img" => ROOT_URL. "/public" . "/img",
            "plugin" => ROOT_URL. "/public" . "/plugin",
            "jqueryUI" => ROOT_URL. "/public" . "/jqueryUI",
            "bootstrap3" => ROOT_URL. "/public" . "/bootstrap_3_3_7",
            
        );

        return $urls[$key];
    }

    static function getLinkControllerAdmin($key) {
        $urls = array(
            "index" => ROOT_URL.'/admin/index',
            "user" => ROOT_URL.'/admin/user',
            "banner" => ROOT_URL.'/admin/banner',
            "news" => ROOT_URL.'/admin/news',
            "event" => ROOT_URL.'/admin/event',
            "bot" => ROOT_URL.'/admin/bot',
            "capnhatgia" => ROOT_URL.'/admin/capnhatgia',
           
        );

        return $urls[$key];
    }
    
    static function getLinkControllerDefault($key) {
        $urls = array(
            "index" => ROOT_URL.'/index',
            "news" => ROOT_URL .'/news',
            "banner" => ROOT_URL .'/banner',
            "user" => ROOT_URL .'/user',
            "event" => ROOT_URL .'/event',
            "about" => ROOT_URL .'/about',
            "lichsugiavang" => ROOT_URL .'/lich-su-gia-vang',
            "giavang2" => ROOT_URL .'/giavang2',
            "giavang3" => ROOT_URL .'/giavang3',
            "ptt_robot" => ROOT_URL .'/pttrobot',
            "mq_robot" => ROOT_URL .'/mqrobot',
            "temp" => ROOT_URL .'/temp',
            
        );

        return $urls[$key];
    }

    static function getLink($key) {
        $urls = array(
            "root" => ROOT_URL,
            "js" => ROOT_URL . "/public/js",
            "css" => ROOT_URL . "/public/css",
            "img" => ROOT_URL . "/public/img",
            "24h" =>  ROOT_URL."/batch_bot_news_from_vang_24h.php",
            "vietstock" =>  ROOT_URL."/batch_bot_news_from_viet_stock.php",
        );

        return $urls[$key];
    }

    static function getRouteAdmin($key) {
        $module = '/admin';
        $urls = array(
            "admin" => $module,
            "index" => $module.'/index',
            "user" => $module.'/user',
            "news" => $module.'/news',
            "event" => $module.'/event',
            "cuahang" => $module.'/cuahang',
            "congnotho" => $module.'/congnotho',
            "store" => $module.'/store',
            "artisan" => $module.'/artisan',
            "totalartisan" => $module.'/totalartisan',
        );

        return $urls[$key];
    }

    static function getRoute($key) {
        $urls = array(
            "index" => '/index',
            "job" => "/job",
            "mqrobot" => '/mqrobot',
        );

        return $urls[$key];
    }

    static function getPath($key) {
        $result = array('cv_tmp' => "./public/uploads/cv/tmp",
            'cv' => "./public/uploads/cv",
        );

        return $result[$key];
    }

}

?>