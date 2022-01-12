<?php
include "db.php";
session_start();

class TimelineLogic extends db {
    
    public function checkEmpty($data) {
        foreach($data as $key => $value){
            if($value === ""){
                return false;
            }
        }
        return true;
    }   

}