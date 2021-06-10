<?php
session_start();

if ((isset($_SESSION['isLoggedIn'])) && ($_SESSION['isLoggedIn'] == true)) {
    header('Location:home.php');
    exit();
} else if ((isset($_POST['name'])) && (isset($_POST['surname'])) && (isset($_POST['emailRegister'])) && (isset($_POST['phone_number']))
    && (isset($_POST['password1'])) && (isset($_POST['password2']))
) {
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
        } else {
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
                if ($row['MAX(id_użytkownika)'] == NULL) $row['MAX(id_użytkownika)'] = 0;
                $row['MAX(id_użytkownika)'] = $row['MAX(id_użytkownika)'] + 1;
                $_SESSION['id_uzyt'] = $row['MAX(id_użytkownika)'];

                if ($connection->query("INSERT INTO użytkownicy VALUES (" . $_SESSION['id_uzyt'] . ",'$name','$surname','$phone_number','$emailRegister','$password1_hash')")) {
                    $result->free();

                    $result = $connection->query("SELECT MAX(id_portfela) FROM portfele");
                    $row = $result->fetch_assoc();
                    if ($row['MAX(id_portfela)'] == NULL) $row['MAX(id_portfela)'] = 0;
                    $row['MAX(id_portfela)'] = $row['MAX(id_portfela)'] + 1;
                    $connection->query("INSERT INTO portfele VALUES (" . $row['MAX(id_portfela)'] . "," . $_SESSION['id_uzyt'] . ",0)");

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
                    $result->free();
                    $connection->close();
                    header('Location: logIn.php');
                } else {
                    throw new Exception($connection->error);
                }
            }
            $result->free();
            $connection->close();
        }
    } catch (Exception $exception) {
        echo "Error: " . $exception;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Octopus Exchange</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="homePageStyle.css">
</head>

<body>

    <nav class="navbar navbar-expand-md navbar-dark" style="width: 100%;position:fixed; overflow: hidden; top:0; background-color:transparent;">

        <img src="img/oct.png" width="50px" height="40px">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item" style="margin-left:5%">
                    <button style="width:80px" type="button" class="btn btn-outline-success" data-target="#myModal" data-toggle="modal">Join</button>
                </li>
                <li class="nav-item" style="margin-left:5%">
                    <button style="width:80px" type="button" class="btn btn-outline-danger" data-target="#myMod" data-toggle="modal">Log In</button>
                </li>


            </ul>
        </div>
    </nav>
    <br>



    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog cascading-modal" role="document">
            <!--Content-->
            <div class="modal-content">
                <div class="modal-body mb-1">

                    <form method="post">
                        <h2>Register</h2>

                        <label class="napis" style="user-select: none">Name</label><br>
                        <input class="form-control" class="text" type="text" value="<?php if (isset($_SESSION['RF_name'])) {
                                                                                        echo $_SESSION['RF_name'];
                                                                                        unset($_SESSION['RF_name']);
                                                                                    } ?>" placeholder="Enter Name" name="name" required /><br>
                        <?php if (isset($_SESSION['err_name'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['err_name'] . '</div>';
                            unset($_SESSION['err_name']);
                        } ?>
                        <label class="napis" style="user-select: none">Surname</label><br>
                        <input class="form-control" class="text" type="text" value="<?php if (isset($_SESSION['RF_surname'])) {
                                                                                        echo $_SESSION['RF_surname'];
                                                                                        unset($_SESSION['RF_surname']);
                                                                                    } ?>" placeholder="Enter Surname" name="surname" required><br>
                        <?php if (isset($_SESSION['err_surname'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['err_surname'] . '</div>';
                            unset($_SESSION['err_surname']);
                        } ?>
                        <label class="napis" style="user-select: none">Email</label><br>
                        <input class="form-control" class="text" type="text" value="<?php if (isset($_SESSION['RF_emailRegistration'])) {
                                                                                        echo $_SESSION['RF_emailRegistration'];
                                                                                        unset($_SESSION['RF_emailRegistration']);
                                                                                    } ?>" placeholder="Enter Email" name="emailRegister" required><br>
                        <?php if (isset($_SESSION['err_email'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['err_email'] . '</div>';
                            unset($_SESSION['err_email']);
                        } ?>
                        <label class="napis" style="user-select: none">Phone Number</label><br>
                        <input class="form-control" style="margin-bottom:0%;" class="text" type="text" value="<?php if (isset($_SESSION['RF_phone_number'])) {
                                                                                                                    echo $_SESSION['RF_phone_number'];
                                                                                                                    unset($_SESSION['RF_phone_number']);
                                                                                                                } ?>" placeholder="Enter Phone Number" name="phone_number" required><br>
                        <?php if (isset($_SESSION['err_phone'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['err_phone'] . '</div>';
                            unset($_SESSION['err_phone']);
                        } ?>
                        <label class="napis" style="user-select: none">Password</label><br>




                        <input class="form-control" class="text" type="password" value="<?php if (isset($_SESSION['RF_password1'])) {
                                                                                            echo $_SESSION['RF_password1'];
                                                                                            unset($_SESSION['RF_password1']);
                                                                                        } ?>" placeholder="Enter Password" name="password1" required><br>


                        <?php if (isset($_SESSION['err_psswd'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['err_psswd'] . '</div>';
                            unset($_SESSION['err_psswd']);
                        } ?>


                        <label class="napis" style="user-select: none">Repeat password</label><br>
                        <input class="form-control" class="text" type="password" value="<?php if (isset($_SESSION['RF_password2'])) {
                                                                                            echo $_SESSION['RF_password2'];
                                                                                            unset($_SESSION['RF_password2']);
                                                                                        } ?>" placeholder="Repeat Password" name="password2" required><br>
                        <label style="user-select: none">
                            <input type="checkbox" name="regulations" value="<?php if (isset($_SESSION['RF_regulations'])) {
                                                                                    echo "checked";
                                                                                    unset($_SESSION['RF_regulations']);
                                                                                } ?>" /> I have read and agree to the Terms of Service.<br>
                        </label>
                        <?php if (isset($_SESSION['err_regulations'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['err_regulations'] . '</div>';
                            unset($_SESSION['err_regulations']);
                        } ?>

                        <br>

                        <!--Footer-->
                        <div class="modal-footer">
                            <img src="img/oct.png" width="50px" height="40px">

                            <button style="width:70px" type="submit" class="btn btn-outline-success">Join</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <div id="myMod" class="modal fade">
        <div class="modal-dialog modal-login">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Login</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="logIn.php" method="post">

                        <input class="form-control" class="text" type="text" placeholder="Enter Email" name="email" required><br>

                        <input class="form-control" class="text" type="password" placeholder="Enter Password" name="password" required><br>


                        <?php if (isset($_SESSION['error'])) echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>'; ?>


                        <!--Footer-->
                        <div class="modal-footer">
                            <img src="img/oct.png" width="50px" height="40px">
                            <button type="submit" class="btn btn-outline-danger" id="login">Login</button>
                        </div>

                        <!-- <button type="submit" class="btn" id="login">Login</button> -->
                    </form>

                </div>
            </div>
        </div>
    </div>

    <section>

        <img src="img/g5.png" style="width:100%; height:70%px; margin-top: -15%;">

    </section>
    <section>

        <img src="img/g12.png" style="width:100%; ">

    </section>

    <section>

        <img src="img/g6.png" style="width:100%; margin-top:-10%">

    </section>
    <section style="background-color: #525f7f; width:100%; height: 120px">
        <div style="text-align: center;">
        <img src="img/oct.png" width="80px" height="60px" style="margin: 1%;">
        <span style=" color:#fff; font-size: 36px; font-weight: bold; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">Octopus</span>
        </div>

    </section>

</body>

</html>