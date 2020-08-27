<?php
$oid=$_POST["oid"];//訂單編號(應該要從前一個PHP頁面一起傳過來，而非指定)
$oid_Question=$_POST["content"];//官方回應的存取，等等還是存到db裡面的oid_Question
$status=$_POST['CloseorNot'];//訂單回覆狀態，1代表完成回覆，等等要接收前面那頁的選項。
$user_id = strval(7777);//官方客服編號，應該從上個回覆頁面中抓取，而非指定
$t = time();//跑到這行的時間，等等拿來記圖片、記上傳時間


$servername='localhost';#主機名稱
$username='root';#使用者名稱
$password='';#使用者PW
$dbname = "day5_QA";#存取的DB名稱
$connect = mysqli_connect($servername,$username,$password,"$dbname");#可以跟new mysqli比較
if ($connect->connect_error) {
   die("連線失敗: " . $connect->connect_error);
}
//echo "連線成功".'<br/>';
date_default_timezone_set("Asia/Taipei");//時區拉到台北來
$date = date('Y-m-d H:i:s',$t);//把time()取出來的東西變成date的形式，跟隨著時區改變。

$insertSql = 
"INSERT INTO customer_qa(oid,oid_Question,PicFile,user_id,time,IsCustomer)
VALUES ($oid,'$oid_Question','',$user_id,'$date',0)";

$insertSQL_QA_list = 
"UPDATE qa_list SET renew_time='$date',status=$status WHERE oid = $oid";

if(mysqli_query($connect, $insertSql)){
  echo "資料上傳成功".'<br/>';
} else{
  echo "ERROR: Could not able to execute $insertSql. " . mysqli_error($connect).'<br/>';
  }
if(mysqli_query($connect, $insertSQL_QA_list)){
  echo "資料上傳成功".'<br/>';
  } else{
  echo "ERROR: Could not able to execute $insertSQL_QA_list. " . mysqli_error($connect).'<br/>';
  }






?>