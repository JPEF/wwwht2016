$( document ).ready(function() {
    //catch users input from arrow keys
    $(document).keydown(function(e) {
        switch(e.which) {
            //if user presses left arrow key
            case 37: 
            //get the current click number (i.e. at what image we are currently at) from session storage
            var click = sessionStorage.getItem("click");
            //if the click is 0 or 1 the user hasn't started looking at the 
            //images or is at the first image, ignore the button press
            if (click==0 || click==1) {
               break;
            } 
            //otherwise call function imgBack that handles moving backwards in images
            else {
                imgBack(); 
            }
            break;
            
            //if user presses right arrow key, 
            //call function imgChange that handles moving forwards in images
            case 39: 
            imgChange();    
            break;
    
            default: return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });
    
    //swipe right on touchscreen, moves backwards in images
    $(".content").on("swiperight",function(){
        var click = sessionStorage.getItem("click");
        if (!(click==0 || click==1)) {
               imgBack();
        }
    });
    
    //swipe left moves forward in images
    $(".content").on("swipeleft",function(){
        imgChange(); 
    });
    
    //handles hiding/showing the header
    $("#expander").on("click",function(){
        if (!$("#expander").hasClass("expanded")) {
            $("#expander").addClass("expanded");
            $("#header").addClass("expanded");
            $(".content").addClass("expanded");
        }
        else {
            $("#expander").removeClass("expanded");
            $("#header").removeClass("expanded");
            $(".content").removeClass("expanded");
        }
    });
});

// function to take care of the image changes on the page
function imgChange() {
    $("#image").show();
    $("#infotext").hide();
    //get the click number (i.e. at what image we are currently at) from session storage
    //and get the total number of images from session storage
    var click = sessionStorage.getItem("click");
    var imageNumber = sessionStorage.getItem("links");
    
    //if clicks and imageNumber are equal we have gone through all the images 
    //available and post a recommendation to the user
    if (click == imageNumber) {
        $("#image").hide();
        $("#infotext").show();
        document.getElementById("infotext").innerHTML = "<div><p class='imagetext'>Images from this subreddit ended!</br>Search for a new one!</p></div>";
    } 
    else {
        //get images url from session storage
        var url = sessionStorage.getItem(click);
        
        //1. remove the click item from session storage
        //2. add one to click variable
        //3. put the new click back to session storage
        sessionStorage.removeItem("click");
        click++;
        sessionStorage.setItem("click", click);
        
        //update the page with new image using the url
        document.getElementById("image").innerHTML = "<img src="+url+">";
    }            
}

//function to move backwards in images
function imgBack() {
    $("#image").show();
    $("#infotext").hide();
    
    //1. get the click number (i.e. at what image we are currently at) from session storage
    //2. substact two from click variable
    var click = sessionStorage.getItem("click");
    click = click-2;
    //get images url from session storage
    var url = sessionStorage.getItem(click);
    
    //1. remove the click item from session storage
    //2. add one to click variable
    //3. put the new click back to session storage
    sessionStorage.removeItem("click");
    click++;
    sessionStorage.setItem("click", click);
    
    //update the page with the previous image using the url
    document.getElementById("image").innerHTML = "<img src="+url+">";
}

//function that handles getting the image urls from the php file using ajax/j
function getImageLinks() {
    $.ajax({
            type: "POST",
            url: "returnImages.php",
            dataType: "json", // Set the data type so jQuery can parse it for you
            success: function (data) {
                //check if the data that we got from the server is null
                if (data[0] == null) {
                    //if the data is null (i.e. there is no such subreddit), show to user that his search query is faulty 
                    document.getElementById("infotext").innerHTML = "<div><p class='imagetext'>Couldn't Find The Specified Subreddit!</br>Please Try Again!</p></div>";
                    return;
                }
                //inform user that we are ready and give guidelines on how to use the app
                document.getElementById("infotext").innerHTML = "<div><p class='imagetext'>Click Me To Start!</br>You can navigate through clicking the images or using the arrow keys.</p></div>";
                
                //1. make sure that the session storage is clean
                //2. set clicks (i.e. at what image we are currently at) to zero
                //3. set links (i.e. the amount of image urls) to zero
                sessionStorage.clear();
                sessionStorage.setItem("click", 0);
                sessionStorage.setItem("links", 0);
                
                //for loop to go through the data that we got from the ajax call
                //1. put url in session storage
                //2. update the links session storage
                for (var i=0; i<data.length; i++) {
                    sessionStorage.setItem(i, data[i]);
                    sessionStorage.setItem("links", i);
                }
            }
        });
}

