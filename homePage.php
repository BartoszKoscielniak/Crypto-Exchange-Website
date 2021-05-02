<?php
session_start();

if ((isset($_SESSION['isLoggedIn'])) && ($_SESSION['isLoggedIn'] == true)) {
    header('Location: userProfile.php');
    exit();
}else if ((isset($_POST['name'])) && (isset($_POST['surname'])) && (isset($_POST['emailRegister'])) && (isset($_POST['phone_number']))
    && (isset($_POST['password1'])) && (isset($_POST['password2']))) {
    $validationCompleted = true;
    $_SESSION['popup_status'] = "block";
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $emailRegister = $_POST['emailRegister'];
    $emailRegisterSafety = filter_var($emailRegister, FILTER_SANITIZE_EMAIL);
    $phone_number = $_POST['phone_number'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    if (strlen($name) < 3 || strlen($name) > 20) {
        $validationCompleted = false;
        $_SESSION['err_name'] = '<span style = "color:#ff0000">Name must contain 3 to 20 letters</span><br>';
    }

    if (ctype_alpha($name) == false) {
        $validationCompleted = false;
        $_SESSION['err_name'] = '<span style = "color:#ff0000">Name must contain only letters</span><br>';
    }

    if (strlen($surname) < 3 || strlen($surname) > 30) {
        $validationCompleted = false;
        $_SESSION['err_surname'] = '<span style = "color:#ff0000">Surname must contain 3 to 30 letters</span><br>';
    }

    if (ctype_alpha($surname) == false) {
        $validationCompleted = false;
        $_SESSION['err_surname'] = '<span style = "color:#ff0000">Surname must contain only letters</span><br>';
    }

    if (filter_var($emailRegisterSafety, FILTER_VALIDATE_EMAIL) == false || $emailRegisterSafety != $emailRegister) {
        $validationCompleted = false;
        $_SESSION['err_email'] = '<span style = "color:#ff0000">Please type in correct e-mail</span><br>';
    }

    if (strlen($password1) < 8 || strlen($password1) > 30) {
        $validationCompleted = false;
        $_SESSION['err_psswd'] = '<span style = "color:#ff0000">Password must contain 8 to 30 letters</span><br>';
    }

    if ($password1 != $password2) {
        $validationCompleted = false;
        $_SESSION['err_psswd'] = '<span style = "color:#ff0000">Password are not identical</span><br>';
    }

    $password1_hash = password_hash($password1, PASSWORD_DEFAULT);

    if (!isset($_POST['regulations'])) {
        $validationCompleted = false;
        $_SESSION['err_regulations'] = '<span style = "color:#ff0000">I am over 18 age, and I agree to the Terms</span><br>';
    }

    if (!is_numeric($phone_number)) {
        $validationCompleted = false;
        $_SESSION['err_phone'] = '<span style = "color:#ff0000">Phone number contain only numbers</span><br>';
    }

    $_SESSION['RF_name'] = $name;
    $_SESSION['RF_surname'] = $surname;
    $_SESSION['RF_emailRegistration'] = $emailRegister;
    $_SESSION['RF_phone_number'] = $phone_number;
    $_SESSION['RF_password1'] = $password1;
    $_SESSION['RF_password2'] = $password2;
    if (isset($_POST['regulations'])) $_SESSION['RF_regulations'] = true;


    require_once "dataBaseConnector.php";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_error());
        }else {
            //whether that email exist in db
            $result = $connection->query("SELECT id_użytkownika FROM użytkownicy WHERE adres_email = '$emailRegister'");

            if (!$result) throw new Exception($connection->error);

            if ($result->num_rows > 0) {
                $validationCompleted = false;
                $_SESSION['err_email'] = '<span style = "color:#ff0000">User with that e-mail already exist</span><br>';
            }
            //whether that phone number exist in db
            $result = $connection->query("SELECT id_użytkownika FROM użytkownicy WHERE nr_telefonu = '$phone_number'");

            if (!$result) throw new Exception($connection->error);

            if ($result->num_rows > 0) {
                $validationCompleted = false;
                $_SESSION['err_phone'] = '<span style = "color:#ff0000">User with that phone number already exist</span><br>';
            }

            if ($validationCompleted == true) {

                $result = $connection->query("SELECT MAX(id_użytkownika) FROM użytkownicy");
                $row = $result->fetch_assoc();
                if($row['MAX(id_użytkownika)'] == NULL) $row['MAX(id_użytkownika)'] = 0;
                $row['MAX(id_użytkownika)'] = $row['MAX(id_użytkownika)'] + 1;
                $_SESSION['id_uzyt'] = $row['MAX(id_użytkownika)'];

                if ($connection->query("INSERT INTO użytkownicy VALUES (".$_SESSION['id_uzyt'].",'$name','$surname','$phone_number','$emailRegister','$password1_hash')")) {
                    $result -> free();

                    $result = $connection->query("SELECT MAX(id_portfela) FROM portfele");
                    $row = $result->fetch_assoc();
                    if($row['MAX(id_portfela)'] == NULL) $row['MAX(id_portfela)'] = 0;
                    $row['MAX(id_portfela)'] = $row['MAX(id_portfela)'] + 1;
                    $connection->query("INSERT INTO portfele VALUES (".$row['MAX(id_portfela)'].",".$_SESSION['id_uzyt'].",0)");

                    $_SESSION['registrationSuccessful'] = true;
                    $_SESSION['emailToLogIn'] = $emailRegister;
                    $_SESSION['passwordToLogIn'] = $password1;
                    unset($_SESSION['RF_regulations']);
                    unset($_SESSION['RF_password2']);
                    unset($_SESSION['RF_password1']);
                    unset($_SESSION['RF_phone_number']);
                    unset($_SESSION['RF_emailRegistration']);
                    unset($_SESSION['RF_surname']);
                    unset($_SESSION['RF_name']);
                    unset($_SESSION['err_regulations']);
                    unset($_SESSION['err_psswd']);
                    unset($_SESSION['err_email']);
                    unset($_SESSION['err_name']);
                    unset($_SESSION['err_phone']);
                    unset($_SESSION['err_surname']);
                    $result -> free();
                    $connection->close();
                    header('Location: logIn.php');
                }else {
                    throw new Exception($connection->error);
                }
            }
            $result -> free();
            $connection->close();
        }
    } catch (Exception $exception) {
        echo "Error: " . $exception;
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Strona</title>
    <link rel="stylesheet" href="homePageStyle.css">
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

<!-- Menu -->

<ul>

    <li><a onclick="FormVis()" href="#log-popup">LOG IN</a></li>
    <li><a onclick="CreateAccountFormVis()" href="#create">CREATE ACCOUNT</a></li>
    <li style="float: left;"><a href="#contact">CONTACT</a></li>
    <li style="float: left;"><a href="#faq">FAQ</a></li>
    <li style="float: left"><a href="#about">ABOUT US</a></li>

</ul>

<div >
    <img id="bcg" src="img/background2.jpg">
    <!--<img style="position: absolute; left: 5%; top: 3%;" id="logo" src="img/logo_homepage.png"> -->
</div>

<!-- Login form -->

<div class="log-popup" id="form-div" style="visibility: hidden;">

    <form class="login-form" action="logIn.php" method=post>
        <h1 style="font-size: 20px;">Login</h1>
        <label class="napis"><b>Email</b></label><br>
        <input class="text" type="text" placeholder="Enter Email" name="email" required><br>
        <label class="napis"><b>Password</b></label><br>
        <input class="text" type="password" placeholder="Enter Password" name="password" required><br>
        <?php if (isset($_SESSION['error'])) echo $_SESSION['error']; ?>
        <button type="submit" class="btn" id="login">Login</button>
    </form>
    <button class="btn" id="close" onclick="FormVis()">Close</button>
</div>

<!-- New account form -->

<div class="new-account" id="create-account-form" style="display: none; position: fixed;">
    <div id="form-div2">
        <form method=post>
            <h1 style="font-size: 20px; user-select: none">Register<h1></h1>
                <label class="napis" style="user-select: none">Name</label><br>
                <input class="text" type="text" value="<?php if (isset($_SESSION['RF_name'])) { echo $_SESSION['RF_name'];unset($_SESSION['RF_name']);
                } ?>" placeholder="Enter Name" name="name" required/><br>
                <?php if (isset($_SESSION['err_name'])) {
                    echo $_SESSION['err_name'];
                    unset($_SESSION['err_name']);
                } ?>
                <label class="napis" style="user-select: none">Surname</label><br>
                <input class="text" type="text" value="<?php if (isset($_SESSION['RF_surname'])) { echo $_SESSION['RF_surname']; unset($_SESSION['RF_surname']);} ?>" placeholder="Enter Surname" name="surname" required><br>
                <?php if (isset($_SESSION['err_surname'])) {
                    echo $_SESSION['err_surname'];
                    unset($_SESSION['err_surname']);
                } ?>
                <label class="napis" style="user-select: none">Email</label><br>
                <input class="text" type="text" value="<?php if (isset($_SESSION['RF_emailRegistration'])) { echo $_SESSION['RF_emailRegistration']; unset($_SESSION['RF_emailRegistration']);} ?>" placeholder="Enter Email" name="emailRegister" required><br>
                <?php if (isset($_SESSION['err_email'])) {
                    echo $_SESSION['err_email'];
                    unset($_SESSION['err_email']);
                } ?>
                <label class="napis" style="user-select: none">Phone Number</label><br>
                <input class="text" type="text" value="<?php if (isset($_SESSION['RF_phone_number'])) { echo $_SESSION['RF_phone_number']; unset($_SESSION['RF_phone_number']);} ?>" placeholder="Enter Phone Number" name="phone_number" required><br>
                <?php if (isset($_SESSION['err_phone'])) {
                    echo $_SESSION['err_phone'];
                    unset($_SESSION['err_phone']);
                } ?>
                <label class="napis" style="user-select: none">Password</label><br>
                <input class="text" type="password" value="<?php if (isset($_SESSION['RF_password1'])) { echo $_SESSION['RF_password1']; unset($_SESSION['RF_password1']); } ?>" placeholder="Enter Password" name="password1" required><br>
                <?php if (isset($_SESSION['err_psswd'])) {
                    echo $_SESSION['err_psswd'];
                    unset($_SESSION['err_psswd']);
                } ?>
                <label class="napis" style="user-select: none">Repeat password</label><br>
                <input class="text" type="password" value="<?php if (isset($_SESSION['RF_password2'])) {echo $_SESSION['RF_password2']; unset($_SESSION['RF_password2']); } ?>" placeholder="Repeat Password" name="password2" required><br>
                <label style="user-select: none">
                    <input type="checkbox" name="regulations" value="<?php if (isset($_SESSION['RF_regulations'])) { echo "checked"; unset($_SESSION['RF_regulations']); } ?>"/> I have read and agree to the Terms of Service.<br>
                </label>
                <?php if (isset($_SESSION['err_regulations'])) {
                    echo $_SESSION['err_regulations'];
                    unset($_SESSION['err_regulations']);
                } ?>
                <button type="submit" class="btn" id="create">Create</button>

        </form>
        <button class="btn" onclick="CreateAccountFormVis()" id="close">Close</button>

    </div>

</div>

<div id="div-panels" style="background-color: #ffffff; ">
    <p>abc</p>
</div>

<div id="div-panels" style="background-color: #252379; color: #ffffff; ">
    <p>abc</p>
</div>

<!-- How to start -->
<div id="div-panels" style="background-color: #ffffff; ">
    <p>abc</p>
</div>

<!-- Frequently asked -->
<div id="faq" class="container-fluid">
<div id="div-panels" style="background-color: #8b54ae; color: #ffffff; ">
    <p>abc</p>
</div>

<!-- About us -->
<div id="about" class="container-fluid">
<div id="div-panels" style="background-color: #ffffff; ">
    <p>abc</p>
</div>

<!-- Contact/Fotter -->
<div id="contact" class="container-fluid">
<div id="div-fotter" style="background-color: #d586f1; color: #ffffff ">
    <img style=" " src="img/logo%20-%20white.png">
    <div style="border: 1px solid #d586f1; border-bottom-color: antiquewhite; width: 100%;">

    </div>

</div>



</body>
<script src="homePageScript.js"></script>
</html>