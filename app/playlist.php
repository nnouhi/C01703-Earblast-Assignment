<?php include '../Server/server.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <meta name="viewport" content="width=device-width" >
    <script  src="../js/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Playlist</title>
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
                <li><span style="text-decoration: underline"><a href="../app/playlist.php">Playlist</a></span></li>
                <li><a href="../app/recommended.php">Recommended</a></li>
                <li><a href="../app/profile.php">My Profile</a></li>
                <li><a href="../app/login.php?logout=true">Log Out</a></li>
            </ul>
        </div>
        <div class="menu-bg" id="menu-bg"></div>

        <!--The logo of the brand-->
        <div class="box">
            <a class="navbar-brand" href=../app/index.php>
                <img class="logoSmall"  alt="earblast Logo" src="../images/logo/logo2.png">
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
       <div class="IndexContainer2">
                <?php
                    $username=$_SESSION["username"];
                    /*Playlist container*/
                    echo "<h1>My Playlist</h1>";
                    $cntBtn=1;
                    $audioId=1;

                    /*Generate 20 random tracks buttons*/
                     echo'<form class="Logout" method="get" action="#">'.
                            '<br><br>'.
                            '<button type="submit" name="generateRndTracks" class="Indexbtns3">Generate 20 random tracks<i class="fa fa-refresh"></i></button>'.
                    '</form>';

                     /*Delete all tracks*/
                    echo'<form class="Logout" method="get" action="#">'.
                        '<br><br>'.
                        '<button type="submit" name="deleteTracks" class="clearFilterBtn">Delete all tracks<i class="fa fa-trash"></i></button>'.
                    '</form>';
                    echo "<br><br>";

                    $query="SELECT * FROM playlist WHERE username LIKE '%{$username}%'";
                    $result = mysqli_query($myDB, $query);
                    if(mysqli_num_rows($result)!==0) {
                            echo "<br><br>";
                            echo "<table class='albumTable'>" . "<tr>" . "<th>Thumbnail</th>" ."<th>Artist</th>" . "<th>Album</th>" . "<th>Genre</th>" . "</tr>";
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $track_name_playlist = $row["name"];/*We get the track id of the playlist to fetch all the information for that specific song from the tracks db*/
                                $queryTrackDb = "SELECT *  FROM tracks WHERE name LIKE '%{$track_name_playlist}%'";
                                $resultTrackDb = mysqli_query($myDB, $queryTrackDb);
                                $rowTrackDb = mysqli_fetch_array($resultTrackDb, MYSQLI_ASSOC);

                                $cntBtn++;/*increment the btn id  to achieve a unique button id for each row*/
                                $audioId++; /*increment the audio element id  to achieve a unique id for each row*/
                                $btnID = $cntBtn . "Btn";
                                $Img = $rowTrackDb["thumb"];
                                $Album = $rowTrackDb["album"];
                                $trackID=$rowTrackDb["track_id"];/*Will be used for the track/album description page*/
                                $audio = $rowTrackDb["sample"];
                                $songName = $rowTrackDb["name"];
                                $artist = $rowTrackDb["artist"];
                                $genre = $rowTrackDb["genre"];
                                $songNameID = str_replace(' ', '', $rowTrackDb["name"]);
                                $AlbumID = str_replace(' ', '', $rowTrackDb["album"]);

                                    echo "<tr>".
                                        "<td>"./*This <td> contains the thumbnail picture and the song name*/
                                            "<form class='Logout' method='GET' action='#'>
                                                   <button style='background: red' type='submit' name='deleteSongBtn' value='$songName'><i class='fa fa-minus'></i></button>
                                            </form>".
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
                            echo "</table>";
                    }
                    else{
                        echo "<div class='centered3'>
                                 <br><br>
                                <h2>Your playlist seems to be empty :(</h2>
                        </div>";
                    }


                    /*Generate 20 random tracks section*/
                    if(isset($_GET["generateRndTracks"])){
                        $unique_tracks=20;
                        for($i=0; $i<$unique_tracks; $i++){
                            $random_track_id=rand(1,122);/*Generate a random number between track_id (1,available tracks in database(122))*/
                            $query_check_if_track_id_exists="SELECT * from playlist WHERE track_id like '%{$random_track_id}%'";
                            $result_check_if_track_id_exists = mysqli_query($myDB, $query_check_if_track_id_exists);
                            /*If that track exists in the playlist increment the the unique_tracks range so we get 20 UNIQUE tracks*/
                            if(mysqli_num_rows($result_check_if_track_id_exists)!==0){
                                $unique_tracks+=1;
                            }
                            /*Else if track doesnt exist inside our playlist */
                            else{
                                $query_tracks="SELECT * FROM tracks WHERE track_id like '%{$random_track_id}%'";
                                $result_query_tracks = mysqli_query($myDB, $query_tracks);
                                $row=mysqli_fetch_array($result_query_tracks,MYSQLI_ASSOC);
                                $track_id_playlist=$row["track_id"];
                                $artist_playlist=$row["artist"];
                                $album_playlist=$row["album"];
                                $name_playlist=$row["name"];
                                $query_insert_to_playlist = "INSERT INTO playlist (username,track_id,artist,album,name)
                                VALUES('$username','$track_id_playlist','$artist_playlist','$album_playlist','$name_playlist')";
                                mysqli_query($myDB,$query_insert_to_playlist);
                            }
                        }
                        /*Alert user when process is finished*/
                        echo "<script>
                                    alert('20 random songs added to your playlist!');
                                    window.location.href='../app/playlist.php';
                              </script>";
                    }
                    /*When delete all tracks button is pressed*/
                    if(isset($_GET["deleteTracks"])){
                        /*Delete all songs from playlist*/
                        $query_delete_all="DELETE FROM playlist WHERE username like '%{$username}%'";
                        if(mysqli_query($myDB,$query_delete_all)){
                            echo "<script>
                                    alert('All songs deleted from playlist successfully!');
                                    window.location.href='../app/playlist.php';
                                  </script>";
                        }
                    }

                    /*Delete a specific song from the playlist*/
                    if(isset($_GET["deleteSongBtn"])){
                        $songName=$_GET["deleteSongBtn"];
                        /*Delete the song from users playlist*/
                        $query_delete_song="DELETE FROM playlist WHERE username like '%{$username}%' AND name like '%{$songName}%'";
                        if(mysqli_query($myDB,$query_delete_song)){
                            echo "<script>
                                    alert('Song was deleted from your playlist');
                                    window.location.href='../app/playlist.php';
                                  </script>";
                        }
                    }
                ?>
       </div>
    </div>

    <!--Displays this message when the user is not signed in-->
    <div class="centered" id="displayCentered">
        <h2>In order to view the playlist page you have to be <span>Signed In.</span>
            Click the button above to start listening!
        </h2>
    </div>

</body>
</html>