<?php
include 'db.php';
class PostsLogic extends db{
    public function checkEmpty($data){
        if($data["post"] === "" && $data["image"] !== ""){
            return true;
        }elseif($data["post"] === "" && $data["image"] === "" ){
            return false;
        }else{
            return true;
        }
    }

    public function insertPost($data){
        $image_filename = null;


        if (!empty($data['image_base64'])) {

        $base64 = preg_replace('/^data:.+base64,/', '', $_POST['image_base64']);

        $image_binary = base64_decode($base64);

        $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.png';
        $filepath =  '/var/www/public/image/' . $image_filename;
        file_put_contents($filepath, $image_binary);
        if($data["image_base64"] === ""){
            $sql = "INSERT INTO posts(u_id,post) VALUES (:uid,:post)";
            $stmt = $this->connect()->prepare($sql);
            $objects = [
                ":uid" => $_SESSION["id"],
                ":post" => $data["post"]
            ];
            $stmt->execute($objects);
            return true;
        }elseif($data["post"] !== "" && $data["image_base64"] !== ""){
            $sql = "INSERT INTO posts(u_id,post,image) VALUES (:uid,:post,:image)";
            $stmt = $this->connect()->prepare($sql);
            $objects = [
                ":uid" => $_SESSION["id"],
                ":post" => $data["post"],
                ":image" => $image_filename
            ];
            $stmt->execute($objects);
            return true;
        }
        return false;
    }
}
}