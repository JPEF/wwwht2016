<!DOCTYPE html>
<!--Google analytics-->
<?php include_once("analyticstracking.php") ?>
<html lang="en-GB">
	<head>
	    <title>IMSU - Get images from Imgur subreddit galleries</title>
	    <!--Meta info about the page-->
	    <meta charset="UTF-8">
	    <meta name="description" content="Images from Imgur subreddit galleries">
	    <meta name="keywords" content="Images, Pictures, Image, Picture, Pic, Pics, Imgur, Subreddit, Subreddits">
	    <!--Link to custom fonts-->
	    <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans+Condensed:300" rel="stylesheet">
	    
	    <link rel="shortcut icon" href="/favicon.ico" />
	    <link type="text/css" rel="stylesheet" href="style.css"/>
	    
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	    <!--trick not to get ui settings from jquery mobile-->
	    <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
        
	    <script type="text/javascript" src="script.js"></script>
	</head>
    <body>
        <!--Div to hold header stuff on the page
        Includes logo, search bar and button to get users subreddit information-->
        <div id="header">
            <div id="logo">
                <span id="logotext">IMSU</span>
            </div>
                <!--Search form for searching specific subreddit, 
                sends the search string to search.php-->
                <div id="search">
                    <form action="search.php" method="post">
                        <div id="searchinput">
                            <div id="searchtext">
                                <input type="text" name="search" placeholder="Subreddit name e.g. funny"/>
                            </div>
                            <div id="searchsubmit">
                                <input type="submit" name="submit" value="Search" />
                            </div>
                        </div>
                    </form>
                </div>
            
<?php
// Start the session
session_start();

            //Check if we have list of subreddit urls stored in session
            //If we don't have subreddit urls stored show button to get those urls using users reddit account
            //uses getYourSubreddits.php
            if(!isset($_SESSION["listOfUrls"])) {
    	        echo "<div id='account'>
    	        <form action=\"getYourSubreddits.php\" method=\"post\">
    			    <input type=\"submit\" name=\"getSubs\" id=\"getSubs\" value=\"Get subreddits\"/>
    		    </form>
    		    </div>
    		    ";
            } 
            //If we have subreddit urls stored show form/button to end the session/destroy session
            //uses logout.php
            else {
                echo "<div id='account'>
    	        <form action=\"logout.php\" method=\"post\">
    			    <input type=\"submit\" name=\"getSubs\" id=\"getSubs\" value=\"End session\"/>
    		    </form>
    		    </div>
    		    
    		    ";
            }
        //close header div    
	    echo("</div>");
	     
//Checks if we have urls to images stored in session
if(isset($_SESSION["imgUrls"])) {
    //div to show content/image to the user
    //handles also information text and header showing/hiding
    echo "
    <div class=\"content\">
        <div id='expander'></div>
        <div id='infotext' onclick='imgChange()'></div>
        <div class=\"imagewrapper\" id=\"image\" onclick=\"imgChange()\">
            
        </div>
        </div>
        ";
    //Calls script that gets image links from serverside to client    
    echo '
        <script type="text/javascript">
        getImageLinks();
        </script>
    ';
}

//Closes body and html
echo("</body>");
echo("</html>");

?>
