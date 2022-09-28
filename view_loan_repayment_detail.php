<?php
require_once 'model/common.php';
require_once "protect.php";

$uid = $_SESSION["userid"];
$requestId = $_GET["id"];

$_SESSION["reqId"] = $requestId;


$dao = new loanDAO();
$loan = $dao->retrieveLoanByRequestId($requestId);

$dao2 = new usersDAO();
$lender = $dao2->getLenderDetailsById($loan->getLenderId());
$borrower = $dao2->getLenderDetailsById($loan->getBorrowerId());

$dao3 = new repaymentrecordDAO();
$paymentrecords = $dao3->retrieveAllRepaymentByBorrowerId($loan->getBorrowerId(), $loan->getId());

$dao4 = new requestDAO();
$request = $dao4->retrieveRequestInfo($requestId);

$baseAutoRepaymentAmt = $request->getLoanAmount() / $request->getLoanTerm();

$loanTermRemaining = $request->getLoanTerm();
$loanAmtRemaining = $request->getLoanAmount();

if ($paymentrecords != null) {
    if (sizeof($paymentrecords) > 0) {
        $loanTermRemaining -= sizeof($paymentrecords);
        $loanAmtRemaining -= $request->getLoanAmount() / $request->getLoanTerm() * sizeof($paymentrecords);
    }
}

$today = new DateTime('now');
$dateObj = new DateTime($loan->getDateLoan());

while ($today > $dateObj) {
    $dateObj->add(new DateInterval('P30D'));
    $nextAutoRepaymentDate = $dateObj->format('Y-m-d');
}

$monthlyTotalAutoPaymentAmt = $baseAutoRepaymentAmt * ((100 + $loan->getInterestRate() + 0.35) * 1 / 100);

