
<?php

//change dir
chdir(realpath(dirname(__FILE__)));

include '../env.php';
//require '../phpQuery.php';


$sql = 'SELECT * FROM admin_users';
 
$result = $db->fetchAll($sql, 2);
print_r($result);


//$model_admin_users = new Model_AdminUser();
//
//$admin_users = $model_admin_users->getAll(array())->toArray();
//print_r($admin_users);
?>
