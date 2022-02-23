
/*Display tables on btn on click on tracks page*/
function displayTable(tableId){
    if(document.getElementById(tableId).style.display!=="flex") {

        document.getElementById(tableId).style.display ="flex";
    }
    else document.getElementById(tableId).style.display="none";
}

let pause=0;/*counter used to see if a song is paused or not*/
/*Function that plays a song and pauses it*/
function playPause(album,btnId){
    let audio=document.getElementById(album);
    let playPauseBTN=document.getElementById(btnId);
    /*If song is not paused*/
    if(pause!==1){
       pause=1;
        audio.play();
        playPauseBTN.innerHTML= "&#9208";/*Change to 'pause' icon*/
    }
    /*If song is  paused*/
    else{
        pause=0;
        audio.pause();
        playPauseBTN.innerHTML= "&#9658"; /*Change to play icon*/
    }
}

/*Function used to view a specific genre (navigate to it and make the specific table visible) and hide all irrelevant tables*/
function filterGenre(genre){
    document.getElementById("RapList").style.display ="none";   /*Hide all genre buttons */
    document.getElementById("DanceList").style.display="none";  /*Hide all genre buttons */
    document.getElementById("RockList").style.display="none";   /*Hide all genre buttons */
    document.getElementById("IndieList").style.display="none";  /*Hide all genre buttons */
    document.getElementById("RandBList").style.display="none";/*Hide all genre buttons */

    document.getElementById("Rap").style.display ="none";   /*Hide all genre tables*/
    document.getElementById("Dance").style.display="none";  /*Hide all genre tables*/
    document.getElementById("Rock").style.display="none";   /*Hide all genre tables*/
    document.getElementById("Indie").style.display="none";  /*Hide all genre tables*/
    document.getElementById("RandB").style.display="none";/*Hide all genre tables*/



    switch(genre){
        case "Rap":{
            document.getElementById("RapList").style.display ="inline"; /*Make the button visible*/
            document.getElementById("Rap").style.display ="flex"; /*make the table visible*/
            window.location="#"+genre+"List"; /*Navigate to it*/
            break;
        }
        case "Dance":{
            document.getElementById("DanceList").style.display ="inline";
            document.getElementById("Dance").style.display ="flex";
            window.location="#"+genre+"List";
            break;
        }
        case "Rock":{
            document.getElementById("RockList").style.display ="inline";
            document.getElementById("Rock").style.display ="flex";
            window.location="#"+genre+"List";
            break;
        }
        case "Indie":{
            document.getElementById("IndieList").style.display ="inline";
            document.getElementById("Indie").style.display ="flex";
            window.location="#"+genre+"List";
            break;
        }
        case "RandB":{
            document.getElementById("RandBList").style.display ="inline";
            document.getElementById("RandB").style.display ="flex";
            window.location="#"+genre+"List";
            break;
        }
    }

}

/*Function that navigates to the specific user input and highlights the <td>*/
function browseByPlaylist(input){
    document.getElementById(input).style.backgroundColor='rgba(5, 232, 240, 0.39)';
    /*navigate to that specific <tr>*/
    window.location="#"+input;
}

/*Function that displays the available plans on register page*/
function displayPlans(){
    document.getElementById("showPlans").style.display="block";
    setTimeout(function(){ window.location="#Sbheader"},300);

}

/*Function that shows and hides the password*/
function showPassword(page){
    if(page==="register") { /*If function was called from register page get password 1 and 2 */
        let password = document.getElementById("password1");
        let confirm_password = document.getElementById("password2");
        if (password.type === "password") {
            password.type = "text";
            confirm_password.type = "text";
        } else {
            password.type = "password";
            confirm_password.type = "password";
        }
    }
    /*If function was called from login page get password 1 to avoid errors*/
    else{
        let password = document.getElementById("password1");
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }
    }
}

/*Functions used to open and close the hamburger menu*/
function onClickMenu(){
    document.getElementById("menu").classList.toggle("change");
    document.getElementById("nav").classList.toggle("change");

    document.getElementById("menu-bg").classList.toggle("change-bg");
}
function onClickMenuOut(){
    document.getElementById("menu-out").classList.toggle("change");
    document.getElementById("nav-out").classList.toggle("change");

    document.getElementById("menu-bg-out").classList.toggle("change-bg");
}




