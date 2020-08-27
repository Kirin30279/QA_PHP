<?php
$oid=$_POST["order_number"];
$oid_Question=$_POST["Question"];
//echo "表單結果:$oid $oid_Question".'<br/>';
$user_id = 8888;//要學會怎麼import user_id
$type = $_POST['type'];//問題類型：價格問題、商品問題...等
$fileTemp = $_FILES['PicFile']['name'];//
//echo count($fileTemp);
$fileData = array();
$i = 0;
$t = time();//跑到這行的時間，等等拿來記圖片、記上傳時間

while($i < count($fileTemp)) {
  if ($_FILES['PicFile']['error'][$i] === UPLOAD_ERR_OK && $_FILES['PicFile']['name'][$i] != ""){
    echo '檔案名稱: ' . $_FILES['PicFile']['name'][$i] . '<br/>';
    echo '檔案類型: ' . $_FILES['PicFile']['type'][$i] . '<br/>';
    echo '檔案大小: ' . ($_FILES['PicFile']['size'][$i] / 1024) . ' KB<br/>';
    #echo '暫存名稱: ' . $_FILES['PicFile']['tmp_name'][$i] . '<br/>';
    # tmp_name如何生成？
    # 檢查檔案是否已經存在(move_uploader_file會覆蓋)
    #if (file_exists('upload/' . $_FILES['PicFile']['name'])){
        //Q1:這個upload資料夾要開在/opt/html/erp_qa
    #  echo '檔案已存在，請勿重複上傳相同檔案。<br/>';
        /*Q2:兩個不同user分別傳送兩張圖片，名稱皆為Pic1，何解？
        是否應該將儲存名稱改為"oid"+"name"？  */
    #}  
    $file = $_FILES['PicFile']['tmp_name'][$i];
    $newFileName = $t.'_'.$oid."_".$_FILES['PicFile']['name'][$i];
    #time精確到毫秒，除非訂單幾萬筆在跳，不然很難有同毫秒上傳問題
    #此外，再加上訂單編號當作檔名，即便同毫秒上傳不同訂單同檔名也ok
    $dest = 'upload/' .  $newFileName;
      # 將檔案移至指定位置，此處應是/opt/html/upload資料夾？
      #move_uploaded_file(要移動的檔案,檔案新位置);
    move_uploaded_file($file, $dest);
    
    $fileData[] = $dest;
  }

  $i += 1;
}
//print_r($fileData);
$fileData=join(",",$fileData);
// $fileData = $fileData.join(",");
//echo "上傳後的檔案路徑：$fileData".'<br/>';

$way = array();//等等讀訂單圖片的時候要用的路徑

if(!empty($fileData)) {
  $way = explode(",",$fileData);//路徑非空則把路徑寫出來，後面foreach用得到
}

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
echo $date.'<br>';


$insertSQL_Customer_qa = "INSERT INTO customer_qa(oid,oid_Question,PicFile,user_id,time,IsCustomer)
              VALUES ($oid,'$oid_Question','$fileData',$user_id,'$date',1)";
              //這邊記得要想辦法克服user_id傳入的問題
              //這邊記得要想辦法克服user_id傳入的問題
              //這邊記得要想辦法克服user_id傳入的問題
//這邊要先查看看QA_list裡面有沒有這筆訂單，如果有，就用更新，如果沒有，就用insert。


$insertSQL_QA_list = 
"INSERT INTO qa_list(oid,user_id,create_time,renew_time,status,type)
VALUES ($oid,$user_id,'$date','$date',0,$type)
ON DUPLICATE KEY UPDATE 
renew_time='$date', status = 0,type = $type";//沒該訂單就寫入有就更新
//Status=0，這樣專員才可以知道這個訂單被改成未處理。
  if(mysqli_query($connect, $insertSQL_Customer_qa)){
      echo "資料上傳成功".'<br/>';
    }else{
      echo "ERROR: Could not able to execute $insertSQL_Customer_qa. " . mysqli_error($connect).'<br/>';
      }
    if(mysqli_query($connect, $insertSQL_QA_list)){
      echo "資料上傳成功".'<br/>';
    }else{
      echo "ERROR: Could not able to execute $insertSQL_QA_list. " . mysqli_error($connect).'<br/>';
      }
