var StockCheck_app = angular.module('StockCheck_app', []);
StockCheck_app.controller('StockCheck_Controller', function ($scope) {
    var sample_StockCheck = { _items: [{ Principal: 0, Code_Part: '', Description: '', ED_applicable: 0, PO_QTY: 0, Available_ED_Qty: 0,
        _amt: 0, _ed_percent: 0, Available_WED_Qty: 0
    }]
    };
    $scope.init = function (number) {
        //alert(number);
        if (number != "") {
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/StockCheck_Controller.php",
                type: "POST",
                data: { TYP: "SELECT", POID: number },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    alert(jsondata);
                    $scope.$apply(function () {
                        $scope.StockCheck = objs[0];
                        $scope.StockCheck._items = objs[0]._items;
                        if ($scope.StockCheck._items.length > 0) {
                            $("#message").hide();
                        }
                        else {
                            $("#invoicedetails").hide();
                            $("#message").show();
                        }
                        k = 0;
                        while (k < $scope.StockCheck._items.length) {
                            // $scope.StockCheck._items[0]["ED_applicable"].hide();
                            if ($scope.StockCheck._items[k]["ED_applicable"] == 'E' || $scope.StockCheck._items[k]["ED_applicable"] == 'I') {
                                $scope.StockCheck._items[k]["Available_WED_Qty"] = null;
                                $scope.StockCheck.sttrf = false;
                                $scope.StockCheck.invnonexcise = false;
                                if ($scope.StockCheck._items[k]["Available_ED_Qty"] >= $scope.StockCheck._items[k]["PO_QTY"]) {
                                    $scope.StockCheck.otinv = true;
                                    $scope.StockCheck.addtobas = false;
                                }
                                else if ($scope.StockCheck._items[k]["Available_ED_Qty"] < $scope.StockCheck._items[k]["PO_QTY"]) {
                                    $scope.StockCheck.otinv = false;
                                    $scope.StockCheck.addtobas = true;
                                }
                            }
                            if ($scope.StockCheck._items[k]["ED_applicable"] == 'N' && $scope.StockCheck._items[k]["Available_WED_Qty"] == $scope.StockCheck._items[k]["PO_QTY"]) {
                                $scope.StockCheck.invnonexcise = true;
                                $scope.StockCheck.otinv = false;
                                $scope.StockCheck.addtobas = false;
                                $scope.StockCheck.sttrf = false;
                            }
                            if ($scope.StockCheck._items[k]["ED_applicable"] == 'N' && $scope.StockCheck._items[k]["Available_WED_Qty"] < $scope.StockCheck._items[k]["PO_QTY"] && $scope.StockCheck._items[k]["Available_ED_Qty"] > $scope.StockCheck._items[k]["PO_QTY"]) {
                                $scope.StockCheck.invnonexcise = false;
                                $scope.StockCheck.otinv = false;
                                $scope.StockCheck.addtobas = false;
                                $scope.StockCheck.sttrf = true;

                            }
                            if ($scope.StockCheck._items[k]["ED_applicable"] == 'N' && $scope.StockCheck._items[k]["Available_ED_Qty"] < $scope.StockCheck._items[k]["PO_QTY"]) {
                                $scope.StockCheck.invnonexcise = true;
                                $scope.StockCheck.otinv = false;
                                $scope.StockCheck.addtobas = false;
                                $scope.StockCheck.sttrf = false;
                            }


                            k++;
                        }
                    });
                }
            });
        }
    }
});