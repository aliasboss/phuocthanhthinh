<?php
$arr = array("a" => 1, "b"=> 2);
$encode = json_encode($arr);

echo $encode."<br/>";

$decode = json_decode($encode);

var_dump($decode);

$a = '{\"username\":\"n\",\"fullname\":\"\",\"phone\":\"\",\"noitem\":\"\"}';

echo stripslashes($a);