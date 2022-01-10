<?php
require_once(__DIR__ . '/../libs/init.php');
require_once(__DIR__ . '/../controllers/profileController.php');

session_start();

$profile = new ProfileLogic();

$template_filename = 'profile.twig';
$context = [];

if(!isset($_SESSION["username"])){
    header("location:index.php");
}

$context["username"] = $_SESSION["username"];
$data = $profile->getProfile($_SESSION["id"]);

if($data === null){
    $context["bio"] = null;
    $context["profilePic"] = null;
}else{
    $context["bio"] = $data["bio"];
    $context["profilePic"] = $data["profilePic"];
}




require_once(BASEPATH . '/libs/fin.php');