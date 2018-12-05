function LoadSearchDetails() {
    //alert(json_string);
    //$(".flexme4").empty();
    var poid = getParameterByName("POID");
    //alert(poid);
    $(".flexme4").flexigrid({
        url: '../../Controller/Business_Action_Controller/SearchDetails_controller.php?TYP=SEARCH&POID=' + poid,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [ { display: 'principalname', name: 'principalname', width: 200, sortable: true, align: 'left' },
                             { display: 'codepartno', name: 'codepartno', width: 200, sortable: true, align: 'left' },
                             { display: 'po_qty', name: 'po_qty', width: 200, sortable: true, align: 'left' },

                             { display: 'po_unit', name: 'po_unit', width: 200, sortable: true, align: 'left' },
                           { display: 'po_ed_applicability', name: 'po_ed_applicability', width: 200, sortable: true, align: 'left' },
                           { display: 'po_item_stage', name: 'po_item_stage', width: 200, sortable: true, align: 'left' }, ],
        //        buttons: [{ name: 'Edit', bclass: 'edit', onpress: UserMasterGrid }, { separator: true}],
        //        searchitems : [ { display : 'USERID', name : 'USERID'}, { display : 'PASSWD', name : 'PASSWD', isdefault : false } ],
        sortorder: "asc",
        usepager: false,
        title: 'Search Result',
        useRp: false,
        rp: 15,
        showTableToggleBtn: false,
        width: 1360,
        height: 400

    });
    //$(".flexme4").flexReload();
}
LoadSearchDetails();
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if (results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}