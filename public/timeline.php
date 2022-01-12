<?php
require_once(__DIR__ . '/../libs/init.php');
require_once(__DIR__ . '/../controllers/timelineController.php');

$signup = new TimelineLogic();



$template_filename = 'timeline.twig';
$context = [];
$errors = [];


if(!isset($_SESSION["username"])){
    header("location:index.php");
}




$context["currentProfilePic"] = $_SESSION["profilePic"];
$context["currentUsername"] = $_SESSION["username"];

require_once(BASEPATH . '/libs/fin.php');