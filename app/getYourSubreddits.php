<?php
//Start session
session_start();



//Based on this example: https://github.com/reddit/reddit/wiki/OAuth2-PHP-Example
if (isset($_GET["error"]))
    {
        echo("<pre>OAuth Error: " . $_GET["error"]."\n");
        echo('<a href="index.php">Retry</a></pre>');
        die;
    }
    
    //urls needed for reddit api
    $authorizeUrl = 'https://ssl.reddit.com/api/v1/authorize';
    $accessTokenUrl = 'https://ssl.reddit.com/api/v1/access_token';

    //get needed files for reddit API authorization
    //get required information to use with reddit api from config
    require_once "../workspace/background/config.php";
    require("../workspace/PHP-OAuth2-master/src/OAuth2/Client.php");
    require("../workspace/PHP-OAuth2-master/src/OAuth2/GrantType/IGrantType.php");
    require("../workspace/PHP-OAuth2-master/src/OAuth2/GrantType/AuthorizationCode.php");
    
    //Create new client
    $client = new OAuth2\Client($clientId, $clientSecret, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
    $client->setCurlOption(CURLOPT_USERAGENT,$userAgent);
    
    //If user is not authenticated, ask for permission to get users subreddit information
    if (!isset($_GET["code"]))
    {
        $authUrl = $client->getAuthenticationUrl($authorizeUrl, $redirectUrl, array("scope" => "mysubreddits", "state" => "SomeUnguessableValue"));
        header("Location: ".$authUrl);
        die("Redirect");
    }
    //After authentication we can ask for users subreddits where he is subscriber
    else
    {
        //get access token
        $params = array("code" => $_GET["code"], "redirect_uri" => $redirectUrl);
        $response = $client->getAccessToken($accessTokenUrl, "authorization_code", $params);
        
        //set access token
        $accessTokenResult = $response["result"];
        $client->setAccessToken($accessTokenResult["access_token"]);
        $client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
        
        //this returns users subreddits where he is subscriber
        $response = $client->fetch("https://oauth.reddit.com/subreddits/mine/subscriber.json");
        
        //$response has much information that we don't need so we grab only the subreddit name
        $list = $response["result"]["data"]["children"];
        
        //create empty list for the urls
        $listOfUrls = [];
        
        //foreach loop to go through the subreddit names
        foreach ($list as &$value) {
            //The subreddit name includes /r/ in the beginning that we don't want 
            //so we clean the subreddit name using substr
            $cleanedSubredditName = substr($value["data"]["url"], 3);
            //After the name has been cleaned we push it in our listOfUrls
            array_push($listOfUrls, $cleanedSubredditName);
        }

        // Set the list of urls in session variable so we can use it later
        $_SESSION["listOfUrls"] = $listOfUrls;
        
        // Move to getImgurGallery.php to get imgur galleries
        header("Location: getImgurGallery.php");
        }

?>