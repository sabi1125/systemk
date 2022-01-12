<?php
require_once(__DIR__ . '/../libs/init.php');
require_once(__DIR__ . '/../controllers/postsController.php');

session_start();

$post = new PostsLogic();

$template_filename = 'addPosts.twig';
$context = [];
$errors = [];


if(!isset($_SESSION["username"])){
    header("location:index.php");
}

if(isset($_POST["submit"])){
    $data = [];
    $data["image_base64"] = $_POST["image_base64"];
    $data["post"] = $_POST["post"];
     

    $checkIfFilled = $post->checkEmpty($data);

    if(!$checkIfFilled) {
        array_push($errors, "You cannot post nothing");
        $context["errors"] = $errors;
    }else if($_POST["image_base64"] === ""){
        $insertPost = $post->insertPost([$data]);
        if($insertPost){
            header("location:profile.php");
        }else{
            array_push($errors, "internal error");
            $context["errors"] = $errors;    
        }
    }else if($_POST["image_base64"] !== "" && $_POST["post"] === ""){ 
        array_push($errors, "You need to write something along with the picture");
        $context["errors"] = $errors;    
    }else{
        $insertPost = $post->insertPost($data);
        if($insertPost){
            header("location:profile.php");
        }else{
            array_push($errors, "internal error");
            $context["errors"] = $errors;    
        }
    }
}




$context["currentProfilePic"] = $_SESSION["profilePic"];
$context["currentUsername"] = $_SESSION["username"];
require_once(BASEPATH . '/libs/fin.php');