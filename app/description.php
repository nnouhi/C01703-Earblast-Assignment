<?php include '../Server/server.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <meta name="viewport" content="width=device-width" >
    <script  src="../js/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Description</title>
    <?php
    /*If user is not logged in dont display the logged-in navigation*/
    if(isset($_SESSION["username"])){
        echo '<style>#loggedOut { display:none;}</style>';
        echo '<style>#loggedIn { display:block;}</style>';
        echo '<style>#userProfile { display:inline-block;}</style>';
        echo '<style>#displayTracks { display:block;}</style>';
        echo '<style>#displayCentered { display:none;}</style>';
        echo '<style>#displayProfile { display:block;}</style>';
    }
    else{
        echo '<style>#loggedOut { display:block;}</style>';
        echo '<style>#loggedIn { display:none;}</style>';
        echo '<style>#userProfile { display:none;}</style>';
        echo '<style>#displayTracks { display:none;}</style>';
        echo '<style>#displayCentered { display:flex;}</style>';
        echo '<style>#displayProfile { display:none;}</style>';
    }
    ?>
</head>
<body>
<!--Display this menu if user is logged in-->
<div id="loggedIn" class="headerIndex">
    <!--Hamburger menu when user is logged in-->
    <div id="menu-bar">
        <div id="menu" onclick="onClickMenu()">
            <div id="bar1" class="bar"></div>
            <div id="bar2" class="bar"></div>
            <div id="bar3" class="bar"></div>
        </div>
        <ul class="nav" id="nav">
            <li><a href="../app/index.php">Home</a></li>
            <li><span style="text-decoration: underline"><a href="../app/tracks.php">Music</a></span></li>
            <li><a href="../app/playlist.php">Playlist</a></li>
            <li><a href="../app/recommended.php">Recommended</a></li>
            <li><a href="../app/profile.php">My Profile</a></li>
            <li><a href="../app/login.php?logout=true">Log Out</a></li>
        </ul>
    </div>
    <div class="menu-bg" id="menu-bg"></div>

    <!--The logo of the brand-->
    <div class="box">
        <a class="navbar-brand" href=../app/index.php>
            <img class="logoSmall" alt="earblast Logo" src="../images/logo/logo2.png">
        </a>
    </div>
</div>

<!--Display this menu if user is not logged in-->
<div id="loggedOut">
    <div class="IndexContainer">
        <img class="logo" alt="earblast Logo" src="../images/logo/logo.png">
        <div class="btnsContainer" id="btnsContainer">
            <button class="Indexbtns" onclick="window.location.href='../app/login.php'">Sign In <i class="fa fa-sign-in"></i></button>
            <button class="Indexbtns" onclick="window.location.href='../app/register.php'">Sign Up<i class="fa fa-user-plus"></i></button>
        </div>
    </div>
</div>

