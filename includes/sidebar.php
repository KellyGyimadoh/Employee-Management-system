<?php
$accountype = isset($_SESSION['accounttype']) ?: $_SESSION['accounttype'];
$allowed = checkAccount(['admin']);

?>
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img class="img-circle" src="<?php echo isset($_SESSION['userinfo']['image'])
                                                    ? htmlspecialchars($_SESSION['userinfo']['image']) 
                                                    : '../assets/img/users/u3.jpg' ?>" width="45px" />

            </div>
            <div class="admin-info">
                <div class="font-strong"><?php echo isset($_SESSION['userinfo']) ? htmlspecialchars($_SESSION['userinfo']['firstname'] . ' ' . $_SESSION['userinfo']['lastname']) : 'worker' ?></div>
                <small><?php echo isset($_SESSION['userinfo']) && $_SESSION['accounttype'] == 'staff'   ? 'Staff' : 'Administrator'  ?></small>
            </div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <?php echo isset($_SESSION['userinfo']) && $_SESSION['accounttype'] == 'admin'
                    ? '<a class="active" href="../manager/home.php">' : '<a class="active" href="../user/home.php">'  ?>



                <i class="sidebar-item-icon fa fa-th-large"></i>
                <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="heading">ATTENDANCE</li>
            <li>
                <a class="pe-10" data-bs-toggle="collapse" href="#attendancecollapse"
                    role="button" aria-expanded="false" aria-controls="attendancecollapse">
                    <i class="sidebar-item-icon ti-calendar "></i>
                    <span class="nav-label">Manage Attendance</span><i class="fa fa-angle-left arrow mx-6"></i></a>
                <ul class="nav-2-level collapse multi-collapse" id="attendancecollapse">
                    <li>
                        <a href="../attendance/create.php">Mark My Attendance</a>
                    </li>
                    <?php if ($allowed): ?>
                        <li>
                            <a href="../attendance/today.php">Todays Attendance</a>
                        </li>
                        <li>
                            <a href="../attendance/index.php">Attendance History</a>
                        </li>
                    <?php endif ?>

                </ul>
            </li>
            <li class="heading">TASKS</li>
            <li>
                <a class="pe-10" data-bs-toggle="collapse" href="#taskcollapse"
                    role="button" aria-expanded="false" aria-controls="taskcollapse">
                    <i class=" sidebar-item-icon ti-clipboard"></i>
                    <span class="nav-label">Manage Tasks</span><i class="fa fa-angle-left arrow mx-6"></i></a>
                <ul class="nav-2-level collapse multi-collapse" id="taskcollapse">

                    <li>
                        <a href="../tasks/show.php">My Tasks</a>
                    </li>
                    <?php if ($allowed): ?>
                        <li>
                            <a href="../tasks/create.php">Create New Task</a>
                        </li>

                        <li>
                        <a href="../tasks/index.php">View All Tasks Records</a>
                    </li>
                    <?php endif ?>
                    
                   
                    <li>
                        <a href="../tasks/today.php">View Todays Tasks</a>
                    </li>


                </ul>
            </li>
            <?php
            if ($allowed):
            ?>
                <li class="heading">WORKERS DETAILS</li>
                <li>
                    <a class="pe-10" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                        <i class="sidebar-item-icon fa fa-bookmark "></i>
                        <span class="nav-label">Manage Workers Records</span><i class="fa fa-angle-left arrow mx-6"></i></a>
                    <ul class="nav-2-level collapse multi-collapse" id="multiCollapseExample1">
                        <li>
                            <a href="../users/allworkers.php">All Workers</a>
                        </li>
                        <li>
                            <a href="../users/alladmins.php">All Administrators</a>
                        </li>
                        <li>
                            <a href="../users/allstaff.php">All Staffs</a>
                        </li>


                    </ul>
                </li>

                <li>
                    <a data-bs-toggle="collapse" href="#multiCollapseExample2" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                        <i class="sidebar-item-icon ti-id-badge "></i>
                        <span class="nav-label"> Workers Profile</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse multi-collapse" id="multiCollapseExample2">
                        <li>
                            <a href="../users/workersprofile.php">Workers Profile</a>
                        </li>

                    </ul>
                </li>
            <?php endif ?>
            <li class="heading">DEPARTMENTS</li>
            <li>
                <a class="pe-10" data-bs-toggle="collapse" href="#departmentcollapse" role="button" aria-expanded="false" aria-controls="departmentcollapse">
                    <i class="sidebar-item-icon ti-medall "></i>
                    <span class="nav-label">Manage Departments</span><i class="fa fa-angle-left arrow mx-6"></i></a>
                <ul class="nav-2-level collapse multi-collapse" id="departmentcollapse">
                    <?php
                    if ($allowed):
                    ?>
                        <li>
                            <a href="../departments/create.php">Create Department</a>
                        </li>
                        <li>
                            <a href="../departments/index.php">View Departments</a>
                        </li>
                        <li>
                            <a href="../departments/adduser.php">Assign Users</a>
                        </li>
                    <?php endif ?>
                    <li>
                        <a href="../departments/show.php">My Department</a>
                    </li>


                </ul>
            </li>
            <?php
            if ($allowed):
            ?>
                <li class="heading">WORKER SALARIES</li>
                <li>
                    <a class="pe-10" data-bs-toggle="collapse" href="#salariescollapse" role="button" aria-expanded="false" aria-controls="salariescollapse">
                        <i class="sidebar-item-icon ti-money "></i>
                        <span class="nav-label">Manage Salaries</span><i class="fa fa-angle-left arrow mx-6"></i></a>
                    <ul class="nav-2-level collapse multi-collapse" id="salariescollapse">
                        <li>
                            <a href="../salaries/create.php">Assign User Salary</a>
                        </li>
                        <li>
                            <a href="../salaries/index.php">View User Salaries</a>
                        </li>



                    </ul>
                </li>
                <li class="heading">WORKER PAYROLL</li>
                <li>
                    <a class="pe-10" data-bs-toggle="collapse" href="#payrollcollapse" role="button" aria-expanded="false" aria-controls="payrollcollapse">
                        <i class="sidebar-item-icon ti-wallet "></i>
                        <span class="nav-label">Manage Payroll</span><i class="fa fa-angle-left arrow mx-6"></i></a>
                    <ul class="nav-2-level collapse multi-collapse" id="payrollcollapse">
                        <li>
                            <a href="../payroll/create.php">Pay Worker</a>
                        </li>
                        <li>
                            <a href="../payroll/recent.php">View Recent Payroll</a>
                        </li>
                        <li>
                            <a href="../payroll/index.php">View Payroll Records</a>
                        </li>



                    </ul>
                </li>

            <?php
            endif
            ?>
            <li class="heading">LEAVE REQUEST</li>
            <li>
                <a class="pe-10" data-bs-toggle="collapse" href="#leavecollapse" role="button" 
                aria-expanded="false" aria-controls="leavecollapse">
                    <i class="sidebar-item-icon ti-briefcase "></i>
                    <span class="nav-label">Leave Request</span><i class="fa fa-angle-left arrow mx-6"></i></a>
                <ul class="nav-2-level collapse multi-collapse" id="leavecollapse">
                    <li>
                        <a href="../leaverequest/create.php">Create Request</a>
                    </li>
                    <li>
                        <a href="../leaverequest/show.php">My Leave Records</a>
                    </li>
                    <?php
                    if($allowed):
                    ?>
                    <li>
                        <a href="../leaverequest/index.php">View All Leave Records</a>
                    </li>
                    <?php
                    endif
                    ?>


                </ul>
            </li>

        </ul>

    </div>
</nav>