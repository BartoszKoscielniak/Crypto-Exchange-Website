<?php
session_start();
require_once "dataBaseConnector.php";

if (!isset($_SESSION['isLoggedIn'])) {
    header('Location: homePage.php');
    exit();
}
//pobranie informacji o krypto
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

    if ($connection->connect_errno != 0) {
        throw new Exception(mysqli_connect_error());
    } else {
        for ($i = 0; $i < sizeof($decoded); $i++) {

            $result = $connection->query("SELECT COUNT(*) FROM kryptowaluty WHERE nazwa = '" . $decoded[$i]['name'] . "'");
            $row = $result->fetch_assoc();
            $result->free();

            $result = $connection->query("SELECT MAX(id_krypto) FROM kryptowaluty");
            $max = $result->fetch_assoc();
            $result->free();

            if ($row['COUNT(*)'] == 0) {
                $connection->query("INSERT INTO kryptowaluty VALUES (" . ($max['MAX(id_krypto)'] + 1) . ",'" . $decoded[$i]['name'] . "','" . $decoded[$i]['current_price'] . "')");
            } else {
                $connection->query("UPDATE kryptowaluty SET kurs = '" . $decoded[$i]['current_price'] . "' WHERE nazwa = '" . $decoded[$i]['name'] . "'");
            }
        }
    }
}
curl_close($ch);


//sprawdzanie ktore krypto posiadamy
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_error());
} else {
    $result = $connection->query("SELECT * FROM portfele WHERE id_użytkownika = '" . $_SESSION['id_użytkownika'] . "'");
    $_SESSION['portfel'] = $result->fetch_all();
    $result->free();

    $result = $connection->query("SELECT * FROM lista_walut WHERE id_portfela = '" . $_SESSION['portfel'][0][0] . "'");
    $_SESSION['lista_walut'] = $result->fetch_all();
    $result->free();
}

//pobranie listy krypto dostepnej w bazie
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_error());
} else {
    $result = $connection->query("SELECT * FROM kryptowaluty ");
    $_SESSION['krypto'] = $result->fetch_all();
    $result->free();
}

