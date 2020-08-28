<?php
$oid=$_POST["order_number"];//訂單編號
$oid_Question=htmlspecialchars(addslashes ($_POST["Question"]));//問題內容
$user_id = 8888;//使用者id
$type = $_POST['type'];//問題類型：價格問題、商品問題...等
$fileTemp = $_FILES['PicFile']['name'];//圖片暫存
$fileData = array();
$uploadtime = time();//拿來記圖片位置、記上傳時間
$i = 0;//存圖片的計數
while($i < count($fileTemp)) {
  if ($_FILES['PicFile']['error'][$i] === UPLOAD_ERR_OK && $_FILES['PicFile']['name'][$i] != ""){
    echo '檔案名稱: ' . $_FILES['PicFile']['name'][$i] . '<br/>';
    echo '檔案類型: ' . $_FILES['PicFile']['type'][$i] . '<br/>';
    echo '檔案大小: ' . ($_FILES['PicFile']['size'][$i] / 1024) . ' KB<br/>';

    $file = $_FILES['PicFile']['tmp_name'][$i];//第i張圖片
    $newFileName = $uploadtime.'_'.$oid."_".$_FILES['PicFile']['name'][$i];//重新命名
    $dest = 'upload/' .  $newFileName;//送到upload資料夾
    move_uploaded_file($file, $dest);   
    $fileData[] = $dest;//把上傳路徑記下來，等等要送到DB
  }
  $i += 1;
}
$fileData=join(",",$fileData);//上傳路徑用逗號隔開

$servername='localhost';//主機名稱
$username='root';//使用者名稱
$password='';//使用者PW
$dbname = "day5_QA";//DB名稱
$connect = mysqli_connect($servername,$username,$password,"$dbname");
if ($connect->connect_error) {
   die("連線失敗: " . $connect->connect_error);
}

date_default_timezone_set("Asia/Taipei");//時區拉到台北來
$date = date('Y-m-d H:i:s',$uploadtime);//上傳時間變成date的形式，隨著時區改變。
//第一筆上傳，傳入問題庫↓

$IsCustomer = 1 ;
$insertSQL_Customer_qa = "INSERT INTO customer_qa(oid,oid_Question,PicFile,user_id,time,IsCustomer)
VALUES (?,?,?,?,?,?)";
$stmt=$connect->prepare($insertSQL_Customer_qa);
$stmt->bind_param("issssi", $oid,$oid_Question,$fileData,$user_id,$date,$IsCustomer);
$stmt->execute();
$stmt->close();
// $insertSQL_Customer_qa = 
// "INSERT INTO customer_qa(oid,oid_Question,PicFile,user_id,time,IsCustomer)
// VALUES ($oid,'$oid_Question','$fileData',$user_id,'$date',1)";

//第二筆上傳，方便後台列表顯示↓
$insertSQL_QA_list = 
"INSERT INTO qa_list(oid,user_id,create_time,renew_time,status,type)
VALUES ($oid,$user_id,'$date','$date',0,$type)
ON DUPLICATE KEY UPDATE 
renew_time='$date', status = 0,type = $type";

// $insertSQL_QA_list = $mysqli->prepare(
//   "INSERT INTO qa_list(oid,user_id,create_time,renew_time,status,type)
//   VALUES ($oid,$user_id,'$date','$date',0,$type)
//   ON DUPLICATE KEY UPDATE 
//   renew_time='$date', status = 0,type = $type");
//沒該訂單就寫入新資料，有就更新
//status=0，這樣後台才可以知道該編號未處理。

  // if(mysqli_query($connect, $insertSQL_Customer_qa)){
  //   echo "資料上傳成功".'<br/>';
  // } else{
  //   echo "ERROR: Could not able to execute $insertSQL_Customer_qa. " . mysqli_error($connect).'<br/>';
  //   }
  if(mysqli_query($connect, $insertSQL_QA_list)){
    echo "資料上傳成功".'<br/>';
  } else{
    echo "ERROR: Could not able to execute $insertSQL_QA_list. " . mysqli_error($connect).'<br/>';
    }

echo "============↓↓↓↓↓↓下方為上傳完成輸出區↓↓↓↓↓============".'<br/>' ;
// 訂單編號
echo "訂單編號:$oid".'<br/>';
// 問題內容
echo "問題內容:$oid_Question".'<br/>';
// 圖片
$path = array();//上傳完成後，顯示圖片用的路徑
if(!empty($fileData)) {
  //filedata是圖片上傳的路徑
  $path = explode(",",$fileData);//把圖片儲存路徑存下來，foreach讀圖片
}
foreach($path as $value) {
  echo "<img width=50 height=50 src='$value'/>";
}
?>