<?php
$title = isset($_SESSION['departmentdetails'])? htmlspecialchars($_SESSION['departmentdetails']['name'].' '. 'Department Portal'):'Portal';
require '../includes/sessions.php';
include '../includes/head.php';

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "admin") {
    header('location:../auth/login.php');
    die();
}
$allowed=checkAccount(['admin']);
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
                <h1 class="page-title"><?php echo $_SESSION['departmentdetails']['name'] ?> Department Profile</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">DEPARTMENT PROFILE</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Department Details</div>
                    </div>

                    <div class="ibox-body">

                        <div class="row mb-4">
                            <div class="col-md-6 text-nowrap">
                                <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                    <label>Show&nbsp;
                                        <select class="form-control form-control-sm custom-select
                                         custom-select-sm" id="recordsPerPage">
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
                                    <form method="GET" id="searchdepartment">
                                        <input type="search" name="search" class="form-control form-control-sm" 
                                        aria-controls="dataTable" placeholder="Search"
                                         value="<?php echo isset($_GET['search']) ? 
                                         htmlspecialchars($_GET['search']) : ''; ?>">
                                        <input type="hidden" name="limit">
                                        <input type="hidden" name="page">
                                        <input type="hidden" name="deptid" id="deptid" 
                                        value="<?php echo htmlspecialchars($_SESSION['departmentdetails']['id'])?>">
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Search</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col d-flex justify-content-between">
                                <div>
                                    <h3 class="p-2"><?php echo $_SESSION['departmentdetails']['name'] ?>
                                        Department Staff</h3>
                                </div>
                                <div>
                                    <h3 class="p-2">HOD: <?php echo $_SESSION['departmentdetails']['head_firstname'] . ' ' . $_SESSION['departmentdetails']['head_lastname'] ?>
                                    </h3>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>FirstName</th>
                                        <th>Lastname</th>
                                        <th> Email</th>
                                        <th> Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>FirstName</th>
                                        <th>Lastname</th>
                                        <th> Email</th>
                                        <th> Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody id="departmentWorkersTableBody">
                                   
                                </tbody>
                            </table>
                            <div class="col-md-6">
                                <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                    <ul class="dptworkerpagination pagination">

                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <?php if($allowed):?>
                        <h3 class="text-center mb-3">Edit Department</h3>
                        <form id="departmentform-edit">
                            <div class="row ">
                                <div class="row m-auto">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <?php
                                    createDepartmentForm();
                                    ?>

                                </div>

                                <div class=" row flex-box m-3">
                                    <span class="errormsg text-danger fs-5"></span>
                                </div>
                            </div>
                            <div class="col-12 mt-3 d-flex justify-content-center ">
                                <button class="btn btn-primary m-5 w-10 col-3">Edit Department</button>
                            </div>
                        </form>
                        <div class="col-12 ">
                            <form id="deletedepthead">
                                <div class='col-sm-6 form-group'>
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">

                                    <input class='form-control' id="departmentid" type='hidden' name='id' value="<?php echo htmlspecialchars($_SESSION['departmentdetails']['id']) ?>">
                                </div>
                                <div class='form-group col-12  d-flex justify-content-center'>
                                    <button class='btn btn-danger m-5' id="del-btn" type='submit'>
                                        Remove &nbsp; <?php echo htmlspecialchars($_SESSION['departmentdetails']['head_firstname'] .
                                                            ' ' . $_SESSION['departmentdetails']['head_lastname']) ?>&nbsp;As Head</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-12 ">
                            <form id="deletedept-account">
                                <div class='col-sm-6 form-group'>
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">

                                    <input class='form-control' type='hidden' name='id' value="<?php echo htmlspecialchars($_SESSION['departmentdetails']['id']) ?>">
                                </div>
                                <div class='form-group col-12  d-flex justify-content-center'>
                                    <button class='btn btn-danger btn-rounded m-5' id="del-btn" type='submit'>Delete Department</button>
                                </div>
                            </form>
                        </div>
                        <?php endif?>

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
    <script src="../assets/js/deptworkers.js" type="module"></script>
   
</body>

</html>