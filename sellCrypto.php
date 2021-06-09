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

    $ch = curl_init();
    $url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=eur&order=market_cap_desc&per_page=100&page=1&sparkline=false";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    mysqli_report(MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    //wpisanie krytpto do bazy/aktualizacja ceny
    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($response, true);
    }
    curl_close($ch);

echo $_POST['sell'];
    if ($_POST['sell'] >= 1000){
        $_POST['sell'] = $_POST['sell']/1000;
        for ($xdx = 0; $xdx < sizeof($decoded); $xdx++){
            if ($_POST['sell'] == $decoded[$xdx]['market_cap_rank']){
                for ($xdxx = 0; $xdxx < sizeof($_SESSION['krypto']); $xdxx++) {
                    if ($decoded[$xdx]['name'] == $_SESSION['krypto'][$xdxx][1]){
                        $_POST['sell'] = $_SESSION['krypto'][$xdxx][0];
                        echo " po zmianie".$_POST['sell'];
                        break 2;
                    }
                }
            }
        }
    }

    mysqli_report(MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    for ($a = 0; $a < sizeof($_SESSION['lista_walut']); $a++) {
        if ($_SESSION['lista_walut'][$a][2] == $_POST['sell'] ){
        for ($b = 0; $b < sizeof($_SESSION['krypto']); $b++) {
            if ($_POST['sell'] == $_SESSION['krypto'][$b][0]) {
                if ($_POST['amount'] <= $_SESSION['lista_walut'][$a][3]) {
                    if ($connection->connect_errno != 0) {
                        throw new Exception(mysqli_connect_error());
                    }else {
                        $connection->query("UPDATE lista_walut SET ilość_krypto = '" . ($_SESSION['lista_walut'][$a][3] - (float)$_POST['amount']) . "' WHERE id_krypto = '" . $_POST['sell'] . "' AND id_portfela = '" . $_SESSION['portfel'][0][0] . "'");
                        $connection->query("UPDATE portfele SET ilość_euro = '" . ($_SESSION['portfel'][0][2] + ((float)$_POST['amount'] * $_SESSION['krypto'][$b][2])) . "' WHERE id_portfela = '" . $_SESSION['portfel'][0][0] . "'");
                        $connection->query("INSERT INTO transakcje (id_krypto,id_portfela,data_transakcji,czas_zawarcia,ilosc,status,kurs_transakcji) VALUES ('" . $_POST['sell'] . "','" . $_SESSION['portfel'][0][0] . "','" . date("Y-m-d") . "','" . date("H:i") . "','" . $_POST['amount'] . "','" . "SOLD" . "','" . $_SESSION['krypto'][$b][2] . "')");
                        $connection->close();
                    }
                    header('Location:' . $fileName);
                }else {
                    $_SESSION['err_fund2'] = '<div class="alert alert-danger" role="alert">
                                              You have no assets to sell!
                                              </div>';
                    header('Location:' . $fileName);
                }
                break 2;
            }
        }
        }
    }
?>