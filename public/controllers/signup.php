<?php
require_once "../bootstrap.php";
require "../repository/signupRepository.php";

function checkIfEmpty($data) {
    $emptyFields = [];
    foreach($data as $key => $value){
        if($value === ""){
            array_push($emptyFields,$key . " " ."cannot be empty");
        }
    }
    return $emptyFields;
}




if(isset($_POST)){
    $check = checkIfEmpty($_POST);
    if(count($check) > 0 ){
        echo $twig->render("signup.twig",[
            "errors" => $check
        ]);
    }
}
echo $twig->render("signup.twig");

?>