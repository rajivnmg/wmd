function submitme1(){
alert("1");
f1.submit();

}
var siteURL = "";
var site = document.URL;
if (site.match("www.")) {

    //siteURL = "http://www.mastervalue.in/";
    //siteURL = "http://localhost:1741/Education_Portal/";
}
else {
    //siteURL = "mastervalue.in/";
    siteURL = "http://127.0.0.1:8080/Multiweld/";
}