<?php
include_once('./upload.php');
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='./style/style.css'>
    <title>前期試験Systemk</title>
</head>
<body>
 <div class="form">
<form method="post" action="">
  <label>Post Form</label>
  <textarea name="text" class="txt"></textarea> 
  <input id="inp_img" name="img" type="hidden" value="" >
  <input id="inp_file" type="file" class='image'> 
  <input id="bt_save" type="submit" value="Upload" class="button" name='submit'>
</form>
</div>
<?php foreach ($data as $posts): ?>
    <div class="posts">
        <?php $checks = []; ?>
        <p id="<?php echo $posts['id']; ?>"><?php echo 'post_id' . " : " .$posts['id']; ?></p>
          <?php if (1 === preg_match('/^>>[0-9]{1,}/', $posts['posts'], $checks)): ?>
          <?php $id = substr($checks[0], 2)?>
          <dd class='msg'><a href="#<?php echo $id; ?>">>><?php echo $id ?></a></dd>
          <dd class="msg"><?php echo htmlspecialchars(preg_replace('/^>>[0-9]{1,}/', '', $posts['posts'])); ?></dd>
          <?php else: ?>
          <dd class = 'msg'><?php echo htmlspecialchars($posts['posts']); ?></dd>
          <?php endif; ?>
          <?php if (!empty($posts['imagename'])): ?>
          <dd><img src="../image/<?php echo htmlspecialchars($posts['imagename']);?>"></dd>
          <?php endif; ?>
          <p class='time'>created_at: <?php echo $posts['created_at']; ?></p>
      </div>
<?php endforeach;?>
<script>
function fileChange(e) { 
    document.getElementById('inp_img').value = '';
    var file = e.target.files[0];
    if (file.type == "image/jpeg" || file.type == "image/png") {
       var reader = new FileReader();  
       reader.onload = function(readerEvent) {
          var image = new Image();
          image.onload = function(imageEvent) {    
             var max_size = 300;
             var w = image.width;
             var h = image.height;
             if (w > h) {  if (w > max_size) { h*=max_size/w; w=max_size; }
             } else     {  if (h > max_size) { w*=max_size/h; h=max_size; } }
             var canvas = document.createElement('canvas');
             canvas.width = w;
             canvas.height = h;
             canvas.getContext('2d').drawImage(image, 0, 0, w, h);
             if (file.type == "image/jpeg") {
                var dataURL = canvas.toDataURL("image/jpeg", 1.0);
             } else {
                var dataURL = canvas.toDataURL("image/png");   
             }
             document.getElementById('inp_img').value = dataURL;   
          }
          image.src = readerEvent.target.result;
       }
       reader.readAsDataURL(file);
    } else {
       document.getElementById('inp_file').value = ''; 
       alert('Please only select images in JPG- or PNG-format.');  
    }
 }
 document.getElementById('inp_file').addEventListener('change', fileChange, false);          
</script>
</body>
</html>