//pobranie informacji o transakcjach
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_error());
} else {
    $sql = "SELECT k.nazwa as name, t.data_transakcji as date, t.czas_zawarcia as time, t.ilosc as amount, t.status as stat, kurs_transakcji as course FROM 
    kryptowaluty k, transakcje t WHERE t.id_krypto=k.id_krypto AND t.id_portfela = '" . $_SESSION['portfel'][0][0] . "' ORDER BY data_transakcji DESC, czas_zawarcia DESC";

    $result = $connection->query($sql);
    $_SESSION['transakcje'] = $result->fetch_all();
    $result->free();
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">


    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Octopus Exchange</title>
    <!-- bootstrap 5 css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
    <!-- custom css -->
    <link rel="stylesheet" href="userProfileStyle.css">
</head>

<body>

    <nav class="navbar navbar-expand d-flex flex-column align-item-start" id="sidebar">
        <a href="#" class="navbar-brand text-light mt-5">

            <img src="img/oct.png" style="width: 240px; height: 170px; margin-left:3%" alt="niema">
        </a>
        <ul class="navbar-nav d-flex flex-column mt-5 w-100">
            <li class="nav-item w-100">
                <a href="home.php" class="nav-link text-light pl-4"><img src="img/home.png"> Home</a>
            </li>
            <li class="nav-item w-100">
                <a href="wallet.php" class="nav-link text-light pl-4"><img src="img/wallet.png"> Wallet</a>
            </li>
            <li class="nav-item w-100">
                <a href="buyOrSell.php" class="nav-link text-light pl-4"><img src="img/buy.png"> Buy/Sell</a>
            </li>

            <li class="nav-item w-100">
                <a href="history.php" class="nav-link text-light pl-4"><img src="img/history.png"> History</a>
            </li>
            <li class="nav-item w-100" data-toggle="modal" data-target="#myContactModal">
                <a href="#" class="nav-link text-light pl-4"><img src="img/post.png"> Contact Us</a>
            </li>
        </ul>
    </nav>

    <!-- History section -->

    <section id="wallet" class="p-4 my-container">
        <div style="display: inline">
            <script src="https://widgets.coingecko.com/coingecko-coin-price-marquee-widget.js"></script>
            <coingecko-coin-price-marquee-widget coin-ids="bitcoin,ethereum,litecoin,ripple" currency="eur" background-color="#ffffff" locale="en"></coingecko-coin-price-marquee-widget>
            <h2>History of tearing</h2>

            <form action="/20-21-ai-projekt-lab3-projekt-ai-koscielniak-b-matusik-l/logOut.php">
                <button type="submit" class="btn btn-outline-danger" data-mdb-ripple-color="dark" style=" float:right; margin-right:10px">
                    Log Out
                </button>
                <button type="button" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark" style="float:right; margin-right:10px" data-target="#myModal" data-toggle="modal">
                    Buy/Sell
                </button>
                <h1 style=" float:right; margin-right:20px">
                    <?php
                    echo '<a>' . number_format($_SESSION['portfel'][0][2],2) . '</a>'
                    ?>
                    <a style="font-size: 20px;">€</a>
                </h1>

                <button data-target="#getMoneyModal" data-toggle="modal" style="float:right; border-color: rgb(71, 209, 71); background-color: rgb(71, 209, 71); margin-right: 10px; margin-top: 5px;" type="button" class="btn btn-success btn-sm btn-round"><img src="img/white-plus.png" style="position:absolute; top: 50%; left:50%; -webkit-transform: translate(-50%, -50%); -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><span class="glyphicon glyphicon-align-center"></span></button>

        </div>

        </form>

        </div>

        <div class="container">

            <!-- Trigger the modal with a button -->

            <!-- Modal -->
            <!--Modal: Login / Register Form-->
            <div class="modal fade" id="myContactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog cascading-modal" role="document">
                    <!--Content-->
                    <div class="modal-content">
                        <div class="modal-body mb-1">

                            <form>

                                <h2>Contact Us</h2>

                                <input name="amount" type="text" class="form-control" placeholder="Name"><br>
                                <input name="amount" type="text" class="form-control" placeholder="Email"><br>

                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="4" placeholder="Message"></textarea>

                                <!--Footer-->
                                <div class="modal-footer">
                                    <button style="width:70px" type="submit" class="btn btn-outline-success">Send</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">

            <!-- Trigger the modal with a button -->

            <!-- Modal -->
            <!--Modal: Get Money Label Form-->
            <div class="modal fade" id="getMoneyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog cascading-modal" role="document">
                    <!--Content-->
                    <div class="modal-content">

                        <!--Modal cascading tabs-->
                        <div class="modal-body mb-1" style="text-align: center;">


                            <label style="margin-left: 5%; margin-top: 5%; margin:5%" data-success="right" for="modalLRInput12" class="wallet-val">Enter how many euro you want to add:</label>

                            <form action="addMoney.php" method="post">

                                <div class="input-group mb-3">

                                    <input id="amountInput" style=" margin-left: 5%; margin-right: 2%" name="amountttt" type="text" class="form-control" onkeypress="return onlyNumberKey(event,'amountInput')" autocomplete="off">
                                    <h6 style="font-size: 20px;">€</h6><br>

                                </div>
                                <?php if (isset($_SESSION['euroAmount'])) {
                                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['euroAmount'] . '</div>';
                                    unset($_SESSION['euroAmount']);
                                } ?>

                                <?php if (isset($_SESSION['euroAmount2'])) {
                                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['euroAmount2'] . '</div>';
                                    unset($_SESSION['euroAmount2']);
                                } ?>


                                <label style=" margin-top: 5%;" data-success="right" for="modalLRInput12" class="wallet-val">Payment method</label>


                                <div class="payOpt " onclick="selectPayMet('payCard1')" style="cursor:pointer; margin: 5%; margin-left: 5%; margin-right:5%; border: 1px solid #ccc!important; text-align: left; border-radius: 5px;">
                                    <div style="display: inline-block; vertical-align: baseline; ">

                                        <input id="payCard1"  style="float: left; margin: 5%;" type="checkbox">
                                        <h6 style="float: left; margin-left: 10px;"><strong>BLIK</strong></h6><br>
                                        <img style="float: left; margin-left: 10px; margin-bottom: 5px;" src="img/blik.png" width="30px" height="18px">

                                    </div>
                                </div>

                                <div class="payOpt" onclick="selectPayMet('payCard2')" style=" display: block;cursor:pointer; margin: 5%; margin-left: 5%; margin-right:5%; border: 1px solid #ccc!important; text-align: left; border-radius: 5px;">
                                    <div class="cos" style="display: inline-block; vertical-align: baseline;">

                                        <input id="payCard2" style="float: left; margin: 5%;" type="checkbox">
                                        <h6 style="float: left; margin-left: 10px;"><strong>Credit card</strong></h6><br>
                                        <img style="float: left; margin-left: 10px; margin-bottom: 5px;" src="img/vm.png" width="90px" height="18px">

                                    </div>
                                </div>


                                <div id="creditCard" class="input-group mb-3" style="display:none;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                                    <h6 style="margin-left:5%;float:left">Card number:</h6>
                                    <input id="cardNumber" name="cardamount" type="text" maxlength="19" class="form-control" style="width: 90%; margin-left: 5%; margin-right:5%; text-align: center;" placeholder='xxxx-xxxx-xxxx-xxxx' onkeypress="return onlyNumberKey(event,'cardNumber')" autocomplete="off">

                                    <label style="float:left; margin: 1%" data-success="right" for="modalLRInput12" class="wallet-val"></label>

                                    <div style="margin-top: 5%;">
                                        <div style="float: left;">
                                            <h6 style="display: inline-block;">Expiration Date</h6>
                                            <input style="text-align: center;" id="cardDate" maxlength="7" name="dateamount" type="text" class="form-control" placeholder='mm/yyyy' onkeypress="return onlyNumberKey(event,'cardDate')" autocomplete="off">

                                        </div>
                                        <div style="float: right;">
                                            <h6 style="display: inline-block; text-align: center; ">CVV</h6>


                                            <input id="cvvNumber" name="cvvamount" type="text" maxlength='3' class="form-control" placeholder='123' onkeypress="return onlyNumberKey(event,'cvvNumber')" autocomplete="off">

                                        </div>
                                        <div><a>Powered by: </a> <img src="img/mastercard.png" width="40px" height="35px"></div>

                                    </div>

                                </div>

                                <div style="display:none;" id="blikk">

                                    <img src="img/blik.png"><br>
                                    <h6 style="display: inline-block;">Enter the 6-character code</h6>
                                    <input id="blikNumber" name="blikamount" type="text" class="form-control" maxlength="11" style="width:200px; margin-left:28% ;text-align: center;" placeholder='x-x-x-x-x-x' onkeypress="return onlyNumberKey(event,'blikNumber')" autocomplete="off"><br>

                                </div>

                                <!--Footer-->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-primary btn-rounded" onclick="startValue()" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-outline-success" onclick="startValue()">Pay</button>
                                    <textarea name="area" style="display:none">history.php</textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>

        <!--Modal: Login / Register Form-->
        </div>

        <div class="container">

            <!-- Trigger the modal with a button -->

            <!-- Modal -->
            <!--Modal: Login / Register Form-->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog cascading-modal" role="document">
                    <!--Content-->
                    <div class="modal-content">

                        <!--Modal cascading tabs-->
                        <div class="modal-c-tabs">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs md-tabs tabs-2 light-blue darken-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#panel7" role="tab">
                                        Buy</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel8" role="tab">
                                        Sell</a>
                                </li>
                            </ul>

                            <!-- Tab panels -->
                            <div class="tab-content">
                                <!--Buy tab-->
                                <div class="tab-pane fade in show active" id="panel7" role="tabpanel">

                                    <!--Body-->
                                    <div class="modal-body mb-1">
                                        <div class="md-form form-sm mb-5">

                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Your wallet</label>
                                            <h1 id="euro-amount">
                                                <?php
                                                echo '<a>' . number_format($_SESSION['portfel'][0][2],2) . '</a>'
                                                ?>
                                                <a style="font-size: 20px;">€</a>
                                            </h1>
                                        </div>

                                        <div class="md-form form-sm mb-4">
                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Buy:</label>

                                            <form action="buyCrypto.php" method="post" class="mb-3">
                                                <select name="buy" id="cryptoBuy" class="form-select" aria-label="Default select example" onclick="samePayBuyBlock(event)">

                                                    <?php
                                                    for ($i = 0; $i < 10; $i++) {
                                                        echo '<option value="' . htmlspecialchars($_SESSION['krypto'][$i][1]) . '" >' . $_SESSION['krypto'][$i][1] . '</option>' . "\n";
                                                    }
                                                    ?>

                                                </select>
                                                <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Pay:</label>
                                                <select name="pay" id="cryptoPay" class="form-select" aria-label="Default select example" onclick="samePayBuyBlock(event)">

                                                <option value="myWallet">My Wallet</option>
                                                <?php
                                                for ($i = 0; $i < sizeof($_SESSION['lista_walut']); $i++) {
                                                    for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                                                        if ($_SESSION['lista_walut'][$i][2] == $_SESSION['krypto'][$a][0] && $_SESSION['lista_walut'][$i][3] > 0.0001) {
                                                            echo '<option value="' . htmlspecialchars($_SESSION['krypto'][$a][1]) . '" >' . $_SESSION['krypto'][$a][1] . '(' . $_SESSION['lista_walut'][$i][3] . ')</option>' . "\n";
                                                            break;
                                                        }
                                                    }
                                                }

                                                ?>
                                            </select>
                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">How much?</label>
                                            <div class="input-group mb-3">
                                                <input id="amountInputBuy" name="amount" type="text" class="form-control" onkeypress="return onlyNumberKey(event)" autocomplete="off">
                                                <div class="input-group-append">
                                                    <button id="maxButtonBuy" class="btn btn-outline-primary" type="button" onclick="sendMaxBuy()">MAX</button>
                                                </div>
                                            </div>
                                            <?php if (isset($_SESSION['err_fund'])) {
                                                echo $_SESSION['err_fund'];
                                                unset($_SESSION['err_fund']);
                                            } ?>
                                            <textarea name="area" style="display:none">history.php</textarea>
                                            <button id="submitButton" type="submit" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark">Buy</button>
                                        </form>

                                        </div>
                                    </div>
                                    <!--Footer-->
                                    <div class="modal-footer">
                                        <p>Powered by</p>

                                        <img src="img/mastercard.png" width="60px" height="60px">
                                        <button type="button" class="btn btn-outline-primary btn-rounded" data-dismiss="modal" onclick="clearInput()">Close</button>
                                    </div>
                                </div>
                                <!--/.Buy tab-->

                                <!--Sell tab-->
                                <div class="tab-pane fade" id="panel8" role="tabpanel">

                                    <div class="modal-body">
                                        <div class="md-form form-sm mb-5">


                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Your wallet</label>
                                            <h1 id="euro-amount">
                                                <?php
                                                echo "<script>console.log('Debug Objects:');</script>";
                                                echo '<a>' . number_format($_SESSION['portfel'][0][2],2) . '</a>'
                                                ?>
                                                <a style="font-size: 20px;">€</a>
                                            </h1>

                                        </div>

                                        <div>

                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Sell</label>

                                            <form action="sellCrypto.php" method="post" class="mb-3">

                                                <select id="toSell" name="sell" class="form-select" aria-label="Default select example">

                                                <?php
                                                $temp = 0;
                                                for ($i = 0; $i < sizeof($_SESSION['lista_walut']); $i++) {
                                                    for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                                                        if ($_SESSION['lista_walut'][$i][2] == $_SESSION['krypto'][$a][0] && $_SESSION['lista_walut'][$i][3] > 0.0001) {
                                                            echo '<option id="'.$_SESSION['lista_walut'][$i][3].'" value="' . htmlspecialchars($_SESSION['krypto'][$a][0]) . '" >' . $_SESSION['krypto'][$a][1] . '(' . $_SESSION['lista_walut'][$i][3] . ')</option>' . "\n";
                                                            $temp += 1;
                                                            break;
                                                        }
                                                    }
                                                }
                                                if ($temp == 0) {
                                                    echo "No assets to sell";
                                                }
                                                ?>
                                            </select>

                                                <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">How much?</label>

                                                <div class="input-group mb-3">
                                                    <input id="amountInputSell" name="amount" type="text" class="form-control" onkeypress="return onlyNumberKey(event)" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button id="maxButton" class="btn btn-outline-primary" type="button" onclick="sendMax()">MAX</button>
                                                    </div>
                                                </div>

                                                <?php if (isset($_SESSION['err_fund2'])) {
                                                    echo $_SESSION['err_fund2'];
                                                    unset($_SESSION['err_fund2']);
                                                } ?>
                                                <textarea name="area" style="display:none">history.php</textarea>
                                                <button id="submitButtonSell" type="submit" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark">Sell</button>
                                            </form>
                                        </div>

                                </div>
                                <!--Footer-->
                                <div class="modal-footer">
                                    <p>Powered by</p>
                                    <img src="img/mastercard.png" width="60px" height="60px">
                                    <button type="button" class="btn btn-outline-primary btn-rounded" data-dismiss="modal" onclick="clearInput()">Close</button>
                                </div>
                            </div>
                            <!--/.Sell tab-->
                        </div>
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>
        <!--Modal: Login / Register Form-->
    </div>
    <button class="btn my-4" id="menu-btn">Menu</button>


        <div class="main-content">
            <div class="container mt-7">
                <!-- Table -->

                <div class="row">

                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <h3 class="mb-0">History</h3>
                            </div>
                            <div class="table-responsive">
                                <table id="historyTable" class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>

                                            <?php
                                            if (sizeof($_SESSION['lista_walut']) == 0){
                                                echo '<th scope="col" style="text-align: center">There is no history</th>';
                                            }else{
                                                echo '<th scope="col" style="text-align: center">Logo</th>
                                            <th scope="col" style="text-align: center">Name</th>
                                            <th scope="col" style="text-align: center">Date</th>
                                            <th scope="col" style="text-align: center">Time</th>
                                            <th scope="col" style="text-align: center">Amount</th>
                                            <th scope="col" style="text-align: center">Status</th>
                                            <th scope="col" style="text-align: center">Course</th>' ;
                                            }
                                            ?>


                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        for ($i = 0; $i < sizeof($_SESSION['transakcje']); $i++) {
                                            for ($k = 0; $k < sizeof($decoded); $k++) {
                                                if ($_SESSION['transakcje'][$i][0] == $decoded[$k]['name']) {
                                                    if ($_SESSION['transakcje'][$i][4] == 'BOUGHT') {
                                                        $_SESSION['tempo'] = '<h6><span class="badge bg-success">BOUGHT</span></h6>';
                                                    } else if ($_SESSION['transakcje'][$i][4] == 'SOLD') {
                                                        $_SESSION['tempo'] = '<h6><span class="badge bg-danger">SOLD</span></h6>';
                                                    } else {
                                                        $_SESSION['tempo'] = '<span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="xd">
                                                                          <h6><span class="badge bg-info">' . $_SESSION['transakcje'][$i][4] . '</span></h6>
                                                                          </span>';
                                                    }
                                                    echo '<tr>
                                            <td style="text-align: center">
                                            <img src="' . $decoded[$k]['image'] . '" width="30px" height="30px" >
                                            </td>
                                            <th scope="row" style="text-align: center">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <span class="mb-0 text-sm">' . $_SESSION['transakcje'][$i][0] . '</span>
                                                    </div>
                                                </div>
                                            </th>
                                            <td style="text-align: center">'
                                                        . $_SESSION['transakcje'][$i][1] .
                                                        '</td>
                                            <td style="text-align: center">'
                                                        . $_SESSION['transakcje'][$i][2] .
                                                        '</td>
                                            
                                            <td style="text-align: center">'
                                                        . $_SESSION['transakcje'][$i][3] .
                                                        '</td>
                                            
                                            <td style="text-align: center">'
                                                        . $_SESSION['tempo'] .
                                                        '</td>
                                            
                                            <td>'
                                                        . $_SESSION['transakcje'][$i][5] .
                                                        '</td>
                                            
                                            </tr>';
                                                }
                                            }
                                        }

                                        if ($temp == 0) {
                                            echo '<tr><th style="text-align: center">Looks like there is no assets associated with your wallet. Add some funds and start Your journey</th></tr>';
                                        }

                                        ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.min.js" integrity="sha384-5h4UG+6GOuV9qXh6HqOLwZMY4mnLPraeTrjT5v07o347pj6IkfuoASuGBhfDsp3d" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
        <script>
            $('#date').datepicker({
                datetpicker: true,
                format: 'yyyy-mm-dd'
            });
        </script> -->


    <!-- custom js -->
    <script>
        var menu_btn = document.querySelector("#menu-btn")
        var sidebar = document.querySelector("#sidebar")
        var container = document.querySelector(".my-container")
        var moneyAmount;
        menu_btn.addEventListener("click", () => {
            sidebar.classList.toggle("active-nav")
            container.classList.toggle("active-cont")
        })

        function startValue() {

            setTimeout(() => {

                document.getElementById('payCard1').checked = false;
                document.getElementById('payCard2').checked = false;

                document.getElementsByClassName('payOpt')[0].style.display = 'block';
                document.getElementsByClassName('payOpt')[1].style.display = 'block';

                document.getElementById('creditCard').style.display = 'none';
                document.getElementById('blikk').style.display = 'none';

            }, 500)
        }

        //Credit card
        function selectPayMet(id) {

            document.getElementById(id).checked = true;

            if (id.localeCompare('payCard2') == 0) {
                setTimeout(() => {
                    document.getElementsByClassName('payOpt')[0].style.display = 'none';
                    document.getElementsByClassName('payOpt')[1].style.display = 'none';

                    document.getElementById('creditCard').style.display = 'block';
                }, 500);
            }
            if (id.localeCompare('payCard1') == 0) {
                setTimeout(() => {
                    document.getElementsByClassName('payOpt')[0].style.display = 'none';
                    document.getElementsByClassName('payOpt')[1].style.display = 'none';

                    document.getElementById('blikk').style.display = 'block';
                }, 500);
            }

        }

        function onlyNumberKey(evt, id) {

            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 32 && (ASCIICode < 48 || ASCIICode > 57) && ASCIICode != 46) {

                validateBuy();
                return false;
            } else {

                if (id.localeCompare('cardNumber') == 0) {
                    if (document.getElementById(id).value.length < 19) {
                        document.getElementById(id).style.borderColor = 'red';
                    } else {
                        document.getElementById(id).style.borderColor = 'blue';
                    }

                    if (document.getElementById(id).value.length == 4 || document.getElementById(id).value.length == 9 || document.getElementById(id).value.length == 14) {

                        document.getElementById(id).value += "-";

                    }
                }
                if (id.localeCompare('cardDate') == 0) {
                    if (document.getElementById(id).value.length < 7) {
                        document.getElementById(id).style.borderColor = 'red';
                    } else {
                        document.getElementById(id).style.borderColor = 'blue';
                    }
                    if (document.getElementById(id).value.length == 2) {

                        document.getElementById(id).value += "/";

                    }

                }
                if (id.localeCompare('cvvNumber') == 0) {

                    if (document.getElementById(id).value.length < 3) {
                        document.getElementById(id).style.borderColor = 'red';
                    } else {
                        document.getElementById(id).style.borderColor = 'blue';
                    }

                }
                if (id.localeCompare('blikNumber') == 0) {

                    if (document.getElementById(id).value.length < 11) {
                        document.getElementById(id).style.borderColor = 'red';
                    } else {
                        document.getElementById(id).style.borderColor = 'blue';
                    }
                    if (document.getElementById(id).value.length == 1 ||
                        document.getElementById(id).value.length == 3 ||
                        document.getElementById(id).value.length == 5 ||
                        document.getElementById(id).value.length == 7 ||
                        document.getElementById(id).value.length == 9) {

                        document.getElementById(id).value += "-";

                    }

                }

                blikNumber
                validateBuy();
                return true;

            }

        }




        function validateBuy() {
            var input = document.getElementById('amountInputBuy').value;
            const button = document.getElementById('submitButton');

            if (input > 0 & document.getElementById('cryptoBuy').value != document.getElementById('cryptoPay').value) {
                button.disabled = false;
            } else {
                button.disabled = true;
            }
        }
        setInterval(validateBuy, 250);

        function validateSell() {
            var input = document.getElementById('amountInputSell').value;
            const button = document.getElementById('submitButtonSell');

            if (input > 0) {
                button.disabled = false;
            } else {
                button.disabled = true;
            }
        }

        function sendMax(max) {
            var e = document.getElementById('toSell');
            document.getElementById('amountInputSell').value = e.options[e.selectedIndex].id;
        }
        setInterval(validateSell, 250);

    $(document).ready(function () {
        $('#historyTable').DataTable({
            "pagingType": "numbers"
        });
        $('.dataTables_length').addClass('bs-select');
    });

        function sendMaxBuy(max){
            const xhr = new XMLHttpRequest();

            xhr.onload = function (){

                const serverResponse = document.getElementById("amountInputBuy").value = this.responseText ;

            };
            var pay = document.getElementById('cryptoPay');
            var buy = document.getElementById('cryptoBuy');
            xhr.open("POST","howMuch.php");
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send('pay=' + pay.options[pay.selectedIndex].value + '&buy=' + buy.options[buy.selectedIndex].value);
        }

        function clearInput(){
            document.getElementById('amountInputBuy').value = '';
            document.getElementById('amountInputSell').value = '';
        }

</script>
</body>

</html>