<!DOCTYPE html>
<html>
  <head>
  <script type='text/javascript' src='http://192.168.1.121/multiGurgaonNew/View/js/jquery.js'></script>


    <style>
	@media print {
		 .header {
			//height: 2.5em; /* header must have a fixed height */

		  }
		  .footer {
			height: 2.5em; /* header must have a fixed height */
			position: fixed;
            bottom: 0;
		  }
		  #header {
			display: none;
		  }
		  #footer {
			display: none;
		  }
	}
	@media screen {
		.header {
			display: none;
		  }
		  .footer {
			display: none;
		  }
	}
	.content div {
		height: 300px;
	}
	.content div.header, .content div.footer {
		height: 30px;
	}
  .section {
	display: table;
	width: 100%;
  }
     
     
      
    </style>
  </head>
  <body>
	<div id="footer">
		<div class="footer">PAGE Footer</div>
	 </div>
	 <div id="header">
		<div  class="header">
			PAGE HEADER
		</div>
	 </div>
    <div class="section">
	  <div class="content">
		<div id="1">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="2">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="3">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="4">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="5">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="6">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="7">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="8">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="9">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="10">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="11">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="12">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
		<div id="13">
			Unbreakable section.<br/>Unbreakable section.<br/>Unbreakable section.<br/>
		</div>
	  </div>
	</div>
	<a href="javascript:void (0)" onclick="printW ()">printW</a>
  </body>
</html>
<script>
	function printW () {
		var h = $(".section").height ();
		
		for (var i = 1; i <= 13; i++) {
			$("div#" + i).before($("#header").html());
			i = i + 2;
			$("div#" + i).append($("#footer").html());
		}
		
		window.print ();
		
	}
</script>