$totalFullLoanAmtPayable = $loanAmtRemaining * ((100 + $loan->getInterestRate() + 0.35 - 0.20) * 1 / 100);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <style>
        .borderclass {
            border-bottom: 1px solid #4e73df;
            border-left: 1px solid #4e73df;
            border-radius: 5px;
            padding: 10px;
        }

        .expandbtn {
            background-color: #4e73df;
            float: right;
        }

        .expandbtn :hover {
            background-color: #4e73df;
        }

        h3 {
            display: inline-block;
        }

        h2 {
            margin: auto;
            text-decoration: underline;
        }
    </style>

    <title>Loan Details</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./asset/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Lendella<sup>P2P</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="home.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Home</span></a>
            </li>

            <!-- Divider: user can be both a borrower and lender -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Loan Requests (shows all loan requests of the user)-->
            <li class="nav-item">
                <a class="nav-link" href="view_my_request.php">
                    <!--<i class="fas fa-fw fa-chart-area"></i>-->
                    <span>Loan Requests</span>
                </a>
            </li>

            <!-- Nav Item - Loans Offered (shows all loan offers the user has made)-->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <!--<i class="fas fa-fw fa-table"></i>-->
                    <span>Loan Offers</span>
                </a>
            </li>

            <!-- Nav Item - Loans Offered (shows all loans that have been issued with the user as borrower/lender)-->
            <li class="nav-item">
                <a class="nav-link" href="view_loan_repayment.php">
                    <!--<i class="fas fa-fw fa-chart-area"></i>-->
                    <span>Loans Issued</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">Your loan request has been made.</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        Your loan request was fulfilled!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        You are late for a loan repayment, please make a repayment asap.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="./profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>

                <main style="margin-top: 10px;">
                    <div class="container-fluid" id="app">
                        <div class="row">
                            <h2> Loan Details Page</h2>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Loan Details</h3>
                                <button class="btn btn-primary expandbtn" data-toggle="collapse" href="#collapseLoanDetails" type="button" aria-expanded="false" aria-controls="collapseLoanDetails" data-target="#collapseLoanDetails">Expand</button>
                                <hr>
                                <?php

                                echo "<div class='collapse show' id='collapseLoanDetails'>
                            <div class='row borderclass'>                                        
                                <div class='col-sm-8'>
                                    <b>Title of Loan</b><br>
                                    <b>Loan Base Amount</b><br>
                                    <b>Loan Term</b><br>
                                    <b>Loan Start Date</b><br>
                                    <b>Remaining Term</b><br>
                                    <b>Remaining Base Amount</b>
                                </div>
                                <div class='col-auto'>
                                    {$request->getLoanTitle()}<br>
                                    {$request->getCurrency()} {$request->getLoanAmount()}<br>
                                    {$request->getLoanTerm()}<br>
                                    {$loan->getDateLoan()}<br>
                                    $loanTermRemaining<br>
                                    {$request->getCurrency()} <label id='loanAmtRemaining'>$loanAmtRemaining</label><br>
                                </div>
                            </div>
                            <label id='requestId' style='visibility:hidden'>{$requestId}</label>
                            <label id='loanId' style='visibility:hidden'>{$loan->getId()}</label>
                            <label id='borrowerId' style='visibility:hidden'>{$borrower->getId()}</label>
                            <label id='borrowerUId' style='visibility:hidden'>$borrowerUID</label>
                        </div>";
                                ?>
                                <br>
                                <!-- <h3>Lender Details</h3>
                    <button class="btn btn-primary expandbtn" data-toggle="collapse" href="#collapseLenderDetails" type="button" aria-expanded="false" aria-controls="collapseLenderDetails" data-target="#collapseLenderDetails">Expand</button>
                    <hr>
                    //<php 

                    // echo "<div class='collapse' id='collapseLenderDetails'>
                            // <div class='row borderclass'>                                        
                                // <div class='col-sm-8'>
                                    // <b>Lender Name</b><br>
                                    // <b>Contact No.</b><br>
                                    // <b>Email Address</b><br>
                                // </div>
                                // <div class='col-auto'>
                                    // {$lender->getName()}<br>
                                    // {$lender->getPhone()}<br>
                                    // {$lender->getEmail()}<br>
                                // </div>
                            // </div>
                            // <label id='lenderName' style='visibility:hidden'>{$lender->getName()}</label>
                        // </div>";
                    //>
                    <br> -->

                                <h3>Repayment Summary</h3>
                                <button class="btn btn-primary expandbtn" data-toggle="collapse" href="#collapseSummary" type="button" aria-expanded="false" aria-controls="collapseSummary" data-target="#collapseSummary">Expand</button>
                                <hr>
                                <?php
                                $payFullLoanHref = "view_loan_repayment.php";
                                $loanListHref = "view_loan_repayment.php";
                                echo "  <div class='collapse' id='collapseSummary'>
                            <div class='row borderclass'>
                                <div class='col-12'><h5 style='font-weight:bold;text-decoration:underline;'>Auto Monthly Payment</h5> </div> 
                                                                   
                                <div class='col-sm-8'>
                                    <b>Next Auto Repayment Date</b><br>
                                    <b>Repayment Amount(Monthly)</b><br>
                                    <b>Interest Rate</b><br>
                                    <b>Service Charge</b><br>
                                    <b>Monthly total amount payable(Including Interest & Service Charge)</b><br>
                                </div>
                                <div class='col-auto'>
                                    $nextAutoRepaymentDate<br>
                                    {$request->getCurrency()} $baseAutoRepaymentAmt<br>
                                    {$loan->getInterestRate()}%<br>
                                    0.35%<br>
                                    {$request->getCurrency()} $monthlyTotalAutoPaymentAmt <br>
                                </div>
                            </div><br>";
                                ?>
                                <?php
                                echo "  
                            <div class='row borderclass'>
                                <div class='col-12'><h5 style='font-weight:bold;text-decoration:underline;'>Full Loan Payment</h5> </div> 
                    
                                <div class='col-sm-8'>
                                    <b>Interest Rate</b><br>
                                    <b>Service Charge</b><br>
                                    <b>Early Repayment Deduction</b><br>
                                    <b>Full loan amount payable(Including Interest & Service Charge)</b><br>
                                </div>
                                <div class='col-auto'>
                                    {$loan->getInterestRate()}%<br>
                                    0.35%<br>
                                    -0.20%<br>
                                    {$request->getCurrency()} <label id='paymentAmt'>$totalFullLoanAmtPayable</label> <br>
                                </div>
                            </div>
                        </div><br>
                        
                        <div class='col-auto mt-5'>
                            <a class='btn btn-primary' style='float:left;' href='$loanListHref'  role='button'>Back</a>
                            <a class='btn btn-primary' style='float:right;' href='#' onclick='PayFullLoan({$borrower->getPhone()},{$borrower->getPin()})' role='button'>Pay Full Loan</a>
                        </div>";

                                ?>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script>
            function PayFullLoan(accId, phoneNo) {
                var confirmed = confirm("Are you sure you want to pay full loan?")
                if (confirmed) {
                    // set service header values
                    var serviceName = "creditTransfer";
                    var userID = "NgChunWang";
                    var pin = "578701";
                    var otp = "999999";
                    var accountFrom = accId;
                    var accountTo = "0000008239"
                    var amount = document.getElementById("paymentAmt").innerHTML;
                    var narrative = "Repayment to TBank Repayment Account (Loan ID:" + document.getElementById("requestId").innerHTML + ")";
                    // set request parameters
                    var headerObj = {
                        Header: {
                            serviceName: serviceName,
                            userID: userID,
                            PIN: pin,
                            OTP: otp
                        }
                    };
                    var contentObj = {
                        Content: {
                            accountFrom: accountFrom,
                            accountTo: accountTo,
                            transactionAmount: amount,
                            transactionReferenceNumber: '1230',
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
                    xmlHttp.open("POST", "http://tbankonline.com/SMUtBank_API/Gateway" + "?Header=" + header + "&Content=" + content, true);
                    xmlHttp.timeout = 5000;
                    // setup http event handlers
                    xmlHttp.onreadystatechange = function() {
                        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                            responseObj = JSON.parse(xmlHttp.responseText);
                            serviceRespHeader = responseObj.Content.ServiceResponse.ServiceRespHeader;
                            globalErrorID = serviceRespHeader.GlobalErrorID;
                            console.log(responseObj.Content)
                            //depositAccount = responseObj.Content.ServiceResponse.DepositAccount;

                            //console.log(depositAccount)
                            if (globalErrorID === "010041") {
                                alert("OTP Timeout");
                                return;
                            } else if (globalErrorID !== "010000") {
                                alert("Error Please Try Again");
                                return;
                            } else {
                                clearloanrepayment();
                                sendToPlatform();
                                sendSMS(phoneNo);
                                
                            }
                        }
                    }
                    xmlHttp.send();


                   
                }
            }

            // function sendToPlatform(){
                // //send to platform acc
                // var serviceName = "creditTransfer";
                    // var userID = "S9711111A";
                    // var pin = "111111"
                    // var otp = "999999";
                    // var accountFrom = "0000008240";
                    // var accountTo = "0000008239"
                    // var amount = parseInt(document.getElementById("loanAmtRemaining").innerHTML) * 0.15;
                    // var narrative = "Platform Charges";
                    // // set request parameters
                    // var headerObj = {
                        // Header: {
                            // serviceName: serviceName,
                            // userID: userID,
                            // PIN: pin,
                            // OTP: otp
                        // }
                    // };
                    // var contentObj = {
                        // Content: {
                            // accountFrom: accountFrom,
                            // accountTo: accountTo,
                            // transactionAmount: amount,
                            // transactionReferenceNumber: '1230',
                            // narrative: narrative
                        // }
                    // };
                    // var header = JSON.stringify(headerObj);
                    // var content = JSON.stringify(contentObj);
                    // // setup http request
                    // var xmlHttp = new XMLHttpRequest();
                    // if (xmlHttp === null) {
                        // alert("Browser does not support HTTP request.");
                        // return;
                    // }
                    // xmlHttp.open("POST", "http://tbankonline.com/SMUtBank_API/Gateway" + "?Header=" + header + "&Content=" + content, true);
                    // xmlHttp.timeout = 5000;
                    // // setup http event handlers
                    // xmlHttp.onreadystatechange = function() {
                        // if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                            // responseObj = JSON.parse(xmlHttp.responseText);
                            // serviceRespHeader = responseObj.Content.ServiceResponse.ServiceRespHeader;
                            // globalErrorID = serviceRespHeader.GlobalErrorID;
                            // console.log(responseObj.Content)
                            // //depositAccount = responseObj.Content.ServiceResponse.DepositAccount;
                            // //console.log(depositAccount)
                            // if (globalErrorID === "010041") {
                                // alert("OTP Timeout");
                                // return;
                            // } else if (globalErrorID !== "010000") {
                                // alert("Error Please Try Again(Platform Error)");
                                // alert(serviceRespHeader.ErrorDetails+"/"+globalErrorID);
                                // return;
                            // } else {
                            // }
                        // }
                    // }
                    // xmlHttp.send();
            // }

            function clearloanrepayment() {
                var reqId = document.getElementById("requestId").innerHTML;
                var loanId = document.getElementById("loanId").innerHTML;
                var paymentAmt = document.getElementById("paymentAmt").innerHTML;
                var borrowerId = document.getElementById("borrowerId").innerHTML;
                var para = "reqId=" + reqId + "&loanId=" + loanId + "&paymentAmt=" + paymentAmt + "&borrowerId=" + borrowerId;
                var xhttp = new XMLHttpRequest();
                xhttp.open("Post", "clear_loan_repayment.php", true);
                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //window.location.href = "clear_laon_repayment.php?id="+document.getElementById("requestId").innerHTML;
                        window.location.href = "repayment_confirmation.php";
                    }
                };
                xhttp.send(para);
            }

            function sendSMS(mobileNumber) {
                // set service header values
                var serviceName = "sendSMS";
                var userID = "S9711111A";
                var PIN = "111111";

                // get and validate form values
                var message = "Your have cleared your loan succuessfully! Please check using our website.";

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
        </script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>