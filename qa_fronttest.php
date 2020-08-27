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
    <form action="qa_control_pic.php" method="post" enctype="multipart/form-data">  
      <div class="form-group">
        <label>訂單編號(10位數字)</label>
        <input type="text" name="order_number" class="form-control" maxlength="10" pattern="[0-9]{10}" >
      </div>

      <div class="form-group">
        <label>提問內容</label>
        <textarea name="Question" class="form-control" cols="30" ></textarea>
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
        <div id="mfiles">
        <input id="picture" name="PicFile[]" type="file" onchange="previewFiles()" accept="image/*" multiple>
        </div>
      </div>

      <div>
      <input type="submit" value='submit'>
      </div>
    </form>  
  </div>

  <script>
  function previewFiles() {
    var preview = document.querySelector('#preview');
    var files   = document.querySelector('input[type=file]').files;
    console.log(files);
    function readAndPreview(file) {  
      if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
        var reader = new FileReader();
        reader.addEventListener("load", function () {
          var image = new Image();   
          image.height = 100; //圖片大小高度100px
          image.alt = file.name;//圖片的title為傳進來的圖片名稱
          image.src = this.result;
          preview.appendChild( image );
        }, false);
        reader.readAsDataURL(file);
      }
    }
    if (files) {
      [].forEach.call(files, readAndPreview);
    }
  }
  </script>
</body>
</html>