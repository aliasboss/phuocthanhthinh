
<?php

//change dir
//chdir(realpath(dirname(__FILE__)));

require 'env.php';
require 'phpQuery.php';
require 'library/NHK/Utils.php';
require 'library/NHK/Utf8.php';



//
function check_url($url, $db) {
  $sql = 'select count(id) as total from url_viet_stock where url = "' . $url . '"';
  return $db->fetchRow($sql);
}

//echo "---------------------------------<br/>";
//echo "---Vui lòng đợi trong giây lát---<br/>";
//echo "---------------------------------<br/>";
//echo "<br/>";


$content = get_curl('http://vietstock.vn/hang-hoa/vang-va-kim-loai-quy.htm');
phpQuery::newDocumentHTML($content);

$list_get = array();
$i = 0;

foreach (pq('.right_latest_news .right_latest_news_quick ul:eq(1) li') as $listnews) {
  $list_get[$i++] = pq($listnews)->find("a")->attr('href');
}

if (count($list_get) > 0)
  unset($list_get[count($list_get) - 1]);

$row_news = 0;
 echo "--Các tin đã lấy--<br/>";
for ($i = count($list_get) - 1; $i >= 0; $i--) {
  $check_url = check_url($list_get[$i], $db);
  if ($check_url['total'] == 0) {
    $content = get_curl("http://vietstock.vn" . $list_get[$i]);
    phpQuery::newDocumentHTML($content);

    //save url
    $data_url['url'] = $list_get[$i];
    $data_url['created_at'] = date('Y-m-d H:i:s');
    $data_url['updated_at'] = date('Y-m-d H:i:s');
    $db->insert('url_viet_stock', $data_url);
    
    $data['title'] = trim(pq("#NewsDetail_Box_Info h1")->html());
      $data['title_slug'] = NHK_Utils::generateUrlSlug($data['title']);
      $data['thumb_img'] = pq("#NewsDetail_Box_Info img:first")->attr("src");
      $data['short_des'] = trim(pq("#NewsDetail_Box_Info h2:first")->html());
      $data['description'] = pq("#NewsDetail_Box_Info p");
      $data['time_post'] = trim(pq(".date_post .po_date")->html());
      $data['page'] = 'vietstock.vn';
      $data['created_at'] = date('Y-m-d H:i:s');
      $data['updated_at'] = date('Y-m-d H:i:s');

      $db->insert('news', $data);
      echo "--------{$data['title']}--<br/>";
      $row_news++;
    
  }

}


 

  echo "--Các tin đã lấy--<br/>";

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

//header('Content-Type:text/html; charset=UTF-8');
//$sql = 'SELECT * FROM admin_users';
// 
//$result = $db->fetchAll($sql, 2);
//print_r($result);
?>
