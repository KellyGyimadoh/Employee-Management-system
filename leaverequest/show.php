<?php
$title = "My Leave Records";
require '../includes/sessions.php';
include '../includes/head.php';

if (
    !isloggedin() || !isset($_SESSION['accounttype']) ||
    !in_array($_SESSION['accounttype'], ['staff', 'admin']) || $_SESSION['userinfo']['status'] !== 1
) {
    header("Location: ../auth/login.php");
    die();
}

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
                <h1 class="page-title">MY LEAVE RECORDS</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">LEAVE REQUEST</li>
                </ol>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="ibox bg-info color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong totalrequestnumber">201</h2>
                            <div class="m-b-5">TOTAL LEAVES REQUESTED</div>
                            <i class="ti-shopping-cart widget-stat-icon"></i>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="ibox bg-warning color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong pendingrequest">1250</h2>
                            <div class="m-b-5"> REQUEST PENDING</div><i class="ti-bar-chart widget-stat-icon"></i>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="ibox bg-success color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong approvedrequest">1250</h2>
                            <div class="m-b-5"> REQUEST APPROVED</div><i class="ti-check-box widget-stat-icon"></i>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="ibox bg-danger color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong rejectedrequest">1250</h2>
                            <div class="m-b-5">REQUEST REJECTED</div><i class="ti-face-sad widget-stat-icon"></i>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Employee Leave Request</div>
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
                                <form method="GET" id="searchLeave">
                                    <input type="date" name="searchdate" class="form-control form-control-sm"
                                        aria-controls="dataTable" value="<?php echo isset($_GET['searchdate'])
                                                                                ? htmlspecialchars($_GET['searchdate']) : ''; ?>">
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        aria-controls="dataTable" placeholder="Search"
                                        value="<?php echo isset($_GET['search'])
                                                    ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <input type="hidden" name="limit">
                                    <input type="hidden" name="page">
                                    <select class="form-control form-control-sm custom-select custom-select-sm" 
                                    name="leavestatus">
                                        <option value="">Filter By</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Approved</option>
                                        <option value="3">Rejected</option>
                                        
                                    </select>&nbsp;
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
                                    <th>Request Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Approved By</th>
                                    <th>Set At</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                   
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>

                                    <th>No</th>
                                   
                                    <th>Request Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Approved By</th>
                                    <th>Set At</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                   
                                </tr>
                            </tfoot>
                            <tbody id="leaveTableBody">


                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                            <ul class="leavepagination pagination">

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
    <script src="../assets/js/userleave.js" type="module">


    </script>
</body>

</html>