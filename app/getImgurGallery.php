<?php
//Start session
session_start();

//Check that we have set list of subreddits in session
if(isset($_SESSION["listOfUrls"])) {
    
    //Get required imgur api php wrapper files and config
    require_once "../workspace/vendor/bndr/imgur-php-wrapper/Imgur.php";
    require_once "../workspace/background/config.php";
    
    //create imgur object with key and secret (in config file)
    $imgur = new Imgur($imgur_api_key, $imgur_api_secret);
    
    //create empty array to store galleries
    $galleries = [];
    //specify that we want images sorted by time in gallery
    //and specify that we want first page of the images
    $sort = time;
    $page = 1;
    
    //foreach loop to go through list of subreddits that are stored in session
    foreach ($_SESSION["listOfUrls"] as $url) {
        //get specified gallery using imgur api
        $gallery = ($imgur->gallery()->subreddit_gallery($url, $sort, $page));
        //push the gallery in array $galleries
        array_push($galleries, $gallery);
    }

    //create clean array to save the urls to images
    //and save the url start that we are looking for in a variable
    $imgUrls = [];
    $correctUrlStart = "http://i.";
    
    //foreach loop to go through the array $galleries
    foreach ($galleries as $gallery) {
        //grab the data from gallery and put it in imglist variable
        $imglist = $gallery["data"];
        //foreach loop to go through the imglist and grab the url from there
        foreach ($imglist  as &$value) {
            //check the first 9 chars of the link
            $urlStart = substr($value["link"], 0, 9);
            //check that the first 9 chars match the $correctUrlStart variable
            if (strcmp($correctUrlStart, $urlStart) == 0) {
                //push the link in the $imgUrls array
                array_push($imgUrls, $value["link"]);
            }
        }
    }
    //randomize/shuffle the image urls in $imgUrls array
    shuffle($imgUrls);
    //save the image urls in session
    $_SESSION["imgUrls"] = $imgUrls;
    //get back to frontpage
    header("Location: /");
}
?>