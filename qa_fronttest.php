<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QA表單</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="m-2">
    <!--用post上傳，用post下載
    加上一個enctype="multipart/form-data，代表等等會上傳檔案
    action=qa_control.php，意思是等等資料會傳到qa_control.php-->
    <form action="qa_control_pic.php" method="post" enctype="multipart/form-data">
   
      
      <div class="form-group">
        <label>訂單編號(10位數字)</label>
        <input type="text" name="order_number" class="form-control" maxlength="10" pattern="[0-9]{10}" >
        <!-- onkeydown="limit(this, 10)" -->
        <!--text單行文本輸入
        Q1:這裡是不是要name=order_number
        input type="text" class="form-control" name="order_number"-->
      </div>

      <div class="form-group">
        <label>提問內容</label>
        <textarea name="Question" class="form-control" cols="30" ></textarea>
        <!--Q2:這邊沒有input是不是要自己加，開一個textarea會直接被input送出來嗎？
        //Q3:rows=?，Default值=5
        //Q4:承Q1，name="Question"-->
      </div>
      
      <div class="form-group">
        <label>問題類型</label>
        <select name="type">
        <option value="1">費用問題</option>
        <option value="2">貨況問題</option>
        <option value="3">運送問題</option>
        <option value="4">其它</option>
        </select>
      </div>

      <div class="form-group">
        <label>問題附件</label>
        <!-- a button, trigger input file -->
        <!-- 當每次上傳完, 怎紀錄在js變數中 -->
        <!-- 如何限制跳出來的框框最多到三個 -->
        <!-- <a href="#" onClick="createUploadBtn()">上傳檔案</a> -->
        <div id="mfiles">
        <input id="picture" name="PicFile[]" type="file" onchange="previewFiles()" accept="image/*" multiple>
        <!-- <div id="preview"></div> -->
        </div>
        <!-- 
        <input type="file" name="PicFile[]" class="form-control" accept="image/*" onchange="loadFile(event,2)">  
        <img id="output2" width="50" height="50"/>
        <input type="file" name="PicFile[]" class="form-control" accept="image/*" onchange="loadFile(event,3)">  
        <img id="output3" width="50" height="50"/> 
        -->
      </div>
        <!-- <a href="javascript:;" class="btn btn-primary">送出</a> <a href="javascript:;" class="btn btn-danger">取消</a> -->
      
      <input type="submit" value='submit'>
      </div>
    
    </form>

    
  </div>

  


  <script>
  function previewFiles() {
  //Preview的工作區
  var preview = document.querySelector('#preview');
  //選取id元素名稱為preview的人，#代表抓id，.就是抓class
  //以這邊為例子#preview其實會找到第43行的<div id="preview"></div>
  var files   = document.querySelector('input[type=file]').files;
  //同上，把第一個input[type=file]的人找出來，也就是說如果一次做三個single的input，這邊就只會抓到第一個input所上傳的file
  //將找到第一個input[type=file]的人檔案內容抓出來，賦值給函數內的files。

  console.log(files);

  function readAndPreview(file) {

    //底下這個if是拿來判斷傳入的檔案必須是img類型的檔案，不然沒辦法顯示，若判斷===1則進入if，
    //若===0就不把它當圖片看了，實際上這個限制在input那邊限制成圖片檔案就可以不用這個f。
    if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
      var reader = new FileReader();
      //新增變數reader，物件型態是FileReader，等等要拿來操作我們傳送進來的file
      reader.addEventListener("load", function () {
        var image = new Image();        //為了顯示圖片，我們建立一個新的image變數，等等要拿它來顯示圖片用(包括操作他的大小等等)
        
        
        
        
        
        
        image.height = 100; //圖片限定大小為高度100px，自己調
        image.alt = file.name;//圖片的title限定為傳進來的圖片名稱
        image.src = this.result;//
        preview.appendChild( image );
      }, false);

      reader.readAsDataURL(file);
    }

  }
  //發現有檔案傳入就讓其Preview出來，因為我們前面寫的input是multiple的方式，
  //只要判斷出有檔案傳入，以下的forEach會把所有本次傳入的檔案全部都跑一次readAndPreview。
  if (files) {
    [].forEach.call(files, readAndPreview);
  }

  }
  </script>


  <!-- <script src="uploadImageFile.js"></script>
  //讀取uploadImageFile腳本 -->
  <script>
    var counter = 1;
    var createUploadBtn = function() {
      if (counter < 3){
        counter = counter + 1;
        let html = `<img id="output${counter}" width="50" height="50"/>`;
        let sourceHtml = document.querySelector("#mfiles").innerHTML + html;
        //讓輸出位置跟著count動為何無效
        console.log(sourceHtml);

        document.querySelector("#mfiles").innerHTML = sourceHtml;
        
      } 
    }
  </script>
</body>
</html>