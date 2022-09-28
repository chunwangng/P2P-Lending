function addBeneficiary(userID, PIN, AccountID) {
    // set service header values
    var serviceName = "addBeneficiary";
    var Description = 'Disbursement / Payout';

    // set request parameters

    var headerObj2 = {
        Header: {
            serviceName: serviceName,
            userID: userID,
            PIN: PIN,
            OTP: '999999'
        }
    };

    var contentObj2 = {
        Content: {
            AccountID: AccountID,
            Description: Description
        }
    };

    var header2 = JSON.stringify(headerObj2);
    var content2 = JSON.stringify(contentObj2);

    // setup http request

    var xmlHttp2 = new XMLHttpRequest();

    if (xmlHttp2 === null) {
        alert("Browser does not support HTTP request.");
        return;
    }

    xmlHttp2.open("POST", "http://tbankonline.com/SMUtBank_API/Gateway" + "?Header=" + header2 + "&Content=" + content2, false);
    var Result = null;

    // setup http event handlers
    xmlHttp2.onreadystatechange = function() {
        if (xmlHttp2.readyState === 4 && xmlHttp2.status === 200) {
            responseObj2 = JSON.parse(xmlHttp2.responseText);
            serviceRespHeader2 = responseObj2.Content.ServiceResponse.ServiceRespHeader;
            globalErrorID = serviceRespHeader2.GlobalErrorID;

            Result = true;
        } else {
            Result = false;
        }
    };

    // send the http request
    xmlHttp2.send();
    return Result;
}

function creditTransfer(accountFrom, accountTo, transactionAmount, userID, PIN) {
    //userID = "S9711111A";
    //PIN = "111111";

    var serviceName = "creditTransfer";
    // get and validate form values

    //var accountFrom = "7650";
    //var accountTo = "7646";
    //var transactionAmount = "50";
    var transactionReferenceNumber = "444";
    var narrative = "Credit Transfer";

    console.log(accountFrom, accountTo);
    console.log(userID, PIN);
    var headerObj = {
        Header: {
            serviceName: serviceName,
            userID: userID,
            PIN: PIN,
            OTP: "999999"
        }
    };

    var contentObj = {
        Content: {
            accountFrom: accountFrom,
            accountTo: accountTo,
            transactionAmount: transactionAmount,
            transactionReferenceNumber: transactionReferenceNumber,
            narrative: narrative
        }
    };

    var header = JSON.stringify(headerObj);
    var content = JSON.stringify(contentObj);

    // setup http request
    var xmlHttp = new XMLHttpRequest();
    if (xmlHttp === null) {
        alert("Browser does not support HTTP request.");
        return;
    }

    xmlHttp.open("POST", "http://tbankonline.com/SMUtBank_API/Gateway?Header=" + header + "&Content=" + content, false);
    var creditTransferResult = null;
    // setup http event handlers
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            responseObj = JSON.parse(xmlHttp.responseText);
            serviceRespHeader = responseObj.Content.ServiceResponse.ServiceRespHeader;
            globalErrorID = serviceRespHeader.GlobalErrorID;

            // get data
            transactionID = responseObj.Content.ServiceResponse.TransactionID._content_;
            balanceBefore = responseObj.Content.ServiceResponse.BalanceBefore._content_;
            balanceAfter = responseObj.Content.ServiceResponse.BalanceAfter._content_;

            // display data
            // document.getElementById("transactionID").value = transactionID;
            // document.getElementById("balanceBefore").value = addCommas(balanceBefore);
            // document.getElementById("balanceAfter").value = addCommas(balanceAfter);
            console.log(transactionID, balanceBefore, balanceAfter, "IT WORKS");

            if (transactionID == "0000000000") {
                return false;
            }

            creditTransferResult = true;
        } else {
            creditTransferResult = false;
        }
    };

    // send the http request
    xmlHttp.send();
    return creditTransferResult;
}

