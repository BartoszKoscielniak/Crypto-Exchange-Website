<?php
    session_start();
    require_once "dataBaseConnector.php";

    if(!isset($_SESSION['isLoggedIn'])){
        header('Location: homePage.php');
        exit();
    }
    //pobranie informacji o krypto
    $ch = curl_init();
    $url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=eur&order=market_cap_desc&per_page=20&page=1&sparkline=false";
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $response = curl_exec($ch);

    //wpisanie krytpto do bazy/aktualizacja ceny
    if($e = curl_error($ch)){
        echo $e;
    }else{
        $decoded = json_decode($response,true);

        mysqli_report(MYSQLI_REPORT_STRICT);
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_error());
        }else{
            for($i = 0; $i < sizeof($decoded); $i++) {

                $result = $connection->query("SELECT COUNT(*) FROM kryptowaluty WHERE nazwa = '".$decoded[$i]['name']."'");
                $row = $result->fetch_assoc();

                if ($row['COUNT(*)'] == 0) {
                    $connection->query("INSERT INTO kryptowaluty VALUES (".($i+1).",'".$decoded[$i]['name']."','".$decoded[$i]['current_price']."')");
                }else{
                    $connection->query("UPDATE kryptowaluty SET kurs = '".$decoded[$i]['current_price']."' WHERE nazwa = '".$decoded[$i]['name']."'");
                }
                $result->free();
            }
            $connection->close();
        }

    }
    curl_close($ch);

    //sprawdzanie ktore krypto posiadamy
    mysqli_report(MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0) {
        throw new Exception(mysqli_connect_error());
    }else{
        $result = $connection->query("SELECT * FROM portfele WHERE id_użytkownika = '".$_SESSION['id_użytkownika']."'");
        $_SESSION['portfel'] = $result->fetch_all();
        $result->free();

        $result = $connection->query("SELECT * FROM lista_walut WHERE id_portfela = '".$_SESSION['portfel'][0][0]."'");
        $_SESSION['lista_walut'] = $result->fetch_all();
        $result->free();

        $connection->close();
    }

    //pobranie listy krypto dostepnej w bazie
    mysqli_report(MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0) {
        throw new Exception(mysqli_connect_error());
    }else{
        $result = $connection->query("SELECT * FROM kryptowaluty ");
        $_SESSION['krypto'] = $result->fetch_all();
        $result->free();
        $connection->close();
}
    //print_r($portfel[0][0]);
    //TODO:Zoptymalizowac sposob laczenia z baza danych
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
    <p style="position:relative; top: 500px; color: antiquewhite; text-align: center">Powered by CoinGecko API</p>
</ul>

<div id="top-bar">
    <p style="width: auto; padding: 5px; position: absolute; right:1%"><?php echo $_SESSION['imię'] ?></p>
    <input type="image" id="myimage" style="height:50px; width:50px; position:absolute; right:5%" src="img/user.png" onclick="FormVis()" href="#log-popup"/>
    <button style="position:absolute; right:9%; padding: 5px ">Send/Receive</button>
    <button style="position:absolute; right:15%; padding: 5px" onclick="document.getElementById('operation-div').style.display='block'">Buy/Sell</button>
    <p style="position:absolute; left: 0%">CRYPTOEXCH - Main Page</p>
</div>

<!-- Wallet -->

<div id="wallet">

    <h4 style="text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black; font-weight: bold; color: rgb(51, 196, 129); margin-right: 80%; font-size: 40px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">Wallet</h4>

    <hr style="border: 1px solid #000; margin-right: 3%; margin-left: 3%;"><br>

    <div id="dollars">
        <img style="float: right;" src="img/dollar.png">
        <?php
            echo '<a>'.$_SESSION['portfel'][0][2].'</a>'
        ?>

    </div>
    
    <table id="wallet-table">
        
        <tr style="background-color: rgb(51, 196, 129); color: #000; "><th class="rank">Rank</th><th>Logo</th><th>Crypto</th><th>Balance</th></tr>
        <?php
        $temp = 0;

        for($i = 0; $i < sizeof($_SESSION['lista_walut']); $i++) {
            for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                if ($_SESSION['lista_walut'][$i][2] == $_SESSION['krypto'][$a][0]) {
                    echo '<tr><th class="rank">' . $decoded[$a]['market_cap_rank'] . '</th><th style="width: 100px;"><img src="' . $decoded[$a]['image'] . '" width="50px" height="50px"></th><th>' . $decoded[$a]['name'] . '</th><th>'.$_SESSION['lista_walut'][$i][3].'</th></tr>' . "\n";
                    $temp += 1;
                    break;
                }
            }
        }
        if($temp == 0){
            echo '<tr><th>Looks like there is no assets associated with your wallet. Add some funds and start Your journey</th></tr>' . "\n";
        }
        //TODO:FRONTEND: Alert ma sie wyswietlac na calej szerokosci tabeli
        ?>
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
    <input id="submit" type="button" value="Submit" ><br><br>
    
</div>

