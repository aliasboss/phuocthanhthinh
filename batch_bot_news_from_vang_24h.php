
<?php

//change dir
//chdir(realpath(dirname(__FILE__)));

require 'env.php';
require 'phpQuery.php';
require 'library/NHK/Utils.php';
require 'library/NHK/Utf8.php';



//
function check_url($url,$db){
  $sql = 'select count(id) as total from url_vang_24h where url = "'.$url.'"';  
  return $db->fetchRow($sql);
}

//function generateUrlSlug($string, $maxlen = 0) {
//      $string = D_Utils::convertToAlias($string);
//      $string = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($string)), '-');
//      if ($maxlen && D_Utf8::len($string) > $maxlen) {
//          $string = substr($string, 0, $maxlen);
//          $pos = strrpos($string, '-');
//          if ($pos > 0) {
//              $string = substr($string, 0, $pos);
//          }
//      }
//      return $string;
//  }

//echo "---------------------------------<br/>";
//echo "---Vui lòng đợi trong giây lát---<br/>";
//echo "---------------------------------<br/>";
//echo "<br/>";


$content = file_get_contents('http://vang-24h.com.vn/tintuc/ds/53/Tin-tuc.html');
phpQuery::newDocumentHTML($content, $charset = 'utf8');

$html = pq('.listnew_cat_news');

$list_get = array();
$i = 0;
foreach(pq('.listnew_cat_news') as $listnews)  {
  $list_get[$i]['thumb_img']=pq($listnews)->find('a img')->attr('src');
  $list_get[$i]['url'] = pq($listnews)->find('a')->attr('href');
  $anchor = pq($listnews)->find('a')->html();
  $anchor = pq($anchor)->remove('img')->html();
  //$anchor = pq($anchor)->remove('h3');
  $arr_content_anchor = explode('</span>',$anchor);
  if(isset($arr_content_anchor[1])){
    $list_get[$i]['short_des'] = trim($arr_content_anchor[1]);
  }else{
    $list_get[$i]['short_des'] = trim($anchor);
  }      
  $i++;
  //echo pq($listnews)->find('a img')->attr('src');
}


$sql = "INSERT INTO `news` (
`title` ,
`title_slug` ,
`type` ,
`thumb_img` ,
`time_post` ,
`page` ,
`source` ,
`view` ,
`short_des` ,
`description` ,
`created_at` ,
`updated_at` 
)
VALUES ";



$row_news = 0;

echo "--Các tin đã lấy--<br/>";

//
for($i=count($list_get)-1; $i>=0;$i--){
  $item = $list_get[$i];
  $check_url = check_url($item['url'], $db);
  if($check_url['total']==0){
    $content = file_get_contents($item['url']);
    phpQuery::newDocumentHTML($content, $charset = 'utf8');
    
    //save url
    $data_url['url']=$item['url'];
    $data_url['created_at'] = date('Y-m-d H:i:s');
    $data_url['updated_at'] = date('Y-m-d H:i:s');
    $db->insert('url_vang_24h',$data_url);
    
    //save news
    //$content = pq('.middle2content');
    
    
    $data['title'] = trim(pq('.middle2content h1:first')->html());
    $data['title_slug'] = NHK_Utils::generateUrlSlug($data['title']);
    $data['thumb_img'] = $item['thumb_img'];
    $data['short_des'] = $item['short_des'];
    $data['description'] = pq('.middle2content .contentdetails')->html();
    $data['time_post'] = trim(pq('.middle2content .datetime')->html());
    $data['page'] = 'vang-24h.com.vn';
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $db->insert('news',$data);
    echo "--------{$data['title']}--<br/>";
    $row_news ++;
  }
  
}
//
//

//$logger->info("=======================================================");
//$logger->info("Begin batch: Get news from page gamek");
echo '-------------------------------------<br />';
echo "-----Đã lấy được {$row_news} tin ----<br />";
echo '-------------------------------------<br />';

echo "<br />";

echo '-----------------<br />';
echo '-----Hoàn tất----<br />';
echo '-----------------<br />';


//$sql = 'SELECT * FROM admin_users';
// 
//$result = $db->fetchAll($sql, 2);
//print_r($result);


header('Content-Type:text/html; charset=UTF-8');


?>
