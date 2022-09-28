<?php
require_once "model/common.php";
require_once "protect.php";

$uid = $_SESSION["userid"];
$username = $_SESSION["username"];

$dao = new loanDAO();
$loanRecords = $dao->retrieveAll($uid);



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

    <title>Lendella - View My Loan</title>

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
                <h1 class="h3 mb-0 text-gray-800">Loans Issued</h1>
            </div>

            <?php
            if (empty($loanRecords)) {
                echo "<i class='fas fa-exclamation-triangle'></i>
                            No loan offers found. ";
            } else {
                echo "
                            <!-- DataTales Example -->
                                <div class='card shadow mb-4'>
                                    <div class='card-body'>
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                                                <thead>
                                                    <tr>
                                                        <th>Loan Title</th>
                                                        <th>Loan Amount</th>
                                                        <th>Loan Term</th>
                                                        <th>Interest Rate</th>
                                                        <th>Risk Grade</th>
                                                        <th>Purpose</th>
                                                        <th>Start Date</th>
                                                        <th>Maturity Date</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>";

                            $dao2 = new requestDAO();
                            foreach ($loanRecords as $loanRecord) {
                                $request = $dao2->retrieveRequestInfo($loanRecord->getRequestId());
                                $nextPageHref = "view_loan_issued_details.php?id={$loanRecord->getRequestId()}";
                                $interest_rate = $request->getInterestRate();
                                $riskgrade = $dao2->getRiskGrade($interest_rate);
                                $loanTerm = $request->getLoanTerm();
                                $startDate = $loanRecord->getDateLoan();
                                $maturityDate = $dao2->getMaturityDate($startDate, $loanTerm);

                                echo "<tr>
                                    <td>{$request->getLoanTitle()}</td>
                                    <td>{$request->getCurrency()} {$request->getLoanAmount()}</td>
                                    <td>{$request->getLoanTerm()}</td>
                                    <td>$interest_rate%</td>
                                    <td>$riskgrade</td>
                                    <td>{$request->getLoanPurpose()}</td>
                                    <td>{$loanRecord->getDateLoan()}</td>
                                    <td>{$maturityDate}</td>
                                    <td><a href='$nextPageHref' class='btn btn-sm btn-primary shadow-sm'>View</a></td>
                                </tr>";
                }

                echo "</tbody>
                                    </table>
                                </div>
                            </div>
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