function sendSMS(userID, PIN, mobileNumber) {
    // set service header values
    var serviceName = "sendSMS";
    //var userID = "S9711111A";
    //var PIN = "111111";

    // get and validate form values
    //var mobileNumber = "6597591930";
    var message = "Your loan request has been matched! We have credited the amount to your account.";

    // set request parameters
    var headerObj = {
        Header: {
            serviceName: serviceName,
            userID: userID,
            PIN: PIN,
            OTP: "999999"
        }
    };

    var contentObj = {
        Content: {
            mobileNumber: mobileNumber,
            message: message
        }
    };

    var header = JSON.stringify(headerObj);
    var content = JSON.stringify(contentObj);

    // setup http request
    var xmlHttp = new XMLHttpRequest();
    if (xmlHttp === null) {
        alert("Browser does not support HTTP request.");
        return;
    }

    xmlHttp.open("POST", "http://tbankonline.com/SMUtBank_API/Gateway?Header=" + header + "&Content=" + content, false);
    statusSms = null;

    // setup http event handlers
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            responseObj = JSON.parse(xmlHttp.responseText);
            serviceRespHeader = responseObj.Content.ServiceResponse.ServiceRespHeader;
            globalErrorID = serviceRespHeader.GlobalErrorID;

            if (globalErrorID === "010041") {
                return;
            } else if (globalErrorID !== "010000") {
                return serviceRespHeader.ErrorDetails;
            }

            statusSMS = true;
        } else {
            statusSMS = false;
        }
    };

    // send the http request
    xmlHttp.send();
    return statusSMS;
}

function addDailyLimit(userID, PIN, accountID) {
    // set service header values
    var serviceName = "updateCustomerPreference";

    var MSViaEmail = true;
    var MSViaSnailMail = false;
    var RPViaEmail = false;
    var RPViaSMS = false;
    var RMViaPhone = true;
    var RMViaEmail = false;
    var RMViaSMS = false;

    var AccountID = accountID;
    var TriggerType = 'Daily Limit';
    var Amount = '99999999';
    var TViaEmail = false;
    var TViaSMS = false;
    var TriggerList = [];


    TriggerList.push({
        AccountID: AccountID,
        TriggerType: TriggerType,
        Amount: Amount,
        ViaEmail: TViaEmail,
        ViaSMS: TViaSMS
    })

    var headerObj = {
        Header: {
            serviceName: serviceName,
            userID: userID,
            PIN: PIN,
            OTP: "999999"
        }
    };
    var msObj = {
        ViaEmail: MSViaEmail,
        ViaSnailMail: MSViaSnailMail
    };
    var rpObj = {
        ViaEmail: RPViaEmail,
        ViaSMS: RPViaSMS
    };
    var rmObj = {
        ViaPhone: RMViaPhone,
        ViaEmail: RMViaEmail,
        ViaSMS: RMViaSMS
    };
    var contentObj = {
        Content: {
            MonthlyStatement: msObj,
            RedeemPromotion: rpObj,
            RMModeOfCommunication: rmObj,
            TriggerList: TriggerList
        }
    };

    var header = JSON.stringify(headerObj);
    var content = JSON.stringify(contentObj);

    // setup http request
    var xmlHttp = new XMLHttpRequest();
    if (xmlHttp === null) {
        alert("Browser does not support HTTP request.");
        return;
    }

    xmlHttp.open("POST", "http://tbankonline.com/SMUtBank_API/Gateway" + "?Header=" + header + "&Content=" + content, false);
    // xmlHttp.timeout = 5000;
    var Result = null;


    // setup http event handlers

    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            responseObj = JSON.parse(xmlHttp.responseText);
            serviceRespHeader = responseObj.Content.ServiceResponse.ServiceRespHeader;
            globalErrorID = serviceRespHeader.GlobalErrorID;
            Result = true
        } else {
            Result = false;
        }
    };
    // xmlHttp.ontimeout = function(e) {
    //     showErrorModal("Timeout invoking API.");
    //     return;
    // };
    // send the http request
    xmlHttp.send();
    return Result;
}