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
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

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

    <!-- Wallet section -->

    <section id="wallet" class="p-4 my-container">
        <div style="display: inline">
            <script src="https://widgets.coingecko.com/coingecko-coin-price-marquee-widget.js"></script>
            <coingecko-coin-price-marquee-widget coin-ids="bitcoin,ethereum,litecoin,ripple" currency="eur" background-color="#ffffff" locale="en"></coingecko-coin-price-marquee-widget>
            <h2>Buy or sell Your crypto assets</h2>

        <form action="/20-21-ai-projekt-lab3-projekt-ai-koscielniak-b-matusik-l/logOut.php">
            <button type="submit" class="btn btn-outline-danger" data-mdb-ripple-color="dark" style=" float:right; margin-right:10px">
                Log Out
            </button>
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

                                <input id="amountInputSell" name="amount" type="text" class="form-control" placeholder="Name"><br>
                                <input id="amountInputSell" name="amount" type="text" class="form-control" placeholder="Email"><br>

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
                                    <a class="nav-link active" data-toggle="tab" href="#panel7" role="tab"><i class="fas fa-user mr-1"></i>
                                        Buy</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel8" role="tab"><i class="fas fa-user-plus mr-1"></i>
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
                                            <i class="fas fa-envelope prefix"></i>
                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Your wallet</label>
                                            <h1 id="euro-amount">
                                                <?php
                                                echo '<a>' . number_format($_SESSION['portfel'][0][2],2) . '</a>'
                                                ?>
                                                <a style="font-size: 20px;">€</a>
                                            </h1>
                                        </div>

                                    <div class="md-form form-sm mb-4">
                                         <form action="buyCrypto.php" method="post" class="mb-3">
                                            <input id="cryptoToBuy" name="buy" type="text" class="form-control" autocomplete="off" hidden>

                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Pay:</label>
                                            <select name="pay" id="cryptotoPay" class="form-select" aria-label="Default select example">

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
                                            <textarea name="area" style="display:none">buyOrSell.php</textarea>
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
                                        <i class="fas fa-envelope prefix"></i>

                                        <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Your wallet</label>
                                        <h1 id="euro-amount">
                                            <?php
                                            echo "<script>console.log('Debug Objects:');</script>";
                                            echo '<a>' . number_format($_SESSION['portfel'][0][2],2) . '</a>'
                                            ?>
                                            <a style="font-size: 20px;">€</a>
                                        </h1>

                                        <i class="fas fa-lock prefix"></i>

                                    </div>

                                    <div>


                                        <form action="sellCrypto.php" method="post" class="mb-3">
                                            <input id="cryptoToSell" name="sell" type="text" class="form-control" autocomplete="off" hidden>

                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">How much?</label>

                                            <div class="input-group mb-3">
                                                <input id="amountInputSellxd" name="amount" type="text" class="form-control" onkeypress="return onlyNumberKey(event)" autocomplete="off">
                                                <div class="input-group-append">
                                                    <button id="maxButtonBuy" class="btn btn-outline-primary" type="button" onclick="sendMaxSell()">MAX</button>
                                                </div>
                                            </div>

                                            <?php if (isset($_SESSION['err_fund2'])) {
                                                echo $_SESSION['err_fund2'];
                                                unset($_SESSION['err_fund2']);
                                            } ?>
                                            <textarea name="area" style="display:none">wallet.php</textarea>
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
                            <h3 class="mb-0">TOP 100 Cryptocurrency</h3>
                        </div>
                        <div class="table-responsive">
                            <table id="buySellTable" class="table align-items-center table-flush" >
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="text-align: center">Rank</th>
                                    <th scope="col" style="text-align: center">Logo</th>
                                    <th scope="col" style="text-align: center">Crypto</th>
                                    <th scope="col" style="text-align: center">Current price</th>
                                    <th scope="col" style="text-align: center">Market Cap</th>
                                    <th scope="col" style="text-align: center">24h change</th>
                                    <th scope="col" style="text-align: center">24h change</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                <?php
                                for ($b = 0; $b < sizeof($decoded); $b++) {
                                    if ($decoded[$b]['price_change_percentage_24h'] <= 0) {
                                        $_SESSION['24h_change_color'] = "red";
                                    }else{
                                        $_SESSION['24h_change_color'] = "green";
                                    }
                                    echo '<tr><th class="rank" style="width:10%; text-align: center">' . $decoded[$b]['market_cap_rank'] . '</th><th style="width: 18%; text-align: center"><img src="' . $decoded[$b]['image'] . '" width="30px" height="30px"></th><th style="width:18%; text-align: center">' . $decoded[$b]['name'] . '</th><th style="width:18%; text-align: center">' . number_format($decoded[$b]['current_price'],2) . '€</th><th style="width:18%; text-align: center">' . number_format($decoded[$b]['market_cap']) . '€</th><th style="width:18%; text-align: center; color: '.$_SESSION['24h_change_color'].' ">' . sprintf("%.1f", round($decoded[$b]['price_change_percentage_24h'], 3)) . '%</th><th style="text-align: center"><button type="button" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark" style="float:right; margin-right:10px" data-target="#myModal" data-toggle="modal" onclick="choosedOneRow(' . $decoded[$b]['market_cap_rank'] . ')">Buy/Sell</button></th></tr>' . "\n";
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
<!-- custom js -->
<script>
    var menu_btn = document.querySelector("#menu-btn")
    var sidebar = document.querySelector("#sidebar")
    var container = document.querySelector(".my-container")
    menu_btn.addEventListener("click", () => {
        sidebar.classList.toggle("active-nav")
        container.classList.toggle("active-cont")
    })

    function onlyNumberKey(evt) {
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57) && ASCIICode != 46) {
            validateBuy();
            return false;
        }else{
            validateBuy();
            return true;
        }
    }

    function validateBuy(){
        var input = document.getElementById('amountInputBuy').value;
        const button = document.getElementById('submitButton');

        if(input > 0){
            button.disabled = false;
        }else {
            button.disabled = true;
        }
    }
    setInterval(validateBuy,250);

    function validateSell(){
        var input = document.getElementById('amountInputSellxd').value;
        const button = document.getElementById('submitButtonSell');

        if(input > 0){
            button.disabled = false;
        }else {
            button.disabled = true;
        }
    }
    setInterval(validateSell,250);

    function sendMax(max){
        var e =document.getElementById('toSell');
        document.getElementById('amountInputSell').value = e.options[e.selectedIndex].id;
    }

    $(document).ready(function () {
        $('#buySellTable').DataTable({
            "pagingType": "numbers"
        });
        $('.dataTables_length').addClass('bs-select');
    });

    function choosedOneRow(id){
        document.getElementById('cryptoToBuy').value = id;
        document.getElementById('cryptoToSell').value = id * 1000;
    }

    function sendMaxBuy(max){
        const xhr = new XMLHttpRequest();

        xhr.onload = function (){

            const serverResponse = document.getElementById("amountInputBuy").value = this.responseText ;
            const serverResponse1 = document.getElementById("serverResponse");
            serverResponse1.innerHTML = this.responseText;
        };
        var pay = document.getElementById('cryptotoPay');
        var buy = document.getElementById('cryptoToBuy').value;
        xhr.open("POST","howMuch.php");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send('pay=' + pay.options[pay.selectedIndex].value + '&buy=' + buy);
    }

    function sendMaxSell(max){
        const xhr = new XMLHttpRequest();

        xhr.onload = function (){

            const serverResponse = document.getElementById("amountInputSellxd").value = this.responseText ;
            const serverResponse1 = document.getElementById("serverResponse");
            serverResponse1.innerHTML = this.responseText;
        };

        var buy = document.getElementById('cryptoToSell').value;
        xhr.open("POST","howMuchToSell.php");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send('sell=' + buy);
    }

    function clearInput(){
        document.getElementById('amountInputBuy').value = '';
        document.getElementById('amountInputSellxd').value = '';
    }

</script>
</body>

</html>