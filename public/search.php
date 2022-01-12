<?php
require_once(__DIR__ . '/../libs/init.php');
require_once(__DIR__ . '/../controllers/searchController.php');
session_start();

$timeline = new SearchLogic();

$template_filename = 'search.twig';
$context = [];
$errors = [];

if(!isset($_SESSION["username"])){
    header("location:index.php");
}






//search by name

if(isset($_POST["byname"])){
    $checkIfFilled = $timeline->checkEmpty($_POST);
    if(!$checkIfFilled){
        array_push($errors, "You cannot search blank");
        $context["errors"] = $errors;
    }else{
        $searchResults = $timeline->searchByName($_POST);
        if((count($searchResults)) <= 0 ){
            array_push($errors, "There are no results that match this search");
            $context["errors"] = $errors;
        }else{
            $context["profiles"] = $searchResults;
        }
    }
    
}






//search by date

if(isset($_POST["bydate"])){
    $checkIfFilled = $timeline->checkEmpty($_POST);
    if(!$checkIfFilled){
        array_push($errors, "You have to set a date to search by date");
        $context["errors"] = $errors;
    }
}

require_once(BASEPATH . '/libs/fin.php');