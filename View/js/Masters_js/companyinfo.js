﻿var URL = "../../Controller/Master_Controller/Company_Controller.php?TYP=";
var companyinfo_app = angular.module('companyinfo_app', []);
var flexCreatedForContactDeatails = false;

// create angular controller
//companyinfo_app.controller('buyer_Controller', function ($scope) {
companyinfo_app.controller('companyinfo_Controller', ['$scope', '$http', function companyinfo_Controller($scope, $http) {
  
   
}]);

function UpdateCompanyInfo(){
		var TYPE = "UPDATE";
		var company_nm =  document.getElementById("company_nm").value; 
		var company_add =  document.getElementById("company_add").value; 
		var company_phone =  document.getElementById("company_phone").value;
		var company_mobile =  document.getElementById("company_mobile").value;
		var company_email = document.getElementById("company_email").value;
		var company_website =  document.getElementById("company_website").value;
		var company_fax =  document.getElementById("company_fax").value;
		var company_CERegnNo =  document.getElementById("company_CERegnNo").value;
		var company_RangeMulti =  document.getElementById("company_RangeMulti").value;
		var company_ECCNo =  document.getElementById("company_ECCNo").value;
		var company_tin =  document.getElementById("company_tin").value;
		var gstin_number =  document.getElementById("gstin_number").value;
		var company_pan =  document.getElementById("company_pan").value;
		var commissionrate =  document.getElementById("commissionrate").value;
		var companyDivision =  document.getElementById("companyDivision").value;
		var term_text1 = document.getElementById("term_text1").value;
		var term_text2 = document.getElementById("term_text2").value;
		var term_text3 = document.getElementById("term_text3").value;
		var term_text4 = document.getElementById("term_text4").value;
		var term_text5 = document.getElementById("term_text5").value;
		var term_text6 = document.getElementById("term_text6").value;
		var term_text7 = document.getElementById("term_text7").value;
		var term_text8 = document.getElementById("term_text8").value;
		var term_text9 = document.getElementById("term_text9").value;
		var term_text10 = document.getElementById("term_text10").value;
		var term_text11 = document.getElementById("term_text11").value;
		var term_text12 = document.getElementById("term_text12").value;
    jQuery.ajax({
            url: "../../Controller/Master_Controller/Company_Controller.php",
            type: "POST",
            data: { TYP: TYPE, company_nm: company_nm, company_add : company_add, company_phone: company_phone
                    , company_mobile: company_mobile, company_email : company_email, company_website: company_website, company_fax: company_fax, company_CERegnNo : company_CERegnNo, company_RangeMulti: company_RangeMulti
                    , company_ECCNo: company_ECCNo, company_tin : company_tin, gstin_number : gstin_number, company_pan: company_pan, commissionrate : commissionrate, companyDivision: companyDivision, term_text1: term_text1, term_text2 : term_text2,term_text3: term_text3,term_text4: term_text4, term_text5: term_text5,term_text6:term_text6,term_text7:term_text7,term_text8:term_text8,term_text9:term_text9,term_text10:term_text10,term_text11:term_text11,term_text12:term_text12},
            //cache: false,
            success: function (jsondata) { 
               if (jsondata == "YES") {
                	  alert("Updated Successfully!");
                    location.href = "companyinfo.php";                  
                }else {
                  	alert("Not Updated Successfully!");
                    location.href = "companyinfo.php";      
                	
                }
            }
        });
    
}