<?php
    require_once "dataBaseConnector.php";
    session_start();

    if(!isset($_POST['amount']) || !isset($_POST['buy']) || !isset($_POST['pay'])){
        header("Location:userProfile.php");
        exit();
    }

    if ($_POST['pay'] == 'myWallet'){
        for ($i = 0; $i < sizeof($_SESSION['krypto']); $i++){
            if ($_POST['buy'] == $_SESSION['krypto'][$i][1]){
                $toPay = $_POST['amount'] * $_SESSION['krypto'][$i][2];
                break;
            }
        }
        if ($toPay <= $_SESSION['portfel'][0][2]){
            echo "Zakupiono walletem";
        }else{
            echo "Nie wystarczylo";
            $_SESSION['err_fund'] = '<span style = "color:#ff0000">You have not enough money</span><br>';
        }
    }else{
        for ($i = 0; $i < sizeof($_SESSION['krypto']); $i++) {
            if ($_POST['pay'] == $_SESSION['krypto'][$i][1]) {
                for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                    if ($_POST['buy'] == $_SESSION['krypto'][$a][1]){
                        $exRatio = $_SESSION['krypto'][$i][2]/$_SESSION['krypto'][$a][2];
                        $toPay = $_POST['amount']/$exRatio;
                        for ($b = 0; $b < sizeof($_SESSION['lista_walut']); $b++){
                            if($_SESSION['lista_walut'][$b][2] == $_SESSION['krypto'][$i][0]){
                                if ($_SESSION['lista_walut'][$b][3] >= $toPay){
                                    echo "Zakupiono krypto";
                                    break 3;
                                }else{
                                    echo "Nie zakupiono krypto";
                                    $_SESSION['err_fund'] = '<span style = "color:#ff0000">You have not enough assets</span><br>';
                                    break 3;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    //TODO: Odnoszenie do okienka zakupu jesli blad/odnoszenie do userProfile
?>
