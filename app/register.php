<?php include '../Server/server.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
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

    <!--LOGO CONTAINER-->
    <div class="IndexContainer">
        <img class="logo"  alt="earblast Logo" src="../images/logo/logo.png">
    </div>
    <!--LOGO CONTAINER ENDS-->

    <!--FORM HEADER-->
    <div class="bodyContainer">
        <div class="header">
            <h2>Register</h2>
        </div>
        <!--FORM HEADER ends-->

        <!--Register Form-->
        <form method="post" action="register.php">
            <!--Display error message-->
            <?php include '../Server/errors.php'; ?>
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter here..">
            </div>

            <div class="input-group">
                <!-- Password field -->
                <label>Password</label>
                <input type="password" name="password_1" minlength="6" id="password1" required placeholder="Enter here..">
            </div>

            <div class="input-group">
                <!-- Confirm Password field -->
                <label>Confirm password</label>
                <input type="password" name="password_2" id="password2" minlength="6" required placeholder="Enter here..">
            </div>
            <!--Show password input-->
            Show Password <input type="checkbox" onclick="showPassword('register')">
            <!--Show password input end-->
            <br>
            <br>

            <!--take user to premium plans section in register page-->
            <a onclick="displayPlans()">View our available plans!</a>
            <br>
            <br>
            <!--Choose plan section starts-->
            <label>Choose a plan</label>
            <select name="subscription" id="subscription">
            <?php
                /*Display the plans retrieved from the db*/
                $plansQuery="SELECT * FROM offers";
                $result = mysqli_query($myDB, $plansQuery);
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    echo "<option>" . $row["title"] ." - $" . $row["price"] . "</option>";
                }
            ?>
            </select>
            <!--Choose plan section ends-->

            <!--SUBMIT form button -->
            <div class="input-group">
                <button type="submit" class="btn" name="reg_user">SUBMIT</button>
            </div>
            <!--SUBMIT form button ends -->

            <p>
                Already a member? <a href="login.php">Sign in</a>
            </p>

        </form>
        <!--Register form ends-->
    </div>
<!--Body container ends-->


    <!--Subscription plans section retrieved from the database-->
    <div class="IndexContainer" id="showPlans" style="display: none">
        <div class="header" id="Sbheader">
            <h2>Subscriptions</h2>
        </div>

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
    <!--Subscription plans section retrieved from the database ends-->
</body>
</html>