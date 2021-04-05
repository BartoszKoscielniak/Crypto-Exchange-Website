
function switchPanel(id) {

    document.getElementById('wallet').style.display = 'none';
    document.getElementById('account').style.display = 'none';
    document.getElementById('buy').style.display = 'none';
    document.getElementById('exchange').style.display = 'none';

    id.display = "block";

}

