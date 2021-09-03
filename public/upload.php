<?php
$dbh = new PDO('mysql:host=mysql;dbname=testbase', 'root', '');
$image = null;
if(isset($_POST['text'])){
        if (isset($_POST['img'])) {
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
                $image = date("YmdHis").$ext;
            }
              }
              $sql = 'insert into posts(posts,imagename)values(:posts,:imagename)';
              $stmt = $dbh->prepare($sql);
              $stmt->execute(['posts'=>$_POST['text'],'imagename'=>$image ]);
              header('Location:index.php');        
    }
$sql = 'select * from posts ORDER BY created_at DESC';
$data = $dbh->prepare($sql);
$data->execute();
                      
