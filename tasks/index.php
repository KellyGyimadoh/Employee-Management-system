<?php
$title = "All Tasks";
require '../includes/sessions.php';
include '../includes/head.php';

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "admin") {
    header("Location: ../error/error403.php");
    session_destroy();
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
                <h1 class="page-title">All TASKS</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">TASKS</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Employee Tasks</div>
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
                                <form method="GET" id="searchTask">
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
                                    name="taskstatus">
                                        <option value="">Filter By</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Completed</option>
                                        <option value="3">Late</option>
                                        
                                    </select>&nbsp;
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Task Name</th>
                                    <th>Description</th>
                                    <th>Department</th>
                                    <th>Assigned By</th>
                                    <th>Assigned To</th>
                                    <th>Set At</th>
                                    <th>Due Date</th>
                                    <th>Date Completed</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Check</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>

                                    <th>No</th>
                                    <th>Task Name</th>
                                    <th>Description</th>
                                    <th>Department</th>
                                    <th>Assigned By</th>
                                    <th>Assigned To</th>
                                    <th>Set At</th>
                                    <th>Due Date</th>
                                    <th>Date Completed</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Check</th>
                                </tr>
                            </tfoot>
                            <tbody id="taskTableBody">


                            </tbody>
                        </table>
                        </div>  
                    </div>
                    <div class="col-md-6">
                        <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                            <ul class="taskpagination pagination">

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
    <script src="../assets/js/alltask.js" type="module">


    </script>
</body>

</html>