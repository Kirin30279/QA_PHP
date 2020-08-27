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
        <!-- <tr>
          <td>1</td>
          <td><a href="qa_backend_d.php?id=1234567890">1234567890</a></td>
          <td>ListChen</td>
          <td>2020-07-01 10:19:00</td>
        </tr> -->
        <?php
        $servername='localhost';#主機名稱
        $username='root';#使用者名稱
        $password='';#使用者PW
        $dbname = "day5_qa";#存取的DB名稱
        $connect = new mysqli($servername,$username,$password,"$dbname");
        if ($connect->connect_error) {
           die("連線失敗: " . $connect->connect_error);
        }
        
        //$oid = 7777777777;//這個訂單編號應該由外部傳入，從列表點選的時候固定
        //$user_id = strval(8888);//這個客戶的id應該有方法可紀錄
        
        //var_dump(date("Y-m-d H:i:s"));
        
        $query = "SELECT * FROM `qa_list`  
                  ORDER BY `status`ASC,`renew_time` DESC";
                  //WHERE status = 1
                  //這行可調，之後QA列表要想辦法送oid，才能查到該訂單編號的結果。
                 //ORDER BY time ASC 資料隨著時間來升冪排列(遞增) 、DESC則是降冪
        $result = $connect->query($query);
        if (!$result) die("Fatal Error");
        
        $rows = $result->num_rows;//符合qurey查找資格的行數有幾行，等等for迴圈要用
        
        for ($j = 0 ; $j < $rows ; ++$j)
        {
        $array = $result->fetch_array(MYSQLI_ASSOC);
        //可以比較看看MYSQLI_NUM、MYSQLI_BOTH
        
        
        $html  = '';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= $j+1;
        $html .= '</td>';
        $html .= '<td>';//提問超連結↓
        $html .= '<a href="qa_backendwithMySQL.php?oid='.$array['oid'].'&'.'user_id='.$array['user_id'].'"'.'>'.$array['oid'].'</a></td>';
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
       //$html .= $array['type'];
        $html .= '</td>';
        $html .= '<td>';//回覆狀況↓
       //$html .= $array['status'];
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

  <script>
    var temp = {
          act: "checkCreatedInvoice",
          data: {
            "AV27913750": {
              "Type":"2",
              "InvDate":"2020/08/11",
              "TaxId":" ",
              "Title":" ",
              "RandomCode":"4500",
              "MemberId":58911,
              "SubAmt":25,
              "SubTaxAmt":0,
              "SubNetAmt":25,
              "CarriersType":"G0025",
              "CarriersNo":" ",
              "DonationCode":" ",
              "Item": [ {
                "DisplayLabel": "手續費",
                "UnitPrice": 25,
                "Quantity": 1,
                "TaxAmount": 0,
                "SaleAmount":25,
                "SubtotalAmount":25 
              }]
            },
            "AV27913761": {
              "Type":"2",
              "InvDate":"2020/08/11",
              "TaxId":" ",
              "Title":" ",
              "RandomCode":"7869",
              "MemberId":58339,
              "SubAmt":124,
              "SubTaxAmt":0,
              "SubNetAmt":124,
              "CarriersType":"G0025",
              "CarriersNo":" ",
              "DonationCode":" ",
              "Item": [{ 
                "DisplayLabel": "手續費",
                "UnitPrice": 24,
                "Quantity": 1,
                "TaxAmount": 0,
                "SaleAmount":24,
                "SubtotalAmount":24 
              }, { 
                "DisplayLabel": "服務費",
                "UnitPrice": 100,
                "Quantity": 1,
                "TaxAmount": 0,
                "SaleAmount":100,
                "SubtotalAmount":100
              }]
            }
          }};
    console.log(JSON.stringify(temp));
  </script>
</body>
</html>