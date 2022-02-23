<?php include '../Server/server.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <meta name="viewport" content="width=device-width" >
    <script  src="../js/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Music</title>
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
            <li><span style="text-decoration: underline"><a href="../app/tracks.php">Tracks</a></span></li>
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
    <!--All genres container-->
    <div class="Genre" id="displayTracks">
        <!--Php section-->
        <?php

            /*Array to store all the unique genre names*/
            $unique_genreArr = array();
            $cntBtn=1;
            $audioId=1;
            /*Get the count of the unique genres*/
            $total_cnt_Genre="SELECT COUNT(DISTINCT genre) as unique_cnt_genre FROM tracks ";
            $result_cnt_total = mysqli_query($myDB, $total_cnt_Genre);
            $row_total = mysqli_fetch_array($result_cnt_total, MYSQLI_ASSOC);
            $cnt=$row_total["unique_cnt_genre"];

            /*Get the unique names of the genres*/
            $totalGenre="SELECT DISTINCT genre as unique_genre FROM tracks ";
            $result_total = mysqli_query($myDB, $totalGenre);

            /*Push all the unique genres into an array that we will use to create a dynamic query*/
            while ($row_total = mysqli_fetch_array($result_total, MYSQLI_ASSOC)){
                array_push($unique_genreArr,$row_total["unique_genre"] );
            }
            echo "<div class=filterBy>";
                        echo "<h1>Filter by Genre</h1>";
                        /*Create and populate the genre list*/
                        echo "<select class='genreSelect' id='ddl' name='ddl' onmousedown='' onchange='filterGenre(this.value)'>".
                            '<option value="" selected disabled hidden>Genre</option>';
                                    for($i=0; $i<sizeof($unique_genreArr); $i++){
                                        $tableId = str_replace(' ', '', $unique_genreArr[$i]);
                                        echo "<option value='$tableId'>$unique_genreArr[$i]</option>";
                                    }
                        echo  "</select>";
                        echo "<br><br><br><br>";
                        echo "<h2>Search by artist, song or album</h2>";
                        echo'<form class="Logout" action="#">'.
                                  '<input class="inputTracks" type="text" placeholder="Search.." name="browseBy" required>'.
                                  '<button type="submit"><i class="fa fa-search"></i></button>'.
                            '</form>';
                        echo "<br><br>";
                        echo  "<button class='clearFilterBtn' id='clearGenreFilter' onclick=\"location.href='../app/tracks.php'\">Clear Filter<i class='fa fa-recycle'></i></button>";
            echo "</div>";
            /*If user did not browse by anything display all the tracks/genres*/
            if(!isset($_GET["browseBy"])) {
                for ($i = 0; $i < $cnt; $i++) {
                    /*Construct the unique query for the genre*/
                    $Query = "SELECT * FROM tracks  WHERE genre LIKE '%{$unique_genreArr[$i]}%' ORDER BY track_id ASC";
                    $result = mysqli_query($myDB, $Query);
                    $tableId = str_replace(' ', '', $unique_genreArr[$i]);
                    $tableList=$tableId."List";
                    /*Create different buttons for different genres*/
                    echo "<button class='tracksBtn' id='$tableList' onclick='displayTable(\"$tableId\")'>" . $unique_genreArr[$i] . "<i class='fa fa-caret-down'>" . "</i>" . "</button>";
                    echo "<table  id='$tableId'>" . "<tr>" . "<th>" . "Thumbnail" . "</th>" . "<th>" . "Artist" . "</th>" . "<th>" . "Album" . "</th>" . "<th>" . "Genre" . "</th>" . "</tr>";
                    /*Populate the table*/
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $cntBtn++;/*increment the btn id  to achieve a unique button id for each row*/
                        $audioId++; /*increment the audio element id  to achieve a unique id for each row*/
                        $btnID = $cntBtn . "Btn";
                        $trackID=$row["track_id"];/*Will be used for the track/album description page*/
                        $Img = $row["thumb"];
                        $Album = $row["album"];
                        $audio = $row["sample"];
                        $songName = $row["name"];
                        $artist = $row["artist"];
                        $genre = $row["genre"];
                        /*I used the function str_replace to remove the whitespaces from the strings
                          i was using as IDs because w3c validation was giving me errors*/
                        $songNameID = str_replace(' ', '', $row["name"]);
                        $AlbumID = str_replace(' ', '', $row["album"]);
                        echo "<tr>" .
                                "<td>" ./*This <td> contains the thumbnail picture and the song name*/
                                    "<img class='thumb' src='../$Img' alt='Track Thumbnail'>" .
                                    "<br>" .
                                    "<a href='description.php?Song=$trackID'>$songName</a>" .
                                "</td>" .
                                "<td>" ./*This <td> contains the artist*/
                                 $artist .
                                "</td>" .
                                "<td>" ./*This <td> contains the album*/
                                    "<a href='description.php?Album=$trackID'>$Album</a>" .
                                "</td>" .
                                "<td>" ./*This <td> contains the genre as well as the audio element and the button that plays a song and pauses it using js*/
                                    $genre .
                                    "<br>" .
                                    "<audio id='$audioId'>" .
                                        "<source src='../$audio' type='audio/mpeg'>" .
                                    "</audio>" . /*unique audio element */
                                    "<button id='$btnID' onclick='playPause(\"$audioId\",\"$btnID\")'>&#9658;</button>" .
                                "</td>" . /*the genre*/
                            "</tr>";
                    }
                    echo "</table>";
                }
            }

            /*Search by artist name, album or tracks functionality*/
            if(isset($_GET["browseBy"])){
                $queryStringBrowseBy=$_GET["browseBy"];
                $cnt=1;
                /*Construct the album query*/
                $queryAlbum="SELECT album as selected_album, genre as selected_genre FROM tracks  where album LIKE '" . $queryStringBrowseBy . "%'";
                $resultBrowseByAlbum = mysqli_query($myDB, $queryAlbum);

                /*Construct the artist query*/
                $queryArtist="SELECT artist as selected_artist, genre as selected_genre FROM tracks WHERE artist like '". $queryStringBrowseBy . "%'";
                $resultBrowseByArtist = mysqli_query($myDB, $queryArtist);

                /*Construct the song name query*/
                $querySong="SELECT name as selected_song_name, genre as selected_genre FROM tracks WHERE name like '". $queryStringBrowseBy . "%'";
                $resultBrowseBySong = mysqli_query($myDB, $querySong);

                /*If we get a row back from our database construct the table for
                the specific album, or if rows returned are 0
                  that means there was no album found, go below and search if its an artist */
                if(mysqli_num_rows($resultBrowseByAlbum)>0){
                    $row=mysqli_fetch_array($resultBrowseByAlbum,MYSQLI_ASSOC);
                    $album = $row["selected_album"];
                    $query="SELECT * FROM tracks WHERE album LIKE '$album%' ORDER BY track_id ASC";
                    $result = mysqli_query($myDB, $query);
                    echo "<table class='tracksTable'>" . "<tr>" . "<th>" . "Thumbnail" . "</th>" . "<th>" . "Artist" . "</th>" . "<th>" . "Album" . "</th>" . "<th>" . "Genre" . "</th>" . "</tr>";
                    while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                        $cntBtn++;/*increment the btn id  to achieve a unique button id for each row*/
                        $audioId++; /*increment the audio element id  to achieve a unique id for each row*/
                        $btnID = $cntBtn . "Btn";
                        $Img = $row["thumb"];
                        $trackID=$row["track_id"];/*Will be used for the track/album description page*/
                        $Album = $row["album"];
                        $audio = $row["sample"];
                        $songName = $row["name"];
                        $artist = $row["artist"];
                        $genre = $row["genre"];
                        $songNameID = str_replace(' ', '', $row["name"]);
                        $AlbumID = str_replace(' ', '', $row["album"]);
                        echo "<tr>" .
                                "<td>" ./*This <td> contains the thumbnail picture and the song name*/
                                    "<img class='thumb' src='../$Img' alt='Track Thumbnail'>" .
                                    "<br>" .
                                    "<a href='description.php?Song=$trackID'>$songName</a>" .
                                "</td>" .
                                "<td>" ./*This <td> contains the artist*/
                                    $artist .
                                "</td>" .
                                "<td>" ./*This <td> contains the album*/
                                    "<a href='description.php?Album=$trackID'>$Album</a>" .
                                "</td>" .
                                "<td>" ./*This <td> contains the genre as well as the audio element and the button that plays a song and pauses it using js*/
                                    $genre .
                                    "<br>" .
                                    "<audio id='$audioId'>" .
                                        "<source src='../$audio' type='audio/mpeg'>" .
                                    "</audio>" . /*unique audio element */
                                    "<button id='$btnID' onclick='playPause(\"$audioId\",\"$btnID\")'>&#9658;</button>" .
                                "</td>" . /*the genre*/
                            "</tr>";
                    }
                    echo "</table>";
                }

                /*If we get a row back from our database construct the table for
                the specific artist, or if rows returned are 0
                 that means there was no artist found, go below and search if its a song */
                else if(mysqli_num_rows($resultBrowseByArtist)>0){
                        $row=mysqli_fetch_array($resultBrowseByArtist,MYSQLI_ASSOC);
                        $artist = $row["selected_artist"];
                        $query="SELECT * FROM tracks WHERE artist LIKE '$artist%' ORDER BY track_id ASC";
                        $result = mysqli_query($myDB, $query);
                        echo "<table class='tracksTable'>" . "<tr>" . "<th>" . "Thumbnail" . "</th>" . "<th>" . "Artist" . "</th>" . "<th>" . "Album" . "</th>" . "<th>" . "Genre" . "</th>" . "</tr>";
                        while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                            $cntBtn++;/*increment the btn id  to achieve a unique button id for each row*/
                            $audioId++; /*increment the audio element id  to achieve a unique id for each row*/
                            $btnID = $cntBtn . "Btn";
                            $trackID=$row["track_id"];/*Will be used for the track/album description page*/
                            $Img = $row["thumb"];
                            $Album = $row["album"];
                            $audio = $row["sample"];
                            $songName = $row["name"];
                            $artist = $row["artist"];
                            $genre = $row["genre"];
                            $songNameID = str_replace(' ', '', $row["name"]);
                            $AlbumID = str_replace(' ', '', $row["album"]);
                            echo "<tr>" .
                                    "<td>" ./*This <td> contains the thumbnail picture and the song name*/
                                        "<img class='thumb' src='../$Img' alt='Track Thumbnail'>" .
                                        "<br>" .
                                        "<a href='description.php?Song=$trackID'>$songName</a>" .
                                    "</td>" .
                                    "<td>" ./*This <td> contains the artist*/
                                        $artist .
                                    "</td>" .
                                    "<td>" ./*This <td> contains the album*/
                                        "<a href='description.php?Album=$trackID'>$Album</a>" .
                                    "</td>" .
                                    "<td>" ./*This <td> contains the genre as well as the audio element and the button that plays a song and pauses it using js*/
                                        $genre .
                                        "<br>" .
                                        "<audio id='$audioId'>" .
                                            "<source src='../$audio' type='audio/mpeg'>" .
                                        "</audio>" . /*unique audio element */
                                        "<button id='$btnID' onclick='playPause(\"$audioId\",\"$btnID\")'>&#9658;</button>" .
                                    "</td>" . /*the genre*/
                                "</tr>";
                        }
                        echo "</table>";
                }

                /*If we get a row back from our database cconstruct the table for
                the specific track/song,, or if rows returned are 0
                 that means there was no song found, go below and alert the user that nothing was
                 found (song/artist/album) */
                else if(mysqli_num_rows($resultBrowseBySong)>0){
                        $row=mysqli_fetch_array($resultBrowseBySong,MYSQLI_ASSOC);
                        $song = $row["selected_song_name"];
                        $query = "SELECT * FROM tracks WHERE name LIKE '$song%' ORDER BY track_id ASC";
                        $result = mysqli_query($myDB, $query);
                        echo "<table class='tracksTable'>" . "<tr>" . "<th>" . "Thumbnail" . "</th>" . "<th>" . "Artist" . "</th>" . "<th>" . "Album" . "</th>" . "<th>" . "Genre" . "</th>" . "</tr>";
                        while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                            $cntBtn++;/*increment the btn id  to achieve a unique button id for each row*/
                            $audioId++; /*increment the audio element id  to achieve a unique id for each row*/
                            $btnID = $cntBtn . "Btn";
                            $Img = $row["thumb"];
                            $Album = $row["album"];
                            $trackID=$row["track_id"];/*Will be used for the track/album description page*/
                            $audio = $row["sample"];
                            $songName = $row["name"];
                            $artist = $row["artist"];
                            $genre = $row["genre"];
                            $songNameID = str_replace(' ', '', $row["name"]);
                            $AlbumID = str_replace(' ', '', $row["album"]);
                            echo "<tr>" .
                                    "<td>" ./*This <td> contains the thumbnail picture and the song name*/
                                        "<img class='thumb' src='../$Img' alt='Track Thumbnail'>" .
                                        "<br>" .
                                        "<a href='description.php?Song=$trackID'>$songName</a>" .
                                    "</td>" .
                                    "<td>" ./*This <td> contains the artist*/
                                        $artist .
                                    "</td>" .
                                    "<td>" ./*This <td> contains the album*/
                                        "<a href='description.php?Album=$trackID'>$Album</a>" .
                                    "</td>" .
                                    "<td>" ./*This <td> contains the genre as well as the audio element and the button that plays a song and pauses it using js*/
                                        $genre .
                                        "<br>" .
                                        "<audio id='$audioId'>" .
                                            "<source src='../$audio' type='audio/mpeg'>" .
                                        "</audio>" . /*unique audio element */
                                        "<button id='$btnID' onclick='playPause(\"$audioId\",\"$btnID\")'>&#9658;</button>" .
                                    "</td>" . /*the genre*/
                                "</tr>";
                        }
                        echo "</table>";
                }
                else{
                    /*User is alerted that nothing was found*/
                    echo '<script>alert("Zzz.. We could not find for what you were searching.")</script>';
                }
            }
        ?>

    </div>
    <!--Displays this message when the user is not signed in-->
    <div class="centered" id="displayCentered">
        <h2>In order to view the tracks page you have to be <span>Signed In.</span></h2>

        <br>

        <h2>Click the button above to start listening!</h2>
    </div>
</body>
</html>