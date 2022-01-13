<?php
require_once(__DIR__ . '/../libs/init.php');
require_once(__DIR__ . '/../controllers/postsController.php');

$timeline = new PostsLogic();

session_start();

$template_filename = 'timeline.twig';
$context = [];
$errors = [];
$likes = [];
$totalLikes = [];
if(!isset($_SESSION["username"])){
    header("location:index.php");
}

if(isset($_POST["int"])){
    $userAndPost = $timeline->getFollowingPosts($_POST["int"]);
}
else{
    $userAndPost = $timeline->getFollowingPosts(5);
}

$numOfItems = count($userAndPost);
$context["userAndPost"] = $userAndPost;

foreach($userAndPost as $value){
    $check = $timeline->checkIfLiked($value["id"]);
    $numberOfLikes = $timeline->totalLikes($value["id"]);
    $likeResult = [$value["id"] => $check];
    $likesCount = [$value["id"] => count($numberOfLikes)];
    array_push($likes,$likeResult);
    array_push($totalLikes,$likesCount);
}



$context["count"]=count($userAndPost);
$context["likes"] = $likes;
$context["likesCount"] = $totalLikes;
$context["currentProfilePic"] = $_SESSION["profilePic"];
$context["currentUsername"] = $_SESSION["username"];

require_once(BASEPATH . '/libs/fin.php');