<br>
<br>

    <div class="Genre" id="displayTracks">
        <?php
        $username=$_SESSION["username"];
        /*If querystring has "Album" display the appropriate information and table*/
        if(isset($_GET["Album"])) {
            /*Because w3c validation wouldn't allow me to pass a string with white spaces
            i used track_id to get the specific album that corresponds to that id and then i used that album
            to get all the tracks of it*/
            $track_id = $_GET["Album"];
            $track_idQuery="SELECT album FROM tracks WHERE track_id='$track_id'";
            $result_track_id=mysqli_query($myDB, $track_idQuery);
            $row_track_id = mysqli_fetch_array($result_track_id, MYSQLI_ASSOC);
            $selected_album=$row_track_id["album"];

            /*Construct the query for the selected album*/
            $AlbumQuery = "SELECT * FROM tracks WHERE album='$selected_album'";
            $result = mysqli_query($myDB, $AlbumQuery);
            /*Problem here -> skips first table row*/
            $cntBtn = 1;
            $audioId = 1;

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if ($cntBtn === 1) {
                    $tableId = $row["genre"];
                    $description = $row["description"];
                    $AlbumImg = $row["image"];
                    /*The top header; containing : The selected album the description and albums image.*/
                    echo "<div class='topHeader'>";
                        echo "<h1>$selected_album<br><br></h1>";
                        echo "<h2 class='left'>Description:</h2>";
                        echo "<br><br>";
                        echo "<h4 class='description'>$description</h4>";
                            echo "<div class=centered2>";
                                echo "<img class='albumImg' src='../$AlbumImg' alt='Album image '>";
                            echo "</div>";
                        echo "<h2 class='left'>Album's Songs</h2>";
                    echo "</div>";
                    echo "<div class=filterBy>";
                    /*Selected albums table information thumbnail,name,artist,genre*/
                    echo "<table  id='$tableId' class='albumTable'>" . "<tr>" . "<th>" . "Thumbnail" . "</th>". "<th>" . "Artist" . "</th>" . "<th>" . "Genre" . "</th>" . "</tr>";
                }
                $cntBtn++;/*increment the btn id  to achieve a unique button id for each row*/
                $audioId++; /*increment the audio element id  to achieve a unique id for each row*/
                $btnID = $cntBtn . "Btn";
                $Album = $row["album"];
                $audio = $row["sample"];
                $Img = $row["thumb"];
                $songName = $row["name"];
                $genre=$row["genre"];
                $artist=$row["artist"];
                $trackID=$row["track_id"];/*Will be used for the track/album description page*/
                echo "<tr>" .
                        "<td>" ./*This <td> contains the thumbnail picture and the song name*/
                            "<img class='thumb' src='../$Img' alt='Thumb picture'>" .
                            "<br>" .
                            "<a href='description.php?Song=$trackID'>$songName</a>" .
                        "</td>" .
                        "<td>" ./*This <td> contains the artist*/
                            $artist .
                        "</td>" .
                        "<td>" ./*This <td> contains the genre as well as the audio element and the button that plays a song and pauses it using js*/
                            $genre .
                            "<br>" .
                            "<audio id='$audioId' >" .
                                "<source src='../$audio' type='audio/mpeg'>" .
                            "</audio>" . /*unique audio element */
                            "<button id='$btnID' onclick='playPause(\"$audioId\",\"$btnID\")'>&#9658;</button>" .
                        "</td>" . /*the genre*/
                    "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
          else if(isset($_GET["Song"])){
              /*Submitted review message initially hidden*/
              echo "<div id='submittedReview' style='display:none'>".
                        "<h2>Your Review was Submitted, Thank you.</h2>".
                  "</div>";
              /*Because w3c validation wouldn't allow me to pass a string with white spaces
              i used track_id to get the specific track that corresponds to that id */
              $track_id = $_GET["Song"];
              /*Break the string into an array seperating the words with "-"*/
              $review_id=explode("-",$track_id);
              $track_idQuery="SELECT name FROM tracks WHERE track_id='$review_id[0]'";
              $result_track_id=mysqli_query($myDB, $track_idQuery);
              $row_track_id = mysqli_fetch_array($result_track_id, MYSQLI_ASSOC);
              $selected_song=$row_track_id["name"];


              /*If the array size is 2; that means the url is ?Song=songname-review*/
              if(count($review_id)!==1){
                  /*After 1 second passes display the submit message and remove the review section*/
                  echo '<script>setTimeout(function(){
                     document.getElementById("reviewSection").style.display="none"
                     document.getElementById("submittedReview").style.display="block";
                         },1000)
                        </script>';

              }

              /*Construct the query for the selected song*/
              $SongQuery="SELECT * FROM tracks WHERE name LIKE '%{$selected_song}%'";
              $result = mysqli_query($myDB, $SongQuery);
              $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
              $AlbumImg=$row["image"];
              $Album=$row["album"];
              $Artist=$row["artist"];
              $audio=$row["sample"];
              $track_id=$row["track_id"];
              $song=$row["name"];
              $genre=$row["genre"];
              /*Check if the user already voted once for that specific song, if voted remove the voting section*/
              $query="SELECT *  FROM reviews WHERE name like '%{$username}%' AND product_id like '%{$track_id}%'";
              $result = mysqli_query($myDB, $query);
              if(mysqli_num_rows($result)!==0){
                  echo '<script>setTimeout(function(){
                     document.getElementById("reviewSection").style.display="none"
    
                         },1000)
                        </script>';
              }
              /*The top header; containing : The selected song an image and the album and artist .*/
              echo "<div class='IndexContainer3'>";
                     echo "<h1>$selected_song</h1><hr><br>";
                     echo "<div class=centered2>";
                            echo "<img class='albumImg' src='../$AlbumImg' alt='Album image '>";
                     echo "<h4>"."Genre: ".$genre ."<br><br>"."</h4>";
                            echo "<h4>"."Album: "."<a href='description.php?Album=$track_id'>$Album<br><br></a>"."</h4>";
                            echo "<h4>"."Artist: ".$Artist."<br><br>"."</h4>";
                            echo "<audio controls class='controls'><source src='../$audio' type='audio/mpeg'></audio>";
                     echo "</div>";
                     echo "<br><br>";
                     echo "<form class='Logout' method='post' action='#'>".
                            "<button  type='submit' id='addPlaylist' name='addPlaylist' class='Indexbtns3'>Add to Playlist<i class='fa fa-plus'></i></button>".
                            "<button style='background: red'  type='submit' id='removePlaylist' name='removePlaylist' class='Indexbtns3'>Remove From Playlist<i class='fa fa-minus'></i></button>".
                     "</form>";
                     echo "<br><br>";


              /*Construct the query for the selected song's review*/
              $ReviewQuery="SELECT * FROM reviews WHERE product_id='$track_id'";
              $result = mysqli_query($myDB, $ReviewQuery);
              /*If at least 1 review exists for the song display the review table, else display a message that says no reviews
               */
              if(mysqli_num_rows($result)!==0){
                  /*Show selected song's average rating.*/
                  $avgRatingQuery="SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id='$track_id'";/*Contruct average query*/
                  $resultRating = mysqli_query($myDB, $avgRatingQuery);
                  $rowRating = mysqli_fetch_array($resultRating, MYSQLI_ASSOC);
                  $int_avg_rating=(int)$rowRating["avg_rating"]; /*Basic casting to an integer because the result we initially get is a float number*/
                  echo"<h2 class='rating'>Average rating for this track is: $int_avg_rating</h2>";
                  echo '<div class="header">
                               <h2>Review Section</h2>
                        </div>';
                  echo "<table class='tableDescription'>".
                      "<tr>".
                      "<th>Name</th>".
                      "<th>Review</th>".
                      "<th>Rating</th>".
                      "</tr>";
                  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                      $name=$row["name"];
                      $review=$row["review"];
                      $rating=$row["rating"];
                      echo "<tr>".
                          "<td>$name</td>".
                          "<td>$review</td>".
                          "<td>$rating</td>".
                          "</tr>";
                  }
                  echo "</table>";

              }
              else{
                  echo "<br><br>";
                  echo "<h2 class='noReview'>No reviews to be displayed yet, Be the first one!</h2>";
                  echo "<br><br>";
              }

              /*review section*/
              if(isset($_POST["reviewBtn"])){
                  $userReview="";
                  $userRating="";
                  /*using mysqli_real_escape_string for security and to avoid sql injections*/
                  $userReview = mysqli_real_escape_string($myDB,$_POST["userReview"]);
                  $userRating = mysqli_real_escape_string($myDB, $_POST["userRating"]);
                  $userAlbum = mysqli_real_escape_string($myDB, $Album);
                  $userUsername = mysqli_real_escape_string($myDB, $username);
                  $user_track_id = mysqli_real_escape_string($myDB, $track_id);
                  /*Insert the review inside the database*/
                  $query = "INSERT INTO reviews (product_id,album, name, review, rating)
                  VALUES('$user_track_id','$userAlbum','$userUsername', '$userReview','$userRating')";
                  if(mysqli_query($myDB,$query)){
                      header('Location: description.php?Song='.$track_id."-review");/*Put the word review with a - seperator to check later if a review was submitted to alert
                                                                                                      the user and hide the review input section*/
                  }
              }
              /*Review section*/
              echo "<div id=reviewSection>".
                      "<h2>Write a Review</h2>".
                      "<form style='display:block' class='Logout' method='post' action='#'>
                            <textarea rows='2' cols='50' name='userReview' placeholder='Enter your review here...'>"."</textarea>".
                           "<h4>Rating (between 1 and 10)</h4>" .
                           "<input type='number' name='userRating' min='1' max='10' step='1'>"."<br><br>".
                           "<input type='submit' class='reviewBtn' name='reviewBtn'>".
                      "</form>".
                  "</div>";

            /*Check if the song is added to the playlist to make the addtoPlaylist button hidden, and show the remove from playlist button*/
            $query="SELECT *  FROM playlist WHERE username like '%{$username}%' AND track_id like '%{$track_id}%'";
            $result = mysqli_query($myDB, $query);
            if(mysqli_num_rows($result)!==0){
                echo "<script>
                        document.getElementById('addPlaylist').style.display='none';
                        document.getElementById('removePlaylist').style.display='inline';
                    </script>";
            }
            else{
                echo "<script>
                        document.getElementById('addPlaylist').style.display='inline';
                        document.getElementById('removePlaylist').style.display='none';
                    </script>";
            }

            /*When add to playlist button is pressed insert the track_id to the database and alert the user
             also hide the add to playlist button and make the remove from playlist button visible*/
            if(isset($_POST["addPlaylist"])){
                $query = "INSERT INTO playlist (username,track_id,artist,album,name)VALUES('$username','$track_id','$Artist','$Album','$song')";
                if(mysqli_query($myDB,$query)){
                    echo "<script>
                    alert('Song added to playlist!');
                    document.getElementById('addPlaylist').style.display='none';
                    document.getElementById('removePlaylist').style.display='inline';
                    </script>";
                }
            }
            /*When remove from playlist button is pressed delete the track_id from the database and alert the user
             also hide the remove from playlist button and make the add to playlist button visible*/
              if(isset($_POST["removePlaylist"])){
                  $query = "DELETE FROM playlist WHERE track_id LIKE '%{$track_id}%'";
                  if(mysqli_query($myDB,$query)){
                      echo "<script>
                    alert('Song removed from playlist!');
                    document.getElementById('addPlaylist').style.display='inline';
                    document.getElementById('removePlaylist').style.display='none';
                    </script>";
                  }
              }
          }
          /*If user tried to access description.php take him to the error page*/
           if(empty($_GET["Song"]) && empty($_GET["Album"])){
                header("Location:not_found_page.html");
          }
           echo "</div>";
        ?>
    </div>

    <!--Displays this message when the user is not signed in-->
    <div class="centered" id="displayCentered">
        <h2>In order to view the content of this page you have to be <span>Signed In.</span></h2>

        <br>

        <h2>Click the button above to start listening!</h2>
    </div>
</body>
</html>