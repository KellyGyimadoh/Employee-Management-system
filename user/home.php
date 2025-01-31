<?php
$title = "User Dashboard";
require '../includes/sessions.php';
include '../includes/head.php';

if (
    !isloggedin() || !isset($_SESSION['accounttype']) ||
    !in_array($_SESSION['accounttype'], ['staff', 'admin'])
) {
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
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <?php
            include '../includes/alert.php'
            ?>
            <!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-success color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong totalworktasks">201</h2>
                                <div class="m-b-5">TOTAL WORK TASKS</div>
                                <i class="ti-clipboard widget-stat-icon"></i>
                                <div><i class="fa fa-level-up m-r-5"></i><small>25% higher</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-warning color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong totalpendingtask">1250</h2>
                                <div class="m-b-5">TOTAL TASKS PENDING</div><i class="ti-bar-chart widget-stat-icon"></i>
                                <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-success color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong totalcompletedtask">1250</h2>
                                <div class="m-b-5"> TASKS COMPLETED</div>
                                <i class="ti-check-box widget-stat-icon"></i>
                                <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-danger color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong totallatetask">1250</h2>
                                <div class="m-b-5"> LATE SUBMISSION</div><i class="ti-face-sad widget-stat-icon"></i>
                                <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div>
                            </div>
                        </div>
                    </div>

                    <!--users own task-->
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-success color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong tasktotalnumber">201</h2>
                                <div class="m-b-5">MY TOTAL TASKS</div>
                                <i class="ti-list-ol widget-stat-icon"></i>
                                <div><i class="fa fa-level-up m-r-5"></i><small>25% higher</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-warning color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong pendingtask">1250</h2>
                                <div class="m-b-5"> MY TASKS PENDING</div><i class="ti-pencil widget-stat-icon"></i>
                                <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-success color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong completedtask">1250</h2>
                                <div class="m-b-5">MY TASKS COMPLETED</div><i class="ti-face-smile widget-stat-icon"></i>
                                <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-danger color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong latetask">1250</h2>
                                <div class="m-b-5"> MY LATE SUBMISSION</div><i class="ti-face-sad widget-stat-icon"></i>
                                <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div>
                            </div>
                        </div>
                    </div>
                    <!-- attendance -->
                    <div class='row attcard p-3 '>
                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-info color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong attendancetotalnumber">201</h2>
                                    <div class="m-b-5 text-center px-5">USER ATTENDANCE TODAY</div>
                                    <i class="ti-calendar widget-stat-icon"></i>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-success color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong userspresent">1250</h2>
                                    <div class="m-b-5 text-center px-5">EMPLOYEES PRESENT TODAY</div>
                                    <i class="ti-check widget-stat-icon"></i>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-dark color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong userslate">1250</h2>
                                    <div class="m-b-5 text-center px-5">EMPLOYEES LATE TODAY</div>
                                    <i class="ti-flag widget-stat-icon"></i>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-danger color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong usersabsent">1250</h2>
                                    <div class="m-b-5 text-center px-5">EMPLOYEES ABSENT TODAY</div>
                                    <i class="ti-close widget-stat-icon"></i>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- userleave -->
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
                    <div class="row  dptcard p-3 ">
                       
                        
                        <!-- departments -->
                        <div class="col-lg-6 col-md-12 col-sm-12 dptmini">
                            <div class="ibox d-flex">
                                <div class="ibox-body">
                                    <div class="flexbox mb-4">
                                        <div>
                                            <h3 class="m-0">Deparments</h3>
                                            <div>Departments Summary</div>
                                        </div>
                                        <div class="d-inline-flex">
                                            <div class="px-3" style="border-right: 1px solid rgba(0,0,0,.1);">
                                                <div class="text-muted">TOTAL DEPARTMENTS</div>
                                                <div>
                                                    <span class="h2 m-0 departmenttotal">$850</span>
                                                    <span class="text-success ml-2"><i class="fa fa-level-up"></i> +25%</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Total Departments</th>
                                                <th>Has Head Of Department</th>
                                                <th>Active</th>
                                                <th>Suspended</th>

                                            </tr>
                                        </thead>
                                        <tbody class="departmentTableBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
            <div class="row mt-3">
                <div class="ibox col-4 m-2 p-2">
                    <h5 class="ibox-head">Total Workers| <span id="workertype"></span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ti-user widget-stat-icon"></i>
                        </div>
                        <div class="ps-3">
                            <h2 class="totalusers font-strong "></h2>
                            <span id="percentageChange" class="text-success small pt-1 fw-bold">12%</span> <span
                                class="text-muted small pt-2 ps-1">increase</span>

                        </div>
                    </div>
                </div>
                <div class="ibox col-3 m-2 p-2">
                    <h5 class="ibox-head">Total Staff| <span id="workertype"></span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ti-id-badge widget-stat-icon"></i>
                        </div>
                        <div class="ps-3">
                            <h2 class="totalstaff font-strong "></h2>
                            <span id="percentageChange" class="text-success small pt-1 fw-bold">12%</span> <span
                                class="text-muted small pt-2 ps-1">increase</span>

                        </div>
                    </div>
                </div>
                <div class="ibox col-4 m-2 p-2">
                    <h5 class="ibox-head">Total Administrators| <span id="workertype"></span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ti-stamp widget-stat-icon"></i>
                        </div>
                        <div class="ps-3">
                            <h2 class="totaladmin font-strong "></h2>
                            <span id="percentageChange" class="text-success small pt-1 fw-bold">12%</span> <span
                                class="text-muted small pt-2 ps-1">increase</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include '../includes/footer.php'
        ?>

        <!-- END THEME CONFIG PANEL-->
        <!-- BEGIN PAGA BACKDROPS-->
        <div class="sidenav-backdrop backdrop"></div>
        <!-- <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div> -->
        <!-- END PAGA BACKDROPS-->
        <!-- CORE PLUGINS-->
        <?php
        include('../includes/scripts.php')
        ?>
        <script src="../assets/js/userdashboard.js" type="module"></script>
</body>

</html>