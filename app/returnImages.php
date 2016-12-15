<?php
//start session
session_start();

//put image urls to array from session
$arr = $_SESSION["imgUrls"];

//randomize/shuffle the image urls in the array
shuffle($arr);

//echo the array in json format
echo(json_encode($arr));

?>