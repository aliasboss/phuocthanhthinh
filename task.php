
<?php

       
        $ch = curl_init();

       curl_setopt($ch, CURLOPT_URL,"http://hoikimhoancamau.com/admin/capnhatgia/task");
       //curl_setopt($ch, CURLOPT_URL,"http://localhost/hoikimhoancamau/admin/capnhatgia/task");
        //curl_setopt($ch, CURLOPT_URL,"http://phuocthanhthinh.com.vn/giavang/admin/service2");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //curl_setopt($ch, CURLOPT_NOBODY, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$strParams);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        if ($server_output === false) {
            echo "nok";
        }       
        curl_close ($ch);
        
        //return  $data;
?>
