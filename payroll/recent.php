<?php
$title = "Payroll";
require '../includes/sessions.php';
include '../includes/head.php';

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "admin") {
    header('location:../auth/login.php');
    die();
}
$startOfLastMonth = date('F 1, Y', strtotime('first day of this month'));
$endOfLastMonth = date('F t, Y', strtotime('last day of this month'));
?>

<body class="fixed-navbar">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php
        include('../includes/header.php');
        include('../includes/sidebar.php');

        ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <?php
            include '../includes/alert.php';
            ?>
            <!-- START PAGE CONTENT-->
            <div class="page-heading">
                <h1 class="page-title">EMPLOYEES PAYROLL FOR <?php echo $startOfLastMonth . " - " . $endOfLastMonth;?></h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">PAYROLL</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Employee Payroll</div>
                    </div>
                    <div class="row m-2 d-flex justify-content-between">
                        <div class="col-md-6 text-nowrap">
                            <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                <label>Show&nbsp;
                                    <select class="form-control form-control-sm custom-select custom-select-sm" id="recordsPerPage">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>&nbsp;
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-md-right dataTables_filter" id="dataTable_filter">
                                <form method="GET" id="searchRecentPayroll">
                                <input type="date" name="searchdate" class="form-control form-control-sm"
                                     aria-controls="dataTable" value="<?php echo isset($_GET['searchdate']) 
                                     ? htmlspecialchars($_GET['searchdate']) : ''; ?>">
                                      <input type="text" name="search" class="form-control form-control-sm"
                                     aria-controls="dataTable" placeholder="Search"
                                      value="<?php echo isset($_GET['search']) 
                                      ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <input type="hidden" name="limit">
                                    <input type="hidden" name="page">
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-body">

                        <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Employee Name</th>
                                    <th>Total Salary</th>
                                    <th>Due Date</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Update Payment Status</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                
                                    <th>No</th>
                                    <th>Employee Name</th>
                                    <th>Total Salary</th>
                                    <th>Due Date</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Update Payment Status</th>
                                </tr>
                            </tfoot>
                            <tbody id="payrollTableBody">


                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                            <ul class="payrollpagination pagination">

                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
            <!-- END PAGE CONTENT-->
            <?php
            include '../includes/footer.php'
            ?>
        </div>
    </div>
    <!-- BEGIN THEME CONFIG PANEL-->

    <!-- END THEME CONFIG PANEL-->
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS-->
    <?php
    include '../includes/scripts.php'
    ?>
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="../assets/js/payrollrecent.js"  type="module"></script>
   
</body>

</html>