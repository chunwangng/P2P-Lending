<?php
require_once 'model/common.php';
require_once "protect.php";

$uid = $_SESSION["userid"];
$username = $_SESSION["username"];
$requestId = $_GET["id"];

$dao = new requestDAO();
$request = $dao->retrieveRequestInfo($requestId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lendella - View Loan Request</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./asset/css/sb-admin-2.min.css" rel="stylesheet">

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
                <h1 class="h3 mb-0 text-gray-800">Offer Loan</h1>
            </div>

            <div class='card shadow mb-4'>
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Loan Information</h6>
                </div>
                <div class='card-body'>
                    <?php
                    $borrowerId = $request->getBorrowerId();
                    $dao2 = new offerDAO();
                    $total_offered = $dao2->retrieveTotalOfferedAmount($requestId);
                    $act_loan_amount = (int)$request->getLoanAmount();
                    $remaining_to_fund = (int)$request->getLoanAmount() - (int)$total_offered;
                    $interest_rate = $request->getInterestRate();
                    //$dao3 = new usersDAO();
                    //$interest_rate = $dao3->retrieveInterestRate($borrowerId);

                    echo "<div class='row'>                                        
                                        <div class='col-sm-8'>
                                            <b>Loan Title</b><br>
                                            <b>Loan Amount</b><br>
                                            <b>Loan Term</b><br>
                                            <b>Interest Rate</b><br>
                                            <b>Loan Purpose</b><br>
                                            <b>Remaining Offer</b>
                                        </div>
                                        <div class='col-auto'>
                                            {$request->getLoanTitle()}<br>
                                            {$request->getCurrency()} {$request->getLoanAmount()}<br>
                                            {$request->getLoanTerm()}<br>
                                            {$request->getInterestRate()}%<br>
                                            {$request->getLoanPurpose()}<br>
                                            {$request->getCurrency()} $remaining_to_fund<br>
                                        </div>
                                    </div>";
                    ?>
                    <hr>

                    <form action="process_supply_loan.php" method="POST">
                        <div class="row">
                            <div class="col-6 form-check text-center">
                                <input class="form-check-input" type="radio" name="radio" value="full" id="full_amount" onclick="show_for_full()">
                                <label class="form-check-label" for="full_amount">Supply Full Amount</label>
                            </div>
                            <div class="col-6 form-check text-center">
                                <input class="form-check-input" type="radio" name="radio" value="partial" id="partial_amount" onclick="show_for_partial()">
                                <label class="form-check-label" for="partial_amount">Supply Partial Amount</label>
                            </div>
                        </div>

                        <div class="row my-5" id="form_partial" style="display: none;">
                            <label for="amount">How much are you offering?</label>
                            <input type="number" id="amount" name="amount" class="form-control form-control-user" min="1000" max="<?= $remaining_to_fund ?>" placeholder="Minimum 1000" step="1000">
                            <input type="hidden" name="requestid" value="<?= $requestId ?>">
                            <input type="hidden" name="borrowerid" value="<?= $borrowerId ?>">
                            <input type="hidden" name="interest_rate" value="<?= $interest_rate ?>">
                            <input type="hidden" name="loan_amount" value="<?= $remaining_to_fund ?>">
                            <input type="hidden" name="act_loan_amount" value="<?= $act_loan_amount ?>">
                            <button type="submit" class="btn btn-sm btn-primary my-4" name="submit">Submit</button>
                        </div>

                        <div class="row justify-content-center my-5" id="form_full" style="display: none;">
                            <input type="hidden" name="requestid" value="<?= $requestId ?>">
                            <input type="hidden" name="borrowerid" value="<?= $borrowerId ?>">
                            <input type="hidden" name="interest_rate" value="<?= $interest_rate ?>">
                            <input type="hidden" name="loan_amount" value="<?= $remaining_to_fund ?>">
                            <input type="hidden" name="act_loan_amount" value="<?= $act_loan_amount ?>">
                            <button type="submit" class="btn btn-sm btn-primary" name="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php require_once "footer.php"; ?>

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <script>
        function show_for_partial() {
            $element = document.getElementById("form_full");

            if ($element.style.display == "") {
                $element.style.display = "none";
            }

            $element = document.getElementById("form_partial");
            $element.style.display = "";
        }

        function show_for_full() {
            $element = document.getElementById("form_partial");

            if ($element.style.display == "") {
                $element.style.display = "none";
            }

            $element = document.getElementById("form_full");
            $element.style.display = "";
        }
    </script>

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