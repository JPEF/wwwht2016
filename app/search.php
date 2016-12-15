<?php
//check if we already have some image urls in our session
if(isset($_SESSION["imgUrls"])) {
    //unset the session
    unset($_SESSION["imgUrls"]);
}
//start session
session_start();

//Get required imgur api php wrapper files and config file to use the api
require_once "../workspace/vendor/bndr/imgur-php-wrapper/Imgur.php";
require_once "../workspace/background/config.php";

//create imgur object with key and secret (in config file)
$imgur = new Imgur($imgur_api_key, $imgur_api_secret);

//get the search string
$search = $_POST["search"];

//specify that we want images sorted by time in gallery 
//and that we want first page of the images
$sort = time;
$page = 1;

//get specified gallery using imgur api
$gallery = ($imgur->gallery()->subreddit_gallery($search, $sort, $page));

//create clean array to save the urls to images
//and grab the data from gallery and put it in imglist variable
//and save the url start that we are looking for in a variable
$imgUrls = [];
$imglist = $gallery["data"];
$correctUrlStart = "http://i.";

//foreach loop to go through the imglist and grab the url from there
//1. get the first 9 chars of the link
//2. check that the first 9 chars match the $correctUrlStart variable
//3. push the link in the $imgUrls array
foreach ($imglist  as &$value) {
    $urlStart = substr($value["link"], 0, 9);
    if (strcmp($correctUrlStart, $urlStart) == 0) {
        array_push($imgUrls, $value["link"]);
    }
}

//shuffle the image urls in $imgUrls array
shuffle($imgUrls);
//save the image urls in session
$_SESSION["imgUrls"] = $imgUrls;
//get back to frontpage
header("Location: /");

?>