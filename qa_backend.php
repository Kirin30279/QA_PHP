<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>問與答 列表</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="m-2">
    <table class="table table-bordered">
      <thead>
        <tr>
          <td>#</td>
          <td>訂單編號</td>
          <td>會員名稱</td>
          <td>提問時間</td>
          <td>更新時間</td>
          <td>問題類型</td>
          <td>回覆狀況</td>
        </tr>
      </thead>
      <tbody>
        
        <?php
        $servername='localhost';//主機名稱
        $username='root';//使用者名稱
        $password='';//使用者PW
        $dbname = "day5_qa";//存取的DB名稱
        $connect = new mysqli($servername,$username,$password,"$dbname");
        if ($connect->connect_error) {
           die("連線失敗: " . $connect->connect_error);
        }
        $query = 
        "SELECT * FROM `qa_list`  
        ORDER BY `status`ASC,`renew_time` DESC";
        //後台列表優先顯示待處理訂單、以最後更新時間排序，後進先出

        $result = $connect->query($query);
        if (!$result) {//抓不到資料的話
          die("Fatal Error");
        }

        $rows = $result->num_rows;//符合qurey的資料有幾項
      
        for ($j = 0 ; $j < $rows ; ++$j){
          $array = $result->fetch_array(MYSQLI_ASSOC);
          $html  = '';
          $html .= '<tr>';
          $html .= '<td>';
          $html .= $j+1;
          $html .= '</td>';
          $html .= '<td>';//提問超連結↓
          $html .= '<a href="qa_backendwithMySQL.php?oid='.$array['oid'].'"'.'>'.$array['oid'].'</a></td>';
          $html .= '<td>';//提問者名稱↓
          $html .= $array['user_id'];
          $html .= '</td>';
          $html .= '<td>';//提問時間↓
          $html .= $array['create_time'];
          $html .= '</td>';
          $html .= '<td>';//更新時間↓
          $html .= $array['renew_time'];
          $html .= '</td>';
          $html .= '<td>';//問題類型↓ 費用問題、貨況問題、運送問題、其它
          switch($array['type']){
            case '1':
              $html .= '費用';
              $html .= '<img src="pic/money.png" alt="費用" title="費用" width="50px" height="50px">';
            break;
            case '2':
              $html .= '貨況';
              $html .= '<img src="pic/buy.png" alt="貨況" title="貨況" width="50px" height="50px">';
            break;
            case '3':
              $html .= '運送';
              $html .= '<img src="pic/truck.jpg" alt="運送" title="運送" width="50px" height="50px">';
            break;
            case '4':
              $html .= '其它';
              $html .= '<img src="pic/file.png" alt="其它" title="其它" width="50px" height="50px">';
            break;
            default:
              $html .= '問題類型遺失';          
          };
          $html .= '</td>';
          $html .= '<td>';//回覆狀況↓
          switch($array['status']){
            case '0':
              //$html .= '待處理';
              $html .= '<img src="pic/cross.png" alt="待處理" title="待處理" width="50px" height="50px">';
            break;
            case '1':
              //$html .= '已完成';
              $html .= '<img src="pic/check.jpg" alt="完成" title="完成" width="50px" height="50px">';
            break;
            default:
              $html .= '回覆狀況遺失';
          };
          $html .= '</td>';
          $html .= '</tr>';
          echo $html;
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>