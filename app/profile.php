<?php include '../Server/server.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <meta name="viewport" content="width=device-width" >
    <script defer src="../js/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>My Profile</title>
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
                <li><a href="../app/recommended.php">Recommended</a></li>
                <li><span style="text-decoration: underline"><a href="../app/profile.php">My Profile</a></span></li>
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

    <br>
    <br>

    <!--Display users information container-->
    <div class="indexContainer" id="displayProfile">
        <h1>My Profile</h1>
        <?php
            /*Get users subscription plan*/
            $username=$_SESSION["username"];
            $user_query="SELECT * FROM login WHERE username like '%{$username}%'";
            $user_result=mysqli_query($myDB,$user_query);
            $row=mysqli_fetch_array($user_result,MYSQLI_ASSOC);
            $subscription=$row["subscription"];
            $subscription_explode=explode("-",$subscription);
            $query_plan="SELECT * FROM offers WHERE title='$subscription_explode[0]'";
            $plan_result=mysqli_query($myDB,$query_plan);
            $row=mysqli_fetch_array($plan_result,MYSQLI_ASSOC);
            $image=$row["image"];

            echo "<h4><span>User</span> <br> $username</h4>";
            echo "<br>";
            echo "<h4><span>Subscription Plan</span> <br> <img class='subscriptionImg' src='../$image' alt='subscription plans'></h4>";
        ?>
        <button class='tracksBtn' onclick="window.location.href='../app/login.php?logout=true'">Log Out <br> <i class='fa fa-sign-out'></i></button>
    </div>
    <!--Displays this message when the user is not signed in-->
    <div class="centered" id="displayCentered">
        <h2>In order to view your profile you have to be <span>Signed In.</span></h2>

        <br>

        <h2>Click the button above to start listening!</h2>
    </div>
</body>
</html>
