<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
</head><body>
 
<?php

$dbh = new PDO('mysql:host=mysql;dbname=testbase', 'root', '');

if(isset($_POST['submit'])){
if (count($_POST) && (strpos($_POST['img'], 'data:image') === 0)) {
  $img = $_POST['img'];
  if (strpos($img, 'data:image/jpeg;base64,') === 0) {
      $img = str_replace('data:image/jpeg;base64,', '', $img);  
      $ext = '.jpg';
  }
  if (strpos($img, 'data:image/png;base64,') === 0) {
      $img = str_replace('data:image/png;base64,', '', $img); 
      $ext = '.png';
  }
  $img = str_replace(' ', '+', $img);
  $data = base64_decode($img);
  $file = '/var/www/public/image/' .date("YmdHis").$ext;
  file_put_contents($file, $data);





  $sql = 'insert into posts(posts,imagename)values(:posts,:imagename)';
  $stmt = $dbh->prepare($sql);
  $stmt->execute(['posts'=>$_POST['text'],'imagename'=>date("YmdHis").$ext ]);
  header('Location:index.php');        
}
}


$sql = 'select * from posts ORDER BY created_at DESC';
$data = $dbh->prepare($sql);
$data->execute();
                      
?>
 
 
<input id="inp_file" type="file">
 
<form method="post" action="">
  <label>Post Form</label>
  <textarea name="text" class="txt"></textarea>
  <input id="inp_img" name="img" type="hidden" value="">
  <input id="bt_save" type="submit" value="Upload" name='submit'>
</form>
 


<?php foreach ($data as $posts): ?>
    <div class="posts">
        <?php $matches = []; ?>
        <p id="<?php echo $posts['id']; ?>"><?php echo 'post_id' . " : " .$posts['id']; ?></p>
          <?php if (1 === preg_match('/^>>[0-9]{1,}/', $posts['posts'], $matches)): ?>
          <?php $id = substr($matches[0], 2)?>
          <dd><a href="#<?php echo $id; ?>">>><?php echo $id ?></a></dd>
          <dd><?php echo htmlspecialchars(preg_replace('/^>>[0-9]{1,}/', '', $posts['posts'])); ?></dd>
          <?php else: ?>
            <dd><?php echo htmlspecialchars($posts['posts']); ?></dd>
            <?php endif; ?>
            <?php if (!empty($posts['imagename'])): ?>
                <dd><img src="../image/<?php echo htmlspecialchars($posts['imagename']);?>"></dd>
                <?php endif; ?>
                <p>created_at: <?php echo $posts['created_at']; ?></p>
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
 
</body></html>