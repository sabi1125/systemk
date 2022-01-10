<?php

include "db.php";

class ProfileLogic extends db {
    public function checkEmpty($data){
        foreach($data as $key => $value){
            if($value === ""){
                return false;
            }
        }
        return true;
    }

    public function insert($data) {
        $sql = "Select * from profiles where u_id = :uid";
        $stmt = $this->connect()->prepare($sql);
        $objects = [
            ":uid" => $data["uid"]
        ];
        $stmt->execute($objects);
        $count= $stmt->rowCount();
        if($count>0){
            $updateSql = "UPDATE profiles SET bio = :bio where u_id = :uid";
            $updateStmt = $this->connect()->prepare($updateSql);
            $objects = [
                ":bio" => $data["bio"],
                ":uid" => $data["uid"],
            ];
            $updateStmt->execute($objects);
            return true;
        }else {
            $insertSql = "INSERT INTO profiles(bio,u_id) VALUES(:bio,:uid)";
            $insertStmt = $this->connect()->prepare($insertSql);
            $objects = [
                ":bio" => $data["bio"],
                ":uid" => $data["uid"],
            ];
            $insertStmt->execute($objects);
            return true;    
        }
        return false;
    }

    public function getProfile($uid){
        $sql = "Select * from profiles where u_id = :uid";
        $stmt = $this->connect()->prepare($sql);
        $objects = [
            ":uid" => $uid
        ];
        $stmt->execute($objects);
        $count = $stmt->rowCount();
        if($count <= 0){
            return null;
        }else{
            $result = $stmt->fetch();
            return $result;
        }

    }





    public function upsertProfilePicture($data){
        $check = $this->getProfile($data["uid"]);
        $image_filename = null;
        if (!empty($data['image_base64'])) {

        $base64 = preg_replace('/^data:.+base64,/', '', $_POST['image_base64']);

        $image_binary = base64_decode($base64);

        $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.png';
        $filepath =  '/var/www/public/image/' . $image_filename;
        file_put_contents($filepath, $image_binary);
        if($check !== null ){
            $updateSql = "UPDATE profiles SET profilePic = :icon_filename WHERE u_id = :uid";
            $updateStmt = $this->connect()->prepare($updateSql);
            $updateOptions = [
                ':uid' => $data['uid'],
                ':icon_filename' => $image_filename,
            ];
            $updateStmt->execute($updateOptions);
        }else{
            $insertSql = "INSERT INTO profiles (u_id,profilePic) VALUES (:uid,:icon_filename)";
            $insertStmt = $this->connect()->prepare($insertSql);
            $insertOptions = [
                ':uid' => $data['uid'],
                ':icon_filename' => $image_filename,
            ];
            $insertStmt->execute($insertOptions);    
        }

    }
}
}
