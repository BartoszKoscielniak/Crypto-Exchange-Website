<?php
    session_start();

    if(!isset($_SESSION['isLoggedIn'])){
        header('Location: homePage.php');
        exit();
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile</title>
<link rel="stylesheet" href="userProfileStyle.css">
</head>
<body>

<!-- Menu -->

<ul>

    <li><img style="max-width: 100%; max-height: 100%;" src="img/logo.png"></li>
    <li><a onclick="switchPanel(document.getElementById('wallet').style)">Wallet</a></li>
    <li><a onclick="switchPanel(document.getElementById('exchange').style)">Exchange</a></li>
    <li><a onclick="switchPanel(document.getElementById('buy').style)">Buy crypto</a></li>
    <li><a>History</a></li>
    <li><a onclick="switchPanel(document.getElementById('account').style)">My account</a></li>
</ul>

<div id="top-bar">
    <p style="width: auto; padding: 5px; position: absolute; right:1%"><?php echo $_SESSION['imię'] ?></p>
    <input type="image" id="myimage" style="height:50px; width:50px; position:absolute; right:5%" src="img/user.png" onclick="FormVis()" href="#log-popup"/>
    <button style="position:absolute; right:9%; padding: 5px ">Send/Receive</button>
    <button style="position:absolute; right:15%; padding: 5px">Buy/Sell</button>
    <p style="position:absolute; left: 0%">CRYPTOEXCH - Main Page</p>
</div>

<!-- Wallet -->

<div id="wallet">

    <h4 style="text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black; font-weight: bold; color: rgb(51, 196, 129); margin-right: 80%; font-size: 40px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">Wallet</h4>

    <hr style="border: 1px solid #000; margin-right: 3%; margin-left: 3%;"><br>

    <div id="dollars">
        <img style="float: right;" src="img/dollar.png">
        <a>0</a>
    </div>
    
    <table id="wallet-table">
        
        <tr style="background-color: rgb(51, 196, 129); color: #000; "><th class="rank">Rank</th><th>Logo</th><th>Crypto</th><th>Balance</th></tr>
        <tr><th class="rank">1.</th><th style="width: 100px;"><img src="img/btc.png" ></th><th>BTC</th><th>0</th></tr>
        <tr><th class="rank">2.</th><th style="width: 100px;"><img src="img/ethereum.png"></th><th>Ethereum</th><th>0</th></tr>
        <tr><th class="rank">3.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">4.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">5.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">6.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">7.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">8.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">9.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">10.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">11.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">12.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">13.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">14.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
        <tr><th class="rank">15.</th><th style="width: 100px;"><img src="img/eos.png"></th><th>Eos</th><th>0</th></tr>
    </table>

</div>

<!-- Exchange -->

<div id="exchange">

    <h4 style="text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black; font-weight: bold; color: rgb(51, 196, 129); margin-right: 80%; font-size: 40px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">Exchange</h4>

    <hr style="border: 1px solid #000; margin-right: 3%; margin-left: 3%;"><br>

    <div id="euro">
        <img src="img/euro.png">
        <input style="text-align: center;" type="input" class="form__field" placeholder="Euro" required>
    </div>

    <img id="arrow" src="img/arrow.png">

    <div id="crypto">
        <img src="img/bitcoin.png">
        <input style="text-align: center;" type="input" class="form__field" placeholder="Bitcoin" required>
    </div>
    <br><br>
    <input id="submit" type="button" value="Submit"><br><br>
    
</div>

<!-- Buy crypto -->

<div id="buy">

    <h4 style="text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black; font-weight: bold; color: rgb(51, 196, 129); margin-right: 80%; font-size: 40px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">Buy crypto</h4>

    <hr style="border: 1px solid #000; margin-right: 3%; margin-left: 3%;"><br>

    <div id="euro">
        <img src="img/euro.png">
        <input style="text-align: center;" type="input" class="form__field" placeholder="Euro" required>
    </div>

    <img id="arrow" src="img/arrow.png">

    <div id="crypto">
        <img src="img/bitcoin.png">
        <input style="text-align: center;" type="input" class="form__field" placeholder="Bitcoin" required>
    </div>
    <br><br>
    <input id="submit" type="button" value="Submit"><br><br>
    
</div>

<!-- History -->

<div>

</div>

<!-- My account -->

<div id="account" style="display: none;">
    <h4 style="text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black; font-weight: bold; color: rgb(51, 196, 129); margin-right: 80%; font-size: 40px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">My account</h4>

    <img style="float: left; margin-left: 2%;" src="img/user.png">
    <hr style="border: 1px solid #000; margin-right: 3%"><br><br><br>
    <a style="float: left; margin-left: 3%; font-size: 18px;">Username: <?php echo $_SESSION['imię'], " ", $_SESSION['nazwisko'] ?></a><a style="float: left; margin-left: 7%; font-size: 18px;"></a><br><br>
    <a style="float: left; margin-left: 3%; font-size: 18px;">Email: <?php echo $_SESSION['adres_email'] ?></a><a style=" float: left; margin-left: 7%; font-size: 18px;"></a><br><br><br><br><br><br><br><br><br>
    <img style="float: left; margin-left: 15px ; margin-right: 10px;" src="img/facebook-logo.png">
    <a style="float: left;">Connect with Facebook</a><br><br>
    <img style="float: left; margin-left: 15px ; margin-right: 10px;" src="img/google-logo.png"><a style="float: left;">Connect with Google</a>
    
</div>

<div class="log-popup" id="form-div" style="visibility: hidden;">
<!--
    <form class="login-form" action="logIn.php" method=post>
        <h1 style="font-size: 20px;">Login</h1>
        <label class="napis"><b>Email</b></label><br>
        <input class="text" type="text" placeholder="Enter Email" name="email" required><br>
        <label class="napis"><b>Password</b></label><br>
        <input class="text" type="password" placeholder="Enter Password" name="password" required><br>
        <?php if (isset($_SESSION['error'])) echo $_SESSION['error']; ?>
        <button type="submit" class="btn" id="login">Login</button>
    </form>
-->
    <form action="logOut.php">
        <button type="submit" class="btn" id="close">Log Out</button>
    </form>
</div>

<!-- Main screen -->

<div id="main-screen">

    <div style="background-color:mediumpurple; height:350px; width:700px ">

    </div>

</div>

</body>
<script src="homePageScript.js"></script>
<script src="userProfileScript.js"></script>
</html>