<?php

include "db/Db.php";

class Signup extends db {
    public function registerUser($fullname,$username,$email,$password,$confirmPassword){
        var_dump($fullname);
    }
}