<!-- Buy crypto(POP UP) -->
<div id="operation-div" style="display: none">

    <div id="nav-div">
        <button id="buy" class="nav-button" onclick="controlBuyAndSellPanel(document.getElementById('buy-form'))">Buy</button>
        <button id="sell" class="nav-button" onclick="controlBuyAndSellPanel(document.getElementById('sell-form'))">Sell</button>
    </div>

    <div id="buy-form">

        <!--Euro in wallet-->
        <div >
            <h1 id="euro-amount">
                <?php
                echo '<a>'.$_SESSION['portfel'][0][2].'</a>'
                ?>
                <a style="font-size: 20px;">€</a></h1>
        </div>

    <form action="buyCrypto.php" method="post" >

        <img src="img/mastercard.png" width="60" height="60" style="float:left"></img>
        <input id="cr_textfield" type="text" name="amount" placeholder="How much?"></input><br><br>

        <label class="inscription">Buy:</label>
        <select name="buy" id="crypto" style="border:none; text-align: center;">

            <?php
            for($i = 0; $i < 10; $i++) {
                echo '<option value="' . htmlspecialchars($decoded[$i]['name']) . '" >'.$decoded[$i]['name']. '</option>'. "\n";
            }
            ?>

        </select><br><br>
        <label class="inscription">Pay:</label>
        <select name="pay" id="crypto" style="border:none;">

            <option value="myWallet">My Wallet</option>
            <?php
            for($i = 0; $i < sizeof($_SESSION['lista_walut']); $i++) {
                for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                    if ($_SESSION['lista_walut'][$i][2] == $_SESSION['krypto'][$a][0]) {
                        echo '<option value="' . htmlspecialchars($decoded[$a]['name']) . '" >'.$decoded[$a]['name']. '('.$_SESSION['lista_walut'][$i][3].')</option>'. "\n";
                        break;
                    }
                }
            }
            //TODO: Pokazuj w nawiasie dostepne srodki
            //TODO: Pokazuj do sprzedania tylko te krypto ktore posiadasz w porfelu
            //TODO:FRONTEND: Lista z krypto w popupie mogla by sie otwierac w nowym okienku na srodku ekranu po kliknieciu w nia
            //TODO:FRONTEND: Przy wlaczeniu roznych zakladek wallet/exchange itd znika opcja buy w popupie
            //TODO:FRONTEND: zakladka exchange tabela z wszystkimi krypto i pole do wybrania jednej i jej zakupu
            //TODO:FRONTEND: Dodac maly przycisk obok how much *max*
            ?>
        </select>
        <br>
        <br>
        <?php if (isset($_SESSION['err_fund'])) { echo $_SESSION['err_fund'];unset($_SESSION['err_fund']);} ?>
        <button type="submit" id="BuyCrypto">Buy Crypto</button>
    </form>

        <button id="CloseDiv" onclick="document.getElementById('operation-div').style.display='none'">Close</button>
    

    </div>

    <div id="sell-form" style="display: none">

        <!--Euro in wallet-->
        <div >
            <h1 id="euro-amount">
                <?php
                echo '<a>'.$_SESSION['portfel'][0][2].'</a>'
                ?>
                <a style="font-size: 20px;">€</a></h1>
        </div>


    <form >

        <img src="img/mastercard.png" width="60" height="60" style="float:left"></img>
        <label class="inscription">Sell:</label>
        <select name="crypto" id="crypto" style="border:none; text-align: center;">

            <?php
            $temp = 0;
            for($i = 0; $i < sizeof($_SESSION['lista_walut']); $i++) {
                for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                    if ($_SESSION['lista_walut'][$i][2] == $_SESSION['krypto'][$a][0]) {
                        echo '<option value="' . htmlspecialchars($decoded[$a]['name']) . '" >'.$decoded[$a]['name']. '('.$_SESSION['lista_walut'][$i][3].')</option>'. "\n";
                        $temp += 1;
                        break;
                    }
                }
            }
            if($temp == 0){
                echo "No assets to sell";
            }
            ?>

        </select><br><br>
        <input id="cr_textfield" type="text" placeholder="How much?"></input><br><br>

        <label id="sell_value"><a>+</a><a>0</a><a style="font-size: 16px">€</a></label><br>
        
    </form>

    <br>
        <button id="BuyCrypto">Sell Crypto</button>
        <button id="CloseDiv" onclick="document.getElementById('operation-div').style.display='none'">Close</button>
 
</div>

</div>

<!-- <div class="nav-button">

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
    
</div> -->

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

<!-- <div id="main-screen">

    <script src="https://widgets.coingecko.com/coingecko-coin-price-marquee-widget.js"></script>
    <coingecko-coin-price-marquee-widget  coin-ids="bitcoin,ethereum,litecoin,ripple" currency="usd" background-color="#ffffff" locale="en"></coingecko-coin-price-marquee-widget>

    <div style="background-color:mediumpurple; height:350px; width:700px ">
        <script src="https://widgets.coingecko.com/coingecko-coin-price-chart-widget.js"></script>
        <coingecko-coin-price-chart-widget  coin-id="bitcoin" currency="usd" height="350" locale="en"></coingecko-coin-price-chart-widget>
    </div>

</div> -->

</body>
<script src="homePageScript.js"></script>
<script src="userProfileScript.js"></script>
</html>