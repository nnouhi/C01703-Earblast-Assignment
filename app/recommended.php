<?php include '../Server/server.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <meta name="viewport" content="width=device-width" >
    <script  src="../js/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Recommended</title>
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
            <li><a href="../app/tracks.php">Tracks</a></li>
            <li><a href="../app/playlist.php">Playlist</a></li>
            <li><span style="text-decoration: underline"><a href="../app/recommended.php">Recommended</a></span></li>
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
        <img class="logo"  alt="earblast Logo" src="../images/logo/logo.png">
        <div class="btnsContainer" id="btnsContainer">
            <button class="Indexbtns" onclick="window.location.href='../app/login.php'">Sign In <i class="fa fa-sign-in"></i></button>
            <button class="Indexbtns" onclick="window.location.href='../app/register.php'">Sign Up<i class="fa fa-user-plus"></i></button>
        </div>
    </div>
</div>

    <br>
    <br>
    <div class="Genre" id="displayTracks">
        <div class="IndexContainer">
            <?php
            /*Recommendation algorthm: If user rated a track with a 6 or above, recommend him 3 random songs from the album
             the rated song exists*/
                $username=$_SESSION["username"];
                echo "<h1>Recommended songs for you!</h1>";
                echo "<h2 style='color: black'>Songs based on what you like..</h2>";
                $query="SELECT DISTINCT(album) as unique_album FROM reviews WHERE name LIKE '%{$username}%' AND rating >= 6"; /*Select unique(to avoid duplicated in the list) albums that the user rated with 6 and above*/
                $result = mysqli_query($myDB, $query);
                if(mysqli_num_rows($result)!==0){/*If atleast one album was found */
                    $cntBtn=1;
                    $audioId=1;
                    echo "<br><br>";
                    echo "<table class='albumTable'>" . "<tr>" . "<th>Thumbnail</th>" . "<th>Artist</th>" . "<th>Album</th>" . "<th>Genre</th>" . "</tr>";
                    while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                          $unique_album=$row['unique_album'];
                          $query_random_songs="SELECT * FROM tracks WHERE album LIKE '%{$unique_album}%' ORDER BY RAND() LIMIT 3";/*Select 3 random rows from the tracks table that match the unique album*/
                          $result_random_songs = mysqli_query($myDB, $query_random_songs);
                           while($row=mysqli_fetch_array($result_random_songs,MYSQLI_ASSOC)){
                               $cntBtn++;/*increment the btn id  to achieve a unique button id for each row*/
                               $audioId++; /*increment the audio element id  to achieve a unique id for each row*/
                               $Img=$row["thumb"];
                               $btnID = $cntBtn . "Btn";
                               $Album=$row["album"];
                               $audio=$row["sample"];
                               $songName=$row["name"];
                               $artist=$row["artist"];
                               $genre=$row["genre"];
                               $trackID=$row["track_id"];
                               echo "<tr>".
                                       "<td>"./*This <td> contains the thumbnail picture and the song name*/
                                           "<img class='thumb' src='../$Img' alt='Thumb picture'>" .
                                           "<br>".
                                           "<a href='description.php?Song=$trackID'>$songName</a>".
                                       "</td>" .
                                       "<td>"./*This <td> contains the artist*/
                                           $artist .
                                       "</td>" .
                                       "<td>"./*This <td> contains the album*/
                                           "<a href='description.php?Album=$trackID'>$Album</a>".
                                       "</td>".
                                       "<td>"./*This <td> contains the genre as well as the audio element and the button that plays a song and pauses it using js*/
                                           $genre .
                                           "<br>".
                                           "<audio id='$audioId'>".
                                             "<source src='../$audio' type='audio/mpeg'>".
                                           "</audio>". /*unique audio element */
                                           "<button id='$btnID' onclick='playPause(\"$audioId\",\"$btnID\")'>&#9658;</button>" .
                                       "</td>". /*the genre*/
                                   "</tr>";
                           }
                    }
                    echo "</table>";
                }

                else{
                    echo "<br><br>";
                    echo "<h2>You have to rate a song with 6+ in order for us recommend you any songs!</h2>";
                }
            ?>
        </div>
    </div>

    <!-- Display this if user is not logged in-->
    <div class="centered" id="displayCentered">
        <h2>In order to view the recommended  page you have to be <span>Signed In.</span>

            <br>

            Click the button above to start listening!
        </h2>
    </div>

</body>

</html>