function printDoc(divName){
	//alert("start");
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();
 //workaround for Chrome bug - https://code.google.com/p/chromium/issues/detail?id=141633
    if (window.stop) {
        location.reload(); //triggering unload (e.g. reloading the page) makes the print dialog appear
       // window.stop(); //immediately stop reloading
    }
    location.reload(); //triggering unload (e.g. reloading the page) makes the print dialog appear
   // window.stop(); //immediately stop reloading
    return false;
     //document.body.innerHTML = originalContents;
}

function sendBack(){
	location.href="viewQuotation.php";
}