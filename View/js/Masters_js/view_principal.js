$(".PrincipalList").flexigrid({
                url : '../../Controller/Master_Controller/Principal_Controller.php?TYP=&PRINCIPAL_SUPPLIER_ID=',
                dataType : 'json',
                colModel : [ {
                    display : 'Principal ID',
                    name : 'PRINCIPALID',
                    width : 100,
                    sortable : true,
                    align : 'center', process: HitMe
                    }, {
                        display : 'Principal NAME',
                        name : 'PRINCIPALNAME',
                        width : 350,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display: 'CITY ID',
                        name: 'CITYID',
                        width: 375,
                        sortable: true,
                        align: 'left',
                        hide: true
                    },
                    {
                        display: 'STATE ID',
                        name: 'STATEID',
                        width: 375,
                        sortable: true,
                        align: 'left',
                        hide :true
                    },
                    {
                        display : 'State NAME',
                        name : 'STATENAME',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    }, {
                        display : 'City NAME',
                        name : 'CITYNAME',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Address 1',
                        name : 'ADD1',
                        width : 250,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Address 2',
                        name : 'ADD2',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Pincode',
                        name : 'PINCODE',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Range',
                        name : 'RANGE',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Division',
                        name : 'DIVISION',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Commission Rate',
                        name : 'COMMISSIONRATE',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'ECC Code No',
                        name : 'ECC',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Tin No',
                        name : 'TINNO',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Pan No',
                        name : 'PANNO',
                        width : 100,
                        sortable : true,
                        align : 'left'
                    },
//                    {
//                        display : 'Bank Name',
//                        name : 'BANKNAME',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Bank Account',
//                        name : 'ACCOUNTNO',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Bank Address',
//                        name : 'BANKADDRESS',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'RTGS',
//                        name : 'RTGS',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'NEFT',
//                        name : 'NEFT',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Account Type',
//                        name : 'ACCOUNTTYPE',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
                     ],
                sortorder : "asc",
                usepager : true,
                title : 'Principal Master',
                useRp : true,
                rp : 10,
                showTableToggleBtn : false,
                width : 1400,
                height : 350,
                
            });
function HitMe(celDiv, id) {
    $(celDiv).click(function () {
        var id = celDiv.innerText;
        var path = 'principal.php?TYP=SELECT&ID=' + id;
        window.location.href = path;
    });
}