echo "========================".'<br/>' ;

// 訂單編號
echo "訂單編號:$oid".'<br/>';
// 問題內容
echo "問題內容:$oid_Question".'<br/>';
// 圖片
foreach($way as $value) {
  echo "<img width=50 height=50 src='$value'/>";
}
  



// $connect -> query("SET NAMES 'utf8'");#轉UTF8減少亂碼 順利寫入中文
// $fileCount = count($_FILES['PicFile']['name']);
// $emptyOfPic = count(array_keys($_FILES['PicFile']['size'], '0'));
// #echo "空的圖片格數: $emptyOfPic";
// if($emptyOfPic!=0){
//     #若三張圖片大小都為0(沒有圖片傳入)，則僅寫入訂單編號以及問題內容
//     $insertSQL_Customer_qa = "INSERT INTO order_test(oid,oid_Question)
//     VALUES ($oid,'$oid_Question')";
//     if(mysqli_query($connect, $insertSQL_Customer_qa)){
//       echo "Records inserted successfully.";
//     }else{
//       echo "ERROR: Could not able to execute $insertSQL_Customer_qa. " . mysqli_error($connect);
//       }
//   } else{
//       for ($i = 0; $i < $fileCount; $i++){
//         if($_FILES['PicFile']['name'][$i] == "") continue;
//         if ($_FILES['PicFile']['error'][$i] === UPLOAD_ERR_OK){
//           echo '檔案名稱: ' . $_FILES['PicFile']['name'][$i] . '<br/>';
//           echo '檔案類型: ' . $_FILES['PicFile']['type'][$i] . '<br/>';
//           echo '檔案大小: ' . ($_FILES['PicFile']['size'][$i] / 1024) . ' KB<br/>';
//           echo '暫存名稱: ' . $_FILES['PicFile']['tmp_name'][$i] . '<br/>';
//           # tmp_name如何生成？
//           # 檢查檔案是否已經存在(move_uploader_file會覆蓋)
//           #if (file_exists('upload/' . $_FILES['PicFile']['name'])){
//               //Q1:這個upload資料夾要開在/opt/html/erp_qa
//           #  echo '檔案已存在，請勿重複上傳相同檔案。<br/>';
//               /*Q2:兩個不同user分別傳送兩張圖片，名稱皆為Pic1，何解？
//               是否應該將儲存名稱改為"oid"+"name"？  */
//           #}  
//             $file = $_FILES['PicFile']['tmp_name'][$i];
//             $newFileName = time().'_'.$oid."_".$_FILES['PicFile']['name'][$i];
//             #time精確到毫秒，除非訂單幾萬筆在跳，不然很難有同毫秒上傳問題
//             #此外，再加上訂單編號當作檔名，即便同毫秒上傳不同訂單同檔名也ok
//             $dest = 'upload/' .  $newFileName;
        
//             # 將檔案移至指定位置，此處應是/opt/html/upload資料夾？
//               #move_uploaded_file(要移動的檔案,檔案新位置);
//             move_uploaded_file($file, $dest);
//             $insertSQL_Customer_qa = "INSERT INTO order_test(oid,oid_Question,PicFile)
//             VALUES ($oid,'$oid_Question','$dest')";#送到order_test，要寫一個判斷式、若無圖片寫入的儲存
//             #$oid_Question是字串，一定要兩個單引號
//             #insert into外圈必為雙引號""，單引號會當作字串處理，雙引號則可被解釋與替代
//             if(mysqli_query($connect, $insertSQL_Customer_qa)){
//               echo "Records inserted successfully.".'<br/>';
//             } else{
//               echo "ERROR: Could not able to execute $insertSQL_Customer_qa. " . mysqli_error($connect).'<br/>';
//             }
//           } else {
//           echo '錯誤代碼：' . $_FILES['PicFile']['error'][$i] . '<br/>';
//         }
//     }
    
//   }
?>