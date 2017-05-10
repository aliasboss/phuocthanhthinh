
<?php

//change dir
//chdir(realpath(dirname(__FILE__)));

require 'env.php';
require 'phpQuery.php';
require 'library/NHK/Utils.php';
require 'library/NHK/Utf8.php';



//
function check_url($url,$db){
  $sql = 'select count(id) as total from url_vang_viet_nam where url = "'.$url.'"';  
  return $db->fetchRow($sql);
}



$content = file_get_contents('http://www.vangvietnam.vn/Articles/Default.aspx?CateID=207');
phpQuery::newDocumentHTML($content, $charset = 'utf8');

//header('Content-Type:text/html; charset=UTF-8');
//echo $html;
$start = 39;
$arr_id = array();
foreach(pq('#ctl00_PageContent_ArticlesByCate table:first tr:first a') as $listnew){
  $link =  pq($listnew)->attr('href');
  $end = strpos($link, '&');
  $id_news = substr($link, $start,$end-$start);
  array_push($arr_id, $id_news);
}

sort($arr_id);

foreach($arr_id as $id){
  $check_url = check_url($id, $db);
  if($check_url['total']==0){
    $data_url['url']=$id;
    $data_url['created_at'] = date('Y-m-d H:i:s');
    $data_url['updated_at'] = date('Y-m-d H:i:s');
    $db->insert('url_vang_viet_nam',$data_url);
    
    $content = file_get_contents("http://www.vangvietnam.vn/Articles/ArticleDetail.aspx?ArticleID=".$id."&CateID=207");
    phpQuery::newDocumentHTML($content);
    
    $data['title'] = trim(pq('#ctl00_PageContent_ctl00_Title1_lblTitle .ArticleTitle')->html());
    $data['title_slug'] = NHK_Utils::generateUrlSlug($data['title']);
    
    $data['thumb_img'] = 'http://www.vangvietnam.vn'. pq('#ctl00_PageContent_ctl00_ImageArticle')->attr('src');
    
    $data['short_des'] = pq('#ctl00_PageContent_ctl00_lblExcerpt')->html();
    
    $data['description'] = pq('#ctl00_PageContent_ctl00_lblBody')->html();
    $data['time_post'] = trim(pq('#ctl00_PageContent_ctl00_lblPostDate')->html());
    $data['page'] = 'vangvietnam.vn';
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $db->insert('news',$data);
  }
}


//$logger->info("=======================================================");
//$logger->info("Begin batch: Get news from page gamek");
echo 'done';

//$sql = 'SELECT * FROM admin_users';
// 
//$result = $db->fetchAll($sql, 2);
//print_r($result);





?>