<?php
    require_once "dataBaseConnector.php";
    session_start();

    $fileName = $_POST['area'];
    date_default_timezone_set('Europe/Warsaw');

    if ($_POST['amount'] == null || !isset($_POST['sell'])) {
        echo "Xd";
        header("Location:".$fileName);
        exit();
    }

    mysqli_report(MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    for ($a = 0; $a < sizeof($_SESSION['lista_walut']); $a++) {
        for ($b = 0; $b < sizeof($_SESSION['krypto']); $b++) {
            if ($_SESSION['lista_walut'][$a][2] == $_POST['sell'] && $_POST['sell'] == $_SESSION['krypto'][$b][0]) {
                if ($_POST['amount'] <= $_SESSION['lista_walut'][$a][3]) {
                    if ($connection->connect_errno != 0) {
                        throw new Exception(mysqli_connect_error());
                    } else {
                        $connection->query("UPDATE lista_walut SET ilość_krypto = '" . ($_SESSION['lista_walut'][$a][3] - (int)$_POST['amount']) . "' WHERE id_krypto = '" . $_POST['sell'] . "' AND id_portfela = '" . $_SESSION['portfel'][0][0] . "'");
                        $connection->query("UPDATE portfele SET ilość_euro = '" . ($_SESSION['portfel'][0][2] + ((int)$_POST['amount'] * $_SESSION['krypto'][$b][2])) . "' WHERE id_portfela = '" . $_SESSION['portfel'][0][0] . "'");
                        $connection->query("INSERT INTO transakcje (id_krypto,id_portfela,data_transakcji,czas_zawarcia,ilosc,status,kurs_transakcji) VALUES ('" . $_POST['sell'] . "','" . $_SESSION['portfel'][0][0] . "','" . date("Y-m-d") . "','" . date("H:i") . "','" . $_POST['amount'] . "','" . "SOLD" . "','" . $_SESSION['krypto'][$b][2] . "')");
                        $connection->close();
                    }
                    header('Location:'.$fileName);
                } else {
                    $_SESSION['err_fund2'] = '<div class="alert alert-danger" role="alert">
                                              You have no assets to sell!
                                              </div>';
                    header('Location:'.$fileName);
                }
                break 2;
            }
        }
    }
?>