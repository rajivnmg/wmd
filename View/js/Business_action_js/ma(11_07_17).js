var managemenapproval_app = angular.module('ma_app', []);
managemenapproval_app.controller('ma_Controller', function ($scope) {
    var sample_ma = { _items: [{ bpod_Id: '', po_principalId: '', $po_principalName: '', po_codePartNo: '', product: '', po_qty: '', po_cate: '', po_price: '', buyer_discount: '', po_discount: ''}] };

    $scope.managementapp = sample_ma;
    $scope.init = function (number, tg) {
        $scope.managementapp.poid = number;
        //alert("here");
        if (number != "") {
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/ma_Controller.php",
                type: "POST",
                data: {
                    TYP: "MA_FILL",
                    POID: number
                },
                success: function (jsondata) {
                    //alert(jsondata);
                    $scope.$apply(function () {
                        var objs = jQuery.parseJSON(jsondata);
                       // alert(jsondata);
                        $scope.managementapp = objs[0];
                        $scope.managementapp.poid = number;
                        if (objs[0].maTag == "R") {
                            $scope.managementapp.maTag = true;
                        } else {
                            $scope.managementapp.maTag = false;
                        }
                        $scope.showHideatAppStatus(tg);
                        $scope.managementapp._items = objs[0]._items;
                    });
                }


            });
        }

    }
    $scope.giveApp = function (fp) {
        var json_string = JSON.stringify($scope.managementapp);
		
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/ma_Controller.php",
            type: method,
            data: { TYP: "APPROVAL", MADATA: json_string },
            success: function (jsondata) {
                alert("save successfully...");
                //alert(fp);
                if(fp=="AR"){
				  location.href = "../home/Dashboard.php";
				}
            },
            error: function () {
                alert("falied..");
            }
        });
    }
    $scope.Goto = function (id) {
        location.href = "purchaseorder.php?fromPage=MA&POID=" + id;
    }
    $scope.showHideatAppStatus = function (tg) {
        //alert(tg);
        if (tg != "M") {
            $('#forMA').show();
            $('#appStatus').hide();
        } else {
            $('#forMA').hide();
            $('#appStatus').show();
        }
    }
    $scope.AddMA = function (fp) {
	
        var json_string = JSON.stringify($scope.managementapp);
		
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/ma_Controller.php",
            type: method,
            data: { TYP: "INSERT", MADATA: json_string },
            success: function (jsondata) {
                alert("save successfully !!");
                //alert(fp);
                if(fp=="AR"){
				  location.href = "../home/Dashboard.php";
				}else{
					location.href = "purchaseorder.php";
				}
                

            },
            error: function () {
                alert("falied..");
            }
        });
    }
});
