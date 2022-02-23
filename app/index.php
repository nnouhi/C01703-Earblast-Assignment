<?php include '../Server/server.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <meta name="viewport" content="width=device-width" >
    <script defer src="../js/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Home</title>
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
                    <li><span style="text-decoration: underline"><a href="../app/index.php">Home</a></span></li>
                    <li><a href="../app/tracks.php">Tracks</a></li>
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
            <img class="logo" alt="earblast Logo" src="../images/logo/logo.png">
            <div class="btnsContainer" id="btnsContainer">
                <button class="Indexbtns" onclick="window.location.href='../app/login.php'">Sign In <i class="fa fa-sign-in"></i></button>
                <button class="Indexbtns" onclick="window.location.href='../app/register.php'">Sign Up<i class="fa fa-user-plus"></i></button>
            </div>
        </div>
    </div>


    <!--Display greeting message to user-->
    <div class="userProfile" id="userProfile">
        <?php
        if($username!==" "){
            $username=$_SESSION["username"];
            echo "<h2>" . "Welcome back " ."<span style='color:#5F9EA0; text-transform:uppercase'>". $username . "</span>". "</h2>";
        }

        ?>
    </div>

    <!--Branding message-->
    <div class="centered">
        <h3 class="greetmsg"><span>Listening is everything</span> </h3>
        <br>
        <p><span>Millions of songs for you to listen to for FREE!</span></p>
        <button class="plansBtn" onclick="showPlans()">View Premium Plans</button>
    </div>


    <!--Container for plans-->
    <div class="distance" id="distance">
        <?php
        $plansQuery="SELECT * FROM offers";
        $result = mysqli_query($myDB, $plansQuery);
        /*Populate the subscription section with the information fetched from the database*/
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            echo "<div class='border'>";/*Div for the borders*/
                echo "<h3>" . $row["title"] ." - £" .$row["price"]. "</h3>";
                $image=$row["image"];
                echo "<img class='subscriptionImg' src='../$image' alt='subscription plans'>";
                echo "<p>" .$row["description"] . "</p>";
            echo "</div>";
        }
        ?>
    </div>


<script>
    /*Display premium plans on index page*/
    function showPlans(){
        if(document.getElementById("distance").style.display!=="block") {

            document.getElementById("distance").style.display ="block";
        }
        else document.getElementById("distance").style.display="none";
    }
</script>

</body>
</html>