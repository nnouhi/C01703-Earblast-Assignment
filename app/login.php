<?php include '../Server/server.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <meta name="viewport" content="width=device-width" >
    <script  src="../js/app.js"></script>
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
    <div class="IndexContainer">
        <img class="logo" alt="earblast Logo" src="../images/logo/logo.png">
    </div>
    <div class="bodyContainer">
        <div class="header">
            <h2>Log In</h2>
        </div>
        <!--Log In form-->
        <form method="post" action="login.php">
            <!--Display any error message-->
            <?php include '../Server/errors.php'; ?>
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter here.." >
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" id="password1" minlength="6" required placeholder="Enter here.." >
            </div>
            <!--Show password input-->
            Show Password <input type="checkbox"  onclick="showPassword('login')">
            <!--Show password input end-->
            <div class="input-group">
                <button type="submit" class="btn" name="login_user">SUBMIT</button>
            </div>
            <p>
                Not a member yet? <a href="register.php">Sign Up</a>
            </p>
        </form>
    </div>

    <!--Section that checks if the user logout out if url is /login.php?logout=true this means the user logged out-->
    <div id="checkLogout">
        <?php
        /*Check if any variables are specified in the url, if yes destroy sessions*/
        if(!empty($_GET)) {
                $_SESSION["username"] = "";
                $_SESSION['subscription'] = "";
                session_destroy();
        }
        ?>
    </div>

</body>
</html>