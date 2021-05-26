//ajax
if (e.options[e.selectedIndex].id == 'myWallet') {
document.getElementById('amountInputBuy').value = e.options[e.selectedIndex].id;
}else{
var data1 = "xd";
$.ajax({
method: "POST",
url: "home.php",
data: { data1: data1},
}).done(function(html){
alert("success!");

document.getElementById('amountInputBuy').value = <?php

if(isset($_POST['data1'])){
    echo json_encode("xd");
}else{
    echo json_encode("ta nie dziala");
}
?>;

}).fail(function(html){
alert("nah!");
});
}



//inny sposob z cookie
var e = document.getElementById('toPay');
var a = document.getElementById('toBuy');
createCookie("id",e.options[e.selectedIndex].id,10);

document.getElementById('amountInputBuy').value = <?php

if(isset($_COOKIE["id"])){
    echo json_encode($_COOKIE["id"]);
    setcookie("id", time() - 3600);
    //echo json_encode("xd");
}else{
    echo json_encode("nie dziala");
}
?>;


$('#buyForm').submit(function(e) {
e.preventDefault();
$.ajax({
type: "POST",
url: 'home.php',
data: $(this).serialize(),
success: function(response)
{
var jsonData = JSON.parse(response);

// user is logged in successfully in the back-end
// let's redirect
if (jsonData.success == "1")
{
alert('xd');
}
else
{
alert('Invalid Credentials!');
}
}
});
});