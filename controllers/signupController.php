<?php
include "db.php";

class SignupLogic extends db{

    public function checkEmpty($data) {
        foreach($data as $key => $value){
            if($value === ""){
                return false;
            }
        }
        return true;
    }   
    public function lengthChecks($data){
        $validateErrors = [];
        if(strlen($data['fullname']) < 6 ){
            array_push($validateErrors, 'Fullname length too small');
        }
        if(strlen($data['username']) < 3 ){
            array_push($validateErrors, 'Username length too small');
        }
        if(strlen($data['password']) < 6){
            array_push($validateErrors, 'Password length too small');
        }
        return $validateErrors;   
    }

    public function confirmPassword($password,$confirm){
        if($password !== $confirm){
            return false;
        }
        return true;
    }

    public function insert($data) {
        $errors = [];
        $sql="Select * From users where username =:username or email =:email";
        $stmt = $this->connect()->prepare($sql);
        $objects = [
            "username"=>$data["username"],
            "email"=>$data["email"]
        ];
        $stmt->execute($objects);
        $count=$stmt->rowCount();
        if($count > 0){
            array_push($errors, 'username already taken or the email adderess is already in use');
            return $errors;
        }
        $hashPass = password_hash($data["password"],PASSWORD_DEFAULT);
        $insertSql = "INSERT INTO users (fullname,email,username,password) VALUES(:fullname,:email,:username,:password)";
        $insertStmt = $this->connect()->prepare($insertSql);
        $insertObjects = [
            "fullname"=>$data["fullname"],
            "email"=>$data["email"],
            "username"=>$data["username"],
            "password"=>$hashPass,
        ];
        $insertStmt->execute($insertObjects);
        return true;
    }
}



