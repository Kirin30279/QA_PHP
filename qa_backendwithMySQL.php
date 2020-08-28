<?php
//這區程式碼拿來存專員回應進DB
$servername='localhost';//主機名稱
$username='root';//使用者名稱
$password='';//使用者PW
$dbname = "day5_qa";//DB名稱
$connect = new mysqli($servername,$username,$password,"$dbname");
  if ($connect->connect_error) {
    die("連線失敗: " . $connect->connect_error);
    }   
$oid =  $_GET['oid'];//訂單編號
if(isset($_POST['send'])){//submit的判斷
  $oid_Question = addslashes($_POST["content"]);//客服人員的回應 textarea提交
  $status = $_POST['CloseorNot'];//訂單回覆狀態，1代表完成回覆
  $user_id = strval(7777);//官方客服編號
  $uploadtime = time();//跑到這行的時間，等等拿來記圖片、記上傳時間
  date_default_timezone_set("Asia/Taipei");//時區拉到台北來
  $date = date('Y-m-d H:i:s',$uploadtime);//上傳時間變成date的形式，隨著時區改變。 
  $newpers = mysqli_real_escape_string($connect,$oid_Question);
  $insertSql = 
  "INSERT INTO customer_qa(oid,oid_Question,PicFile,user_id,time,IsCustomer)
  VALUES ($oid,'$newpers','',$user_id,'$date',0)";
  
  $insertSQL_QA_list = 
  "UPDATE qa_list SET renew_time='$date',status=$status WHERE oid = $oid";

  mysqli_query($connect, $insertSql);
  mysqli_query($connect, $insertSQL_QA_list);
}
?>













<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>問與答 內頁</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      min-width: 800px
    }

    .qaMsg img {
      max-width: 40rem;
      max-height: 30rem;
    }
  </style>
</head>
<body>
  <div class="m-2">
    <div class="qaMsg card mb-2">
      <div class="card-body">
      
      <?php

      $query = 
      "SELECT * FROM `customer_qa` WHERE `oid`=$oid 
      ORDER BY `time` ASC";//找指定的訂單，內容呈現從最舊到最新排列

      $result = $connect->query($query);
      if (!$result) {//抓不到資料的話
        die("Fatal Error");
      }

      $rows = $result->num_rows;//符合qurey的資料有幾項
      
      for ($j = 0 ; $j < $rows ; ++$j){
        $array = $result->fetch_array(MYSQLI_ASSOC);
        if ($array['IsCustomer']==="1"){//代表這是客戶的提問，放左半邊
         $html = '';
         $html .= '<div class="d-flex my-3">';
         $html .= '<div class="d-flex flex-column">';
         $html .= '<div class="d-flex align-items-end border-bottom">';
         $html .= '<div class="mr-3">';
         $html .= $array['user_id'];
         $html .= '</div>';         
         $html .= '<small class="text-muted">';
         $html .= $array['time'];
         $html .= '</small>';
         $html .= '</div>';
         $html .= '<div style="width: 50rem">';
         $html .= '<p>';
         $html .= htmlspecialchars(stripslashes($array['oid_Question']));
         $html .= '</p>';

         if(!empty($array['PicFile'])) {
           $way = explode(",",$array['PicFile']);//路徑非空則把路徑寫出來，後面foreach用得到
           foreach($way as $value) {
             $html.= "<p><img src='$value'/></p>";
            }
          }

         $html .= '</div>';
         $html .= '</div>';
         $html .= '</div>';
         echo $html;
        } else{//代表這是官方的回覆，放右半邊
          $html = '';
          $html .= '<div class="d-flex justify-content-end my-3">' ;
          $html .= '<div class="d-flex flex-column">';
          $html .= '<div class="d-flex justify-content-end align-items-end border-bottom">';
          $html .= '<div class="mr-3">';
          $html .= $array['user_id'];
          $html .= '</div>';         
          $html .= '<small class="text-muted">';
          $html .= $array['time'];
          $html .= '</small>';
          $html .= '</div>';
          $html .= '<div class="d-flex justify-content-end" style="width: 50rem">';
          $html .= '<p>';
          $html .= htmlspecialchars(stripslashes($array['oid_Question']));
          $html .= '</p>';
          $html .= '</div>';
          $html .= '</div>';
          $html .= '</div>';
          echo $html;
          } 
      }
      ?>

    <form action="" name="confirmationForm" method="post">
    <textarea id="content"name="content" class="form-control" placeholder="官方回應區域"></textarea>
    <input type="hidden" name="oid" value="<?php echo $oid ?>">
    <input type="submit"  name="send" value='回覆' class="btn btn-primary btn-sm">
    <a href='qa_backend.php'  class="btn btn-danger btn-sm">回列表</a>
    <div class="d-flex">
      <div class="col-6 p-0 d-flex my-1">
        <label class="mr-3">結案(本提問已完成)</label>
        <div class="">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="CloseorNot" name="CloseorNot" value="1" checked/>
            <label class="form-check-label">是</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="CloseorNot" name="CloseorNot" value="0"/>
            <label class="form-check-label">否</label>
          </div>
        </div>
      </div>
      
      <div class="col-6 p-0 d-flex my-1 justify-content-end">
        <div class="mx-1">
        </div>
      </div>
    </div>
    </form> 

  </div>
</body>
</html>