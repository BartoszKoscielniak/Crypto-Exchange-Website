<?php

    require_once "dataBaseConnector.php";
    session_start();

    if(!isset($_POST['amount']) || !isset($_POST['sell'])){
        echo "Xd";
        header("Location:userProfile.php");
        exit();
    }

    mysqli_report(MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    for ($a = 0; $a < sizeof($_SESSION['lista_walut']); $a++){
        for ($b = 0; $b < sizeof($_SESSION['krypto']); $b++) {
            if ($_SESSION['lista_walut'][$a][2] == $_POST['sell'] && $_POST['sell'] == $_SESSION['krypto'][$b][0]) {
                if ($_POST['amount'] <= $_SESSION['lista_walut'][$a][3]) {
                    if ($connection->connect_errno != 0) {
                        throw new Exception(mysqli_connect_error());
                    }else {
                        $connection->query("UPDATE lista_walut SET ilość_krypto = '" . ($_SESSION['lista_walut'][$a][3] - $_POST['amount']) . "' WHERE id_krypto = '" . $_POST['sell'] . "' AND id_portfela = '" . $_SESSION['portfel'][0][0] . "'");
                        $connection->query("UPDATE portfele SET ilość_euro = '" . ($_SESSION['portfel'][0][3] + ($_POST['amount'] * $_SESSION['krypto'][$b][2])) . "' WHERE id_portfela = '" . $_SESSION['portfel'][0][0] . "'");
                        $connection->close();
                    }
                    header('Location: userProfile.php');
                }else {
                    $_SESSION['err_fund2'] = '<span style = "color:#ff0000">You have not enough assets</span><br>';
                    header('Location: userProfile.php');
                }
                break 2;
            }
        }
    }

?>
