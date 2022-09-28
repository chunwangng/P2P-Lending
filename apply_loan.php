<?php
    require_once "model/common.php";
    require_once "protect.php";

    $uid = $_SESSION["userid"];
    $username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lendella - Apply Loan</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./asset/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

                <!-- header -->
                <?php require_once "header.php";?>

                <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
                                if (isset($uid)){
                                    echo "<span class='mr-2 d-none d-lg-inline text-gray-600 small'>$username</span>
                                ";}?>
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#">
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
                        <h1 class="h3 mb-0 text-gray-800">Apply Loan</h1>
                    </div>

                    <!-- Loan Form -->
                    <div class="card shadow mb-4 p-5">
                        <!-- <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                        </div> -->

                        <form action="process_new_loan.php" method="POST">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="loan_title" name="loan_title" maxlength="20"
                                        placeholder="Loan Title">
                                </div>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control form-control-user" id="amount" name="amount" min="1000" step="1000"
                                        placeholder="Loan Amount">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label for="currency" class="form-label">Currency </label>
                                    <select class="form-control form-control-user" name="currency">
                                        <option value="SGD" selected>SGD</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="loan_term" class="form-label">Loan Term</label>
                                    <select class="form-control form-control-user" name="loan_term">
                                        <option value="12" selected>12</option>
                                        <option value="24">24</option>
                                        <option value="36">36</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea class="form-control form-control-user" name="loan_purpose" id="loan_purpose" rows="3" maxlength="100" placeholder="Loan Purpose (Max. 100 characters)"></textarea>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-sm btn-primary shadow-sm" type="submit" name="submit">Submit</button>
                                <a href="home.php" class="btn btn-sm btn-danger shadow-sm" name="submit" id="submit">Cancel</a>
                            </div>
                            <!-- <label for="loan_title" class="form-label">Title of Loan</label>
                            <input class="form-control" type="text" id="loan_title" name="loan_title" placeholder="" aria-label="default input example"> -->

                            <!-- <label for="amount">Loan Amount</label>
                            <input type="number" id="amount" name="amount" class="form-control" min="1000" placeholder="Minimum 1000" step="1000"> -->

                            <!-- <label for="currency" class="form-label">Currency </label>
                            <select class="form-select" name="currency" aria-label="Default select example">
                                <option value="SGD" selected>SGD</option>
                                <option value="USD">USD</option>
                            </select>

                            <label for="loan_term" class="form-label">Loan Term</label>
                            <select class="form-select" name="loan_term" aria-label="Default select example">
                                <option value="12" selected>12</option>
                                <option value="24">24</option>
                                <option value="36">36</option>
                            </select><br> -->

                            <!-- <label for="loan_purpose" class="form-label">Purpose of Loan</label>
                            <textarea class="form-control" name="loan_purpose" id="loan_purpose" rows="3" maxlength="100"></textarea> -->

                            <!-- <button type="submit" name="submit" class="btn btn-primary">Submit</button> -->
                        </form>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Lendella 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

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