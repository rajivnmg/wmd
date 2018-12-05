function printDoc(divName){
	//alert("start");
	var h = $(".tx_ivc").height ();
	var breakPoint = $("#breakpoint").val();
	var pageLength = breakPoint + 5; // Footer = 5
		for (var i = 0; i <= breakPoint; i++) {
			if(i > 7) {
				$("#SrNo-" + i).before($("#header_part").html());
			}
			i = i + 8;
			$("#SrNo-" + i)
			
			.after("<div class='page-break'></div>")
			.after($("#footer_part").html());
		}
		
		//window.print ();
	
	 var pageHeight = $("#"+divName).height();
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
	 

     //document.body.innerHTML = printContents;

     window.print();
 //workaround for Chrome bug - https://code.google.com/p/chromium/issues/detail?id=141633
    if (window.stop) {
        //location.reload(); //triggering unload (e.g. reloading the page) makes the print dialog appear
       // window.stop(); //immediately stop reloading
    }
    //location.reload(); //triggering unload (e.g. reloading the page) makes the print dialog appear
   // window.stop(); //immediately stop reloading
    return false;
     //document.body.innerHTML = originalContents;
}

function sendBack(){
	location.href="ChallanView.php";
}