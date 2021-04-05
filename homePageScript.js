function FormVis() {

    var vis = document.getElementById('form-div');
  
    if (vis.style.visibility == "hidden") {
      vis.style.visibility = "visible";
    }
     else {
      vis.style.visibility = "hidden";
    }
  }
  function CreateAccountFormVis() {
  
  var vis = document.getElementById('create-account-form');
  
  if (vis.style.display == "none") {
    vis.style.display = "block";
  }
   else {
    vis.style.display = "none";
  }
  }