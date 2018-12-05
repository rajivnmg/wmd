var view = null;
var myDiv = null;
function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
 function doAjaxPost(url) {

              xmlHTTP=GetXmlHttpObject();
              //var nm=document.getElementById("name").value;
              var url = url;
              view = url;
              //alert(view+"@"+divId);
              //myDiv = divId;
              xmlHTTP.onreadystatechange = showVal;

xmlHTTP.open("POST",url,true);
xmlHTTP.send(null);
}
function loadjscssfile(filename, filetype){
 if (filetype=="js"){ //if filename is a external JavaScript file
  var fileref=document.createElement('script')
  fileref.setAttribute("type","text/javascript")
  fileref.setAttribute("src", filename)
 }
 else if (filetype=="css"){ //if filename is an external CSS file
  var fileref=document.createElement("link")
  fileref.setAttribute("rel", "stylesheet")
  fileref.setAttribute("type", "text/css")
  fileref.setAttribute("href", filename)
 }
 if (typeof fileref!="undefined")
  document.getElementsByTagName("head")[0].appendChild(fileref)
}

function showVal() {
    //alert(xmlHTTP.readyState);
    //alert("ankur");
    //alert(myDiv);
    if (xmlHTTP.readyState == 4 || xmlHTTP.readyState == 0) {
        var x = xmlHTTP.responseText;
        // x=trim(x);
        // document.getElementById("xfileBox").style.visibility = 'visible';
//        if (myDiv == "showGrid") {
//            document.getElementById("showPage").innerHTML = "";
//            document.getElementById("showPage").style.display = "none";
//        } else {
//            document.getElementById("showGrid").innerHTML = "";
//            document.getElementById("showGrid").style.display = "none";
//        }
        //document.getElementById("showGrid").style.display = "block";
        //alert(x);
        document.getElementById("showPage").innerHTML = x;
        loadJS();
    }
}
//loadjscssfile("View/js/Masters_js/unit.js", "js");
//View/state.php

function loadJS()
{
var page_path_array = view.split('/');
var page_array = page_path_array[1].split('.');
view = page_array[0];
switch (view) {
    case "unit":
        loadjscssfile("View/js/Masters_js/unit.js", "js");
        break;
    case "state":
        loadjscssfile("View/js/Masters_js/state.js", "js");
        break;
    case "group":
        loadjscssfile("View/js/Masters_js/group.js", "js");
        break;
    case "city":
        loadjscssfile("View/js/Masters_js/city.js", "js");
        break;
    case "location":
        loadjscssfile("View/js/Masters_js/location.js", "js");
        break;
    case "item":
        loadjscssfile("View/js/Masters_js/item.js", "js");
        break;
    case "principal":
        loadjscssfile("View/js/Masters_js/principal.js", "js");
        break;
    case "principal_grid":
        loadjscssfile("View/js/Masters_js/principal_grid.js", "js");
        break;
    case "supplier":
        loadjscssfile("View/js/Masters_js/supplier.js", "js");
        break;
    case "buyer":
        loadjscssfile("View/js/ang_js/angular.js", "js");
        loadjscssfile("View/js/ang_js/Buyer_app.js", "js");
        loadjscssfile("View/js/Masters_js/buyer.js", "js");
        break;
    default:
        break;
}


}
