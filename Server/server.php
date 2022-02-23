<?php
session_start();
/*initializing variables*/
$username = "";
$subscription="";
/*An array that will handle any errors*/
$errors = array();


/*connect to the database*/
$myDB = mysqli_connect('localhost', 'nnouhi', 'LabVS7Eg', 'nnouhi');

  /*REGISTER USER*/

  /*If user pressed the submit button TODO*/
if (isset($_POST['reg_user'])) {
    /*using mysqli_real_escape_string for security and to avoid sql injections*/
    $username = mysqli_real_escape_string($myDB, $_POST['username']);
    $password_1 = mysqli_real_escape_string($myDB, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($myDB, $_POST['password_2']);
    $subscription=mysqli_real_escape_string($myDB, $_POST["subscription"]);
    // by adding (array_push()) corresponding error unto $errors array
    /*If passwords don't match alert user*/
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

   /* first check the database to make sure*/
   /* a user does not already exist with the same username */
    $user_check_query = "SELECT * FROM login WHERE username='$username' LIMIT 1";
    $result = mysqli_query($myDB, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { /* if user exists Alert user*/
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
    }
    /*Finally, register user if there are no errors*/
    if (count($errors) == 0) {
        /*hashing and salting the password before saving in the database*/
        $password = password_hash($password_1,PASSWORD_DEFAULT);
        $query = "INSERT INTO login (id, username, password,subscription) 
  			  VALUES('', '$username', '$password', '$subscription')";
        mysqli_query($myDB, $query);
        /*Save username to display later*/
        $_SESSION['username'] = $username;
        $_SESSION['subscription'] = $subscription;
        echo "<script>" . "alert('Registered! Welcome to EarBlast')" . "</script>";
        echo "<script>" . "window.location.href='index.php'" . "</script>";
    }
}

  /*LOG IN USER*/
/*If user pressed the submit button TODO*/
if(isset($_POST["login_user"])){
   /* receive all input values from the form,
    escape special characters, if any*/
   $username = mysqli_real_escape_string($myDB, $_POST['username']);
   $password_1 = mysqli_real_escape_string($myDB, $_POST['password']);

   $query="SELECT * FROM login WHERE username='$username'";
   $result=mysqli_query($myDB,$query);
   /*If username exists*/
   if(mysqli_num_rows($result)>0){
       while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
           /*user found*/
           if(password_verify($password_1,$row["password"])){
               $_SESSION["username"]=$username;
               echo "<script>" . "alert('Logged In! Welcome to EarBlast')" . "</script>";
               echo "<script>" . "window.location.href='index.php'" . "</script>";
           }
           else{
               /*if username exists but wrong password was provided */
               array_push($errors, "Wrong password");
           }
       }
   }
   else{
       /*If no rows found with that username*/
       array_push($errors, "Wrong username");
   }
}

/*Log out -> destroy sessions*/
if(isset($_POST["logOutBtn"])){
    $_SESSION["username"]="";
    $_SESSION['subscription'] = "";
    session_destroy();
}








