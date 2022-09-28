<?php
require_once 'model/common.php';
require_once "protect.php";

$uid = $_SESSION["userid"];
$username = $_SESSION["username"];

$requestId = $_GET["id"];

$_SESSION["reqId"] = $requestId;


$dao = new loanDAO();
$loan = $dao->retrieveLoanByRequestId($requestId);

$dao2 = new usersDAO();
$lender = $dao2->getLenderDetailsById($loan->getLenderId());
$borrower = $dao2->getLenderDetailsById($loan->getBorrowerId());

$borrower->getUserid();

$dao3 = new repaymentrecordDAO();
$paymentrecords = $dao3->retrieveAllRepaymentByBorrowerId($loan->getBorrowerId(), $loan->getId());

$dao4 = new requestDAO();
$request = $dao4->retrieveRequestInfo($requestId);

$loanTerm = $request->getLoanTerm();
$startDate = $loan->getDateLoan();
$maturityDate = $dao4->getMaturityDate($startDate, $loanTerm);

$loanAmt = $request->getLoanAmount();
$loanAmtRemaining = $loanAmt * (1 + ($request->getInterestRate() / 100));
if (!empty($paymentrecords)) {
    foreach ($paymentrecords as $paymentrecord) {
        $loanAmtRemaining -= $paymentrecord->getPaymentAmt();
    }
}

$loanListHref = "view_loan_repayment.php";

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

    <title>Lendella - View Loan Details</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./asset/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- header -->
        <?php require_once "header.php"; ?>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                if (isset($uid)) {
                    echo "<span class='mr-2 d-none d-lg-inline text-gray-600 small'>$username</span>
                        ";
                } ?>
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
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
        <!-- End of Header -->


        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Loan Details</h1>
            </div>
            <?php echo "
                        <table class='table'>
                        <thead>
                          <tr>
                            <th scope='col'>Loan Title</th>
                            <th scope='col'>Loan Amount</th>
                            <th scope='col'>Loan Term</th>
                            <th scope='col'>Interest Rate</th>
                            <th scope='col'>Loan Start Date</th>
                            <th scope='col'>Loan Maturity Date</th>
                            <th scope='col'>Remaining Loan Amount Payable</th>
                          </tr>
                        </thead>
                        <tbody>
                        <tr>
                          <td>{$request->getLoanTitle()}</td>
                          <td>{$request->getCurrency()} {$request->getLoanAmount()}</td>
                          <td>{$request->getLoanTerm()}</td>
                          <td>{$request->getInterestRate()}
                          <td>{$loan->getDateLoan()}</td>
                          <td>{$maturityDate}</td>
                          <td>{$request->getCurrency()} <label id='loanAmtRemaining'>$loanAmtRemaining</label></td>

                        </tr>
                        </tbody>
                      </table>
                      <label id='requestId' style='visibility:hidden'>{$requestId}</label>
                      <label id='loanId' style='visibility:hidden'>{$loan->getId()}</label>
                      <label id='borrowerId' style='visibility:hidden'>{$borrower->getId()}</label>
                      <label id='borrowerUID' style='visibility:hidden'>{$borrower->getUserid()}</label>
                    "; ?>

            <?php
            if (empty($paymentrecords)) {
                echo "<i class='fas fa-exclamation-triangle'></i>
                            No repayment records were found. ";
            } else {
                echo "
                            <!-- DataTales Example -->
                                <div class='card shadow mb-4'>
                                    <div class='card-body'>
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                                                <thead>
                                                    <tr>
                                                        <th>Payment Amount</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>";

                foreach ($paymentrecords as $paymentrecord) {

                    echo "<tr>
                                    <td>{$paymentrecord->getPaymentAmt()}</td>
                                    <td>{$paymentrecord->getPaymentDate()}</td>
                                </tr>";
                }

                echo "</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class='col-auto mt-5'>
                            <a class='btn btn-primary' style='float:left;' href='$loanListHref'  role='button'>Back</a>
                            <a class='btn btn-primary' style='float:right;' onclick='PayFullLoan({$borrower->getAccId()},{$borrower->getPhone()},{$borrower->getPin()})' role='button'>Pay Full Loan</a>
                        </div>";
            }
            ?>
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php require_once "footer.php"; ?>

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <script>
        function PayFullLoan(accId, phoneNo, Pin) {
            var confirmed = confirm("Are you sure you want to pay full loan?")
            if (confirmed) {
                // set service header values
                var serviceName = "creditTransfer";
                var userID = document.getElementById("borrowerUID").innerHTML;
                var pin = Pin;
                var otp = "999999";
                var accountFrom = accId;
                var accountTo = "0000008239"
                var amount = document.getElementById("loanAmtRemaining").innerHTML;
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
                            alert(serviceRespHeader.ErrorDetails);
                            return;
                        } else {
                            clearloanrepayment();
                            //sendToPlatform();
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
            var paymentAmt = document.getElementById("loanAmtRemaining").innerHTML;
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php require_once "logoutmodal.php"; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>