/*
* Object Name : Exception 
* Author 	  : Kamal Oberoi
* Version	  : 1.0
* Created Date: 24 December 2013
* Description : This object is used to validate form elements 	
*/
var Exception = {

    /* FormId That is Used validate that Elements */
    siteUrl: 'http://127.0.0.1/multiweld_1/View/',
    //siteUrl : 'http://www.techalay.com/demos/multiweld/View/',       

    validateFormId: '',

    /* Image Icons Folder Path */
    path: this.siteUrl + 'img/',

    /* change this property to use your own error icon images */
    errorImagename: 'error.gif',

    /* change this property to use your own success icon images */
    successImagename: 'succ.gif',

    /* change this property to use your own warning icon images */
    warningImagename: 'warning.gif',

    /* Message Types */
    messageTypes: ["Error", "Success", "Warning"],

    /* Exception Classes */
    //ExceptionClasses: ["required", "email", "password", "url", "phonenumber", "exist"],
	ExceptionClasses: ["required", "email", "password", "url", "phonenumber", "exist", "percentage"],

    /* Elements Array */
    elementsArray: new Array(),

    /* Position Array */
    positionArray: new Array(),

    profilepath: "",

    /* Already Exist Path For Server */
    ServerPath: this.profilepath,

    /* Find Element Position */
    position: function (element) {
        var pos = $(element).position();
        return pos;
    },
    clear: function () {
        $('#' + this.validateFormId + ' .mycustommsg').remove();
    },
    /* traverseing count */
    traversingCount: 0,
    /* Exception Class Message Type like error , success, warning */
    messagetype: '',
    /* check Element Exist */
    exist: function (elementid) {
        return $('#' + Exception.validateFormId + ' #' + elementid).length > 0;
    },
    /* Set Validation Status */
    validStatus: true,
    /* valid Date Status */
    validateDateStatus: true,
    /* status for dateattend */
    validDateAttendStatus: true,
    /* Message Box Styles */
    MessageCss: {
        error: "style='width:165px; padding-top: 4px;color:red;line-height: 13px;font-size:16px;margin-top:-25px;",
        errorImg: "style='margin-right:5px;float:left;'",
        success: "style='width:165px; padding-top: 4px;color:green;line-height: 13px;",
        succImg: "style='margin-right:5px;float:left;'",
        warning: "style='width:165px; padding-top: 4px;color:blue;line-height: 13px;",
        warnImg: "style='margin-right:5px;float:left;'"
    },

    /* Exception Messages Array */
    ExceptionMessagess: {
        required: "Required Field",
        email: "Invalid EmailId",
        Url: "Invalid Url",
        mobileno: "Invalid Mobile No.",
        password: "Password Not Matched",
		percentage: "Invalid percent",
        exist: "Already Exist"
    },

    /* return Array of all elements that in forms with given classes */
    getAllElementArray: function (formid) {
        Exception.elementsArray = new Array();
        this.validateFormId = formid;
        $('#' + this.validateFormId).find('input,textarea,select').each(function () {
            Exception.elementsArray.push(this);
        });
    },
    /* Return Type of Element like text, textarea ,select */
    getElementType: function (objElement) {
        var ele_type = '';

        if ($(objElement).is('input')) {
            ele_type = $(objElement).attr('type');
            return ele_type;
        }
        else if ($(objElement).is('select')) {
            ele_type = 'select';
            return ele_type;
        }
        else if ($(objElement).is('textarea')) {
            ele_type = 'textarea';
            return ele_type;
        }
    },

    /* Validate All Form Elements */
    validate: function (formid) {
        Exception.validStatus = true;
        this.getAllElementArray(formid);
		
        if (this.validateFormId != null) {
            $('#' + this.validateFormId + ' .mycustommsg').remove();
            if (this.elementsArray.length > 0) {
                $.each(this.elementsArray, function (index, value) {

                    if ($(value).attr("class")) {
                        var classnames = $(value).attr("class");
                        var myElement = $(value);
                        var msgHtml = '';
                        var positionobj = '';
                        $.each(Exception.ExceptionClasses, function (key, val) {
                            if (classnames.indexOf(val) != -1) {
                                switch (val) {
                                    case "required":
                                        msgHtml = Exception.required(myElement, Exception.ExceptionMessagess.required);
                                        Exception.SetMessage(msgHtml, myElement);
                                        //Exception.traversingCount++;
                                        return Exception.validStatus;
                                        break;
                                    case "exist":
                                        msgHtml = Exception.AlreadyExist(myElement, Exception.ExceptionMessagess.exist);
                                        Exception.SetMessage(msgHtml, myElement);
                                        //Exception.traversingCount++;
                                        return Exception.validStatus;
                                        break;
                                    case "email":
                                        msgHtml = Exception.email(myElement, Exception.ExceptionMessagess.email);
                                        Exception.SetMessage(msgHtml, myElement);
                                        traversingCount++;
                                        return Exception.validStatus;
                                        break;
                                    case "url":
                                        msgHtml = Exception.url(myElement, Exception.ExceptionMessagess.Url);
                                        Exception.SetMessage(msgHtml, myElement);
                                        //traversingCount++;
                                        return Exception.validStatus;
                                        break;
                                    case "mobileno":
                                        msgHtml = Exception.mobileno(myElement, Exception.ExceptionMessagess.mobileno);
                                        Exception.SetMessage(msgHtml, myElement);
                                        //traversingCount++;
                                        return Exception.validStatus;
                                        break;
                                    case "password":
                                        msgHtml = Exception.password(myElement, Exception.ExceptionMessagess.password);
                                        Exception.SetMessage(msgHtml, myElement);
                                        //traversingCount++;
                                        return Exception.validStatus;
                                        break;
									case "percentage":
                                        msgHtml = Exception.percentage(myElement, Exception.ExceptionMessagess.percentage);
                                        Exception.SetMessage(msgHtml, myElement);
                                        //traversingCount++;
                                        return Exception.validStatus;
                                        break;
                                }
                            }
                        });
                    }

                })
            }
        }
    },
    /* Validate Two Years */
    yearAttend: function (id) {
        Exception.validDateAttendStatus = true;
        var idArray = id.split('_');
        var recordId = "";
        var fromID = "";
        var fromVal = "";
        var toValue = "";
        var element = $('#' + id);
        var elementTo = $('#' + id);
        if (idArray.length > 2) {
            var recordId = idArray[2];
            var fromID = "education_DateAttendFrom_" + recordId;
            var fromVal = $('#' + fromID).val();
            var toValue = $('#' + id).val();

            if (fromVal == 0 || toValue == 0) {
                var html = Exception.getMessageHtml("Both Year Are Required", 'error', element);
                Exception.validDateAttendStatus = false;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
            else if (fromVal > toValue || fromVal == toValue) {
                var html = Exception.getMessageHtml("From Year should be less than To Year", 'error', element);
                Exception.validDateAttendStatus = false;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
            else {
                html = Exception.getMessageHtml("Successfull", 'success', element);
                Exception.validDateAttendStatus = true;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
        }
        else {
            fromID = "education_DateAttendFrom";
            fromVal = $('#' + fromID).val();
            toValue = $('#' + id).val();
            if (fromVal == 0 || toValue == 0) {
                var html = Exception.getMessageHtml("Both Year Are Required", 'error', element);
                Exception.validDateAttendStatus = false;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
            else if (fromVal > toValue || fromVal == toValue) {
                var html = Exception.getMessageHtml("Date Attend From should be less than Date Attend To!", 'error', element);
                Exception.validDateAttendStatus = false;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
            else {
                html = Exception.getMessageHtml("Successfull", 'success', element);
                Exception.validDateAttendStatus = true;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
        }
    },
    /* Date Attend Experienec */
    experienceDateAttend: function (id) {
        Exception.validDateAttendStatus = true;
        var idArray = id.split('_');
        var recordId = "";
        var fromID = "";
        var fromVal = "";
        var toValue = "";
        var element = $('#' + id);
        var elementTo = $('#' + id);
        if (idArray.length > 3) {
            var recordId = idArray[3];
            var fromID = "experience_timePeriod_yearFrom_" + recordId;
            var fromVal = $('#' + fromID).val();
            var toValue = $('#' + id).val();
            if (fromVal == 0 || toValue == 0) {
                var html = Exception.getMessageHtml("Both Year Are Required", 'error', element);
                Exception.validDateAttendStatus = false;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
            else if (fromVal > toValue || fromVal == toValue) {
                var html = Exception.getMessageHtml("Year From should be less than Year To!", 'error', element);
                Exception.validDateAttendStatus = false;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
            else {
                var html = Exception.getMessageHtml("Successfull", 'success', element);
                Exception.validDateAttendStatus = true;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
        }
        else {
            fromID = "experience_timePeriod_yearFrom";
            fromVal = $('#' + fromID).val();
            toValue = $('#' + id).val();
            if (fromVal == 0 || toValue == 0) {
                var html = Exception.getMessageHtml("Both Year Are Required", 'error', element);
                Exception.validDateAttendStatus = false;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
            if (fromVal > toValue || fromVal == toValue) {
                var html = Exception.getMessageHtml("Year From should be less than Year To!", 'error', element);
                Exception.validDateAttendStatus = false;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
            else {
                var html = Exception.getMessageHtml("Successfull", 'success', element);
                Exception.validDateAttendStatus = true;
                Exception.SetMessage(html, elementTo);
                return Exception.validDateAttendStatus;
            }
        }
    },
    /* Validate Date */
    validateDate: function (day_element, month_element, year_element) {
        Exception.validateDateStatus = true;
        if ($(day_element).val() == 0) {
            var html = Exception.getMessageHtml("Please Select Day", 'error', year_element);
            Exception.validateDateStatus = false;
            Exception.SetMessage(html, year_element);
            return Exception.validateDateStatus;
        }
        else {
            validateDateStatus = true;
        }
        if ($(month_element).val() == 0) {
            var html = Exception.getMessageHtml("Please Select Month", 'error', year_element);
            Exception.validateDateStatus = false;
            Exception.SetMessage(html, year_element);
            return Exception.validateDateStatus;
        }
        else {
            validateDateStatus = true;
        }
        if ($(year_element).val() == 0) {
            var html = Exception.getMessageHtml("Please Select Year", 'error', year_element);
            Exception.validateDateStatus = false;
            Exception.SetMessage(html, year_element);
            return Exception.validateDateStatus;
        }
        else {
            Exception.validateDateStatus = true;
            if (Exception.validateDateStatus == true) {
                //Exception.clear();
                var html = Exception.getMessageHtml("Successfull", 'success', year_element);
                Exception.validateDateStatus = true;
                Exception.SetMessage(html, year_element);
                return Exception.validateDateStatus;
            }
        }
        //alert(validateDateStatus);


    },
    /* Required Field Validation */
    required: function (element, message) {
        var elementType = Exception.getElementType(element);
        var html = "";
        switch (elementType) {
            case "text":
                if ($(element).val() == "" || $(element).val() == 'To') {
                    html = Exception.getMessageHtml(message, 'error', element);
                    Exception.validStatus = false;
                    return html;
                }
                else {
                    html = Exception.getMessageHtml(message, 'success', element);
                    Exception.SetStatus();
                    return html;

                }
                break;
            case "select":
                if ($(element).val() == "" || $(element).val() == 0) {
                    html = Exception.getMessageHtml(message, 'error', element);
                    Exception.validStatus = false;
                    return html;
                }
                else {
                    html = Exception.getMessageHtml(message, 'success', element);
                    Exception.SetStatus();
                    return html;
                }
                break;
            case "textarea":
                
                if ($(element).val() == "" || $(element).val() == 'To') {
                    html = Exception.getMessageHtml(message, 'error', element);
                    Exception.validStatus = false;
                    return html;
                }
                else {
                    html = Exception.getMessageHtml(message, 'success', element);
                    Exception.SetStatus();
                    return html;
                }
                break;
        }

    },
    /* Already Exist*/
    AlreadyExist: function (element, messge) {
        var vall = $(element).val();
        if (vall != "") {
            $.ajax({
                url: Exception.ServerPath,
                type: "POST",
                data: { uniqueUsername: vall },
                success: function (jsondata) {
                    //alert(jsondata);
                    if (jsondata.Status == "Exist") {
                        alert(jsondata.Status);
                        var html = Exception.getMessageHtml("Already Exist!", 'error', element);
                        Exception.validStatus = false;
                        return html;
                    }
                    else if (jsondata.Status == "Avail") {
                        var html = Exception.getMessageHtml("Avail", 'success', element);
                        Exception.SetStatus();
                        return html;
                    }
                }
            });
        }
        else {
            var html = Exception.getMessageHtml("Choose Username!", 'error', element);
            Exception.validStatus = false;
            return html;
        }
    },
    /* Set Message */
    SetMessage: function (msg, element) {
        var id = $(element).attr('id');
        var dynamicid = '';
        switch (Exception.messagetype) {
            case Exception.messageTypes[0]:
                dynamicid = 'Exception_' + id;
                if (!Exception.exist(dynamicid)) {
                    $(element).after(msg);
                    Exception.validStatus = false;
                }
                break;
            case Exception.messageTypes[1]:
                dynamicid = 'Success_' + id;
                if (!Exception.exist(dynamicid)) {
                    $(element).after(msg);
                }
                break;
            case Exception.messageTypes[2]:
                dynamicid = 'warning_' + id;
                if (!Exception.exist(dynamicid)) {
                    $(element).after(msg);
                }
                break;

        }

    },
    /* Check Email Id Validation */
    email: function (element, message) {
        if (!this.isEmail($(element).val())) {
            html = Exception.getMessageHtml(message, 'error', element);
            return html;
        }
    },
    /* Check Url Validation */
    url: function (element, message) {
        if (!this.isUrl($(element).val())) {
            html = Exception.getMessageHtml(message, 'error', element);
            return html;
        }
    },
    /* Check Indian 10 Digit Mobile No. Validation */
    mobileno: function (element, message) {
        if (!this.isTenDigitMobile($(element).val())) {
            html = Exception.getMessageHtml(message, 'error', element);
            return html;
        }
    },
    /* Check Password Match Validation */
    password: function (firstelement, secondelement, message) {
        if ($(secondelement).val() != $(firstelement).val()) {
            html = Exception.getMessageHtml(message, 'error', element);
            return html;
        }
    },
    /* Check Email Validation Regular Expression */
    isEmail: function (emailid) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(emailid);
    },
    /* Check Url Validation Regular Expression */
    isUrl: function (url) {
        var regexregex = /^(http:\/\/www\.|https:\/\/www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
        return regex.test(url);
    },
    /**/
    isTenDigitMobile: function (mobileno) {
        var regex = /^[0-9-+]+$/;
        return regex.test(mobileno);
    },
	percentage: function (element, message) {
		var value = $(element).val();
		if($.isNumeric(value))
		{
			value = parseFloat(value);
			if((value < 0)||(value > 100))
			{
				html = Exception.getMessageHtml(message, 'error', element);
				return html;
			}
		}
		else
		{
			html = Exception.getMessageHtml(message, 'error', element);
            return html;
		}
    },
    /* Return the html for particular message type like error messsage , success message, warning message */
    getMessageHtml: function (message, type, element) {
        switch (type) {
            case "error":
                return Exception.getErrorMessageHtml(message, element);
                break;
            case "success":
                return Exception.getSuccessMessageHtml(message, element);
                break;
            case "warning":
                return Exception.getWarningMessageHtml(message, element);
                break;
            default:
                break;
        }
    },
    getErrorMessageHtml: function (message, ele) {
        Exception.messagetype = Exception.messageTypes[0];
        var positionobj = Exception.position(ele);
        var errorMessageHtmlString = '';
        var elewithd = $(ele).width();
        var id = $(ele).attr('id');
        var leftpos = positionobj.left + elewithd;
        leftpos = leftpos + 20;
        var toppos = positionobj.top;
        toppos = toppos + 20;
        errorMessageHtmlString += "<div class='mycustommsg' id='Exception_" + id + "' " + Exception.MessageCss.error + "left:" + leftpos + "px;position:absolute;'>" + message;
        errorMessageHtmlString += "</div>";
        return errorMessageHtmlString;
    },
    getSuccessMessageHtml: function (message, ele) {
        Exception.messagetype = Exception.messageTypes[1];
        var positionobj = Exception.position(ele);
        var errorMessageHtmlString = '';
        var elewithd = $(ele).width();
        var id = $(ele).attr('id');
        var leftpos = positionobj.left + elewithd;
        leftpos = leftpos + 20;
        var toppos = positionobj.top;
        toppos = toppos + 20;
        errorMessageHtmlString += "<div class='mycustommsg' id='Success_" + id + "' " + Exception.MessageCss.success + "left:" + leftpos + "px;position:absolute;'>";
        errorMessageHtmlString += "</div>";
        return errorMessageHtmlString;

    },
    getWarningMessageHtml: function (message, ele) {
        Exception.messagetype = Exception.messageTypes[2];
        var positionobj = Exception.position(ele);
        var errorMessageHtmlString = '';
        var elewithd = $(ele).width();
        var id = $(ele).attr('id');
        var leftpos = positionobj.left + elewithd;
        leftpos = leftpos + 20;
        var toppos = positionobj.top;
        toppos = toppos + 20;
        errorMessageHtmlString += "<div class='mycustommsg' id='warning_" + id + "' " + Exception.MessageCss.warning + "left:" + leftpos + "px;position:absolute;'><img " + Exception.MessageCss.warnImg + " src='" + this.path + this.warningImagename + "'/> " + message;
        errorMessageHtmlString += "</div>";
        return errorMessageHtmlString;
    },
    SetStatus: function () {
        if (Exception.validStatus != false) {
            Exception.validStatus = true;
        }
    }

}
String.prototype.contains = function(it) { return this.indexOf(it) != -1; };
