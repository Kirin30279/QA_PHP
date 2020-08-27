<?php
$servername='localhost';#主機名稱
$username='root';#使用者名稱
$password='';#使用者PW
$dbname = "day5_qa";#存取的DB名稱
$connect = new mysqli($servername,$username,$password,"$dbname");
  if ($connect->connect_error) {
    die("連線失敗: " . $connect->connect_error);
    }
      
$oid =  $_GET['oid']; //這個訂單編號應該由外部傳入，從列表點選的時候固定
$user_id = $_GET['user_id'];//這個客戶的id應該有方法可紀錄
      

      //var_dump(date("Y-m-d H:i:s"));
if(isset($_POST['send'])){ //check if form was submitted，這裡填submit的name
  $oid_Question = $_POST["content"];//官方回應的存取，等等還是存到db裡面的oid_Question
  $status = $_POST['CloseorNot'];//訂單回覆狀態，1代表完成回覆，等等要接收前面那頁的選項。


  /*----------------底下開始把ReplyQA的東西拿來用，準備存檔了-------------*/
  $user_id = strval(7777);//官方客服編號，應該從上個回覆頁面中抓取，而非指定
  $t = time();//跑到這行的時間，等等拿來記圖片、記上傳時間
  date_default_timezone_set("Asia/Taipei");//時區拉到台北來
  $date = date('Y-m-d H:i:s',$t);//把time()取出來的東西變成date的形式，跟隨著時區改變。
  $insertSql = 
  "INSERT INTO customer_qa(oid,oid_Question,PicFile,user_id,time,IsCustomer)
  VALUES ($oid,'$oid_Question','',$user_id,'$date',0)";
  
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
           
      $query = "SELECT * FROM `customer_qa` WHERE `oid`=$oid 
                ORDER BY `time` ASC";
                //這行可調，之後QA列表要想辦法送oid，才能查到該訂單編號的結果。
               //ORDER BY time ASC 資料隨著時間來升冪排列(遞增) 、DESC則是降冪
      $result = $connect->query($query);
      if (!$result) die("Fatal Error");
      
      $rows = $result->num_rows;//符合qurey查找資格的行數有幾行，等等for迴圈要用
      
      for ($j = 0 ; $j < $rows ; ++$j)
      {
      $array = $result->fetch_array(MYSQLI_ASSOC);
      //可以比較看看MYSQLI_NUM、MYSQLI_BOTH
      
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
         $html .= $array['oid_Question'];
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
         $html .= $array['oid_Question'];
         $html .= '</p>';
         $html .= '</div>';
         $html .= '</div>';
         $html .= '</div>';
         echo $html;
         }
      
      
      //var_dump($array);
      echo "<br>";

      }

      ?>
        
    <!-- 官方回覆編輯區 -->
    <!-- <form action="replyQA.php" name="confirmationForm" method="post"> -->
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
          <!--  <a href="#"  class="btn btn-primary btn-sm">回覆</a> -->
          <!-- 這邊一樣用form送出去 -->
        </div>
      </div>
    </div>
    </form> 

  </div>
<script>
// function replyFunction() {
//     var value = document.getElementById('content').value;//取得Textarea內的值
//     var radios = document.getElementsByName('CloseorNot');//要不要送信的判斷、Y或N
//   }
// function eraseText() {//拿來清空id為content的內容，這邊是指清空官方回覆區域
//   //為什麼這邊的清空會有刷新頁面(跳到最上方)的效果？因為href="#"會回到最上方
//     document.getElementById("content").value = "";
// }
// </script>



</body>
</html>