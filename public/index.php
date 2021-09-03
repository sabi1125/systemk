<?php
$dbh = new PDO('mysql:host=mysql;dbname=testbase', 'root', '');



if(isset($_POST['text'])){
    $image_filename = null;
    if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {

        $size = $_FILES['image']['size'];
        
        if (preg_match('/^image\//', mime_content_type($_FILES['image']['tmp_name'])) !== 1){
              header("HTTP/1.1 302 Found");
              header("Location: ./index.php");
            }
            $pathinfo = pathinfo($_FILES['image']['name']);
            $extension = $pathinfo['extension'];
            $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
            $filepath =  '/var/www/public/image/' . $image_filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $filepath);

          }
          
          $sql = 'insert into posts(posts,imagename)values(:posts,:imagename)';
          $stmt = $dbh->prepare($sql);
          $stmt->execute(['posts'=>$_POST['text'],'imagename'=>$image_filename ]);
          header('Location:index.php');        
    
}

$sql = 'select * from posts ORDER BY created_at DESC';
$data = $dbh->prepare($sql);
$data->execute();

?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>システム開発前期試験</title>
</head>
<body>
    <main>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Post Form</label>
        <textarea name="text" class="txt"></textarea>
        <input type="file" accept="image/*" name="image" id="inputImage">
        <button>送信</button>
        <canvas id="canvas"></canvas>

    </form>
</main>

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
let imgInput = document.getElementById('inputImage');


    imgInput.addEventListener('change', function (e) {
    const width = 500;
    const height = 300;
    const fileName = e.target.files[0].name;
    const reader = new FileReader();
    reader.readAsDataURL(e.target.files[0]);
    reader.onload = event => {
        const img = new Image();
        img.src = event.target.result;
        img.onload = () => {
            const elem = document.getElementById('canvas');
            elem.width = width;
            elem.height = height;
            const ctx = elem.getContext('2d');
            ctx.drawImage(img,0,0,width,height);
            ctx.canvas.toBlob((blob) => {
                const file = new File([blob], fileName, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                })
                console.log(file);
                const formData = new FormData();
                formData.set('image', blob,file.name)
                console.log(blob)
            }, 'image/jpeg',1)
        }
        reader.onerror = error =>console.log(error);
    }
  });   




  
</script>
</body>
</html>

