<?php
    session_start();

    if((isset($_SESSION['isLoggedIn'])) && ($_SESSION['isLoggedIn'] == true)){
        header('Location: userProfile.php');
        exit();
    }
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Strona</title>
<link rel="stylesheet" href="homePageStyle.css">
</head>
<body>

  <!-- Menu -->

    <ul>
        
        <li><a onclick="FormVis()" href="#log-popup">LOG IN</a></li>
        <li><a onclick="CreateAccountFormVis()" href="#crate">CREATE ACCOUNT</a></li>
        <li style="float: left"><a href="#about">ABOUT US</a></li>
        <li style="float: left;"><a href="#contact">CONTACT</a></li>
        
    </ul>

    <div style="position: relative;">
      <img id="bcg" src="img/background2.jpg">
      <img style="position: absolute; left: 5%; top: 3%;" id="logo" src="img/logo_homepage.png">
    </div>
    

    <!-- Login form -->

      <div class="log-popup"id="form-div" style="visibility: hidden;">

        <form class="login-form" action="logIn.php" method= post>
            <h1 style="font-size: 20px;">Login</h1>
            <label class="napis"><b>Email</b></label><br>
            <input class="text" type="text"  placeholder="Enter Email" name="email" required><br>
            <label class="napis"><b>Password</b></label><br>
            <input class="text" type="password" placeholder="Enter Password" name="password" required><br>
            <?php if(isset($_SESSION['error'])) echo $_SESSION['error']; ?>
            <button type="submit" class="btn" id="login">Login</button>
        </form>
          <button class="btn" id="close" onclick="FormVis()">Close</button>
      </div>

      <!-- New account form -->

      <div class="new-account" id="create-account-form" style="display: none; position: fixed;">
        <div id="form-div2">
          <form action="createAcc.php" method = post>

            <h1 style="font-size: 20px;">Register<h1></h1>
            <label class="napis">Name</label><br>
            <input class="text" type="text" placeholder="Enter Name" name="name"><br>
            <label class="napis">Surname</label><br>
            <input class="text" type="text" placeholder="Enter Surname" name="surname"><br>
            <label class="napis">Email</label><br>
            <input class="text" type="text" placeholder="Enter Email" name="email"><br>
            <label class="napis">Phone Number</label><br>
            <input class="text" type="text" placeholder="Enter Phone Number" name="phone_number"><br>
            <label class="napis">Password</label><br>
            <input class="text" type="text" placeholder="Enter Password"name="password1"><br>
            <label class="napis">Repeat password</label><br>
            <input class="text" type="text" placeholder="Repeat Password" name="password2"><br>
            <button type="submit" class="btn" id="create">Create</button>

          </form>
            <button class="btn" onclick="CreateAccountFormVis()" id="close">Close</button>

        </div>
      
      </div>

</body>
<script src="homePageScript.js"></script>></script>
</html>