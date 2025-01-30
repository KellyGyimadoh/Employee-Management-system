<?php
include '../includes/sessions.php';
$title = isset($_SESSION['userdetails']) ? htmlspecialchars(ucfirst($_SESSION['userdetails']['firstname']) . ' ' . 'Profile') : 'User Profile';
include '../includes/head.php';

if (
    !isloggedin() || !isset($_SESSION['accounttype']) ||
    !in_array($_SESSION['accounttype'], ['staff', 'admin'])
) {
    header("Location: ../auth/login.php");
    die();
}
if (isset($_SESSION['userdetails'])) {
    $userinfo = $_SESSION['userdetails'];
}
$allowed = checkAccount(['admin']);
?>

<body class="fixed-navbar sidebar-mini">
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
                <h1 class="page-title">Profile</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Profile</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="ibox">
                            <div class="ibox-body text-center">
                                <div class="m-t-20">
                                    <img class="img-circle" src="<?php echo isset($userinfo['image']) ? htmlspecialchars($userinfo['image']) : '../assets/img/users/u3.jpg' ?>" />

                                </div>
                                <h5 class="font-strong m-b-10 m-t-10"><?php echo htmlspecialchars($userinfo['firstname'] . ' ' . $userinfo['lastname']) ?></h5>
                                <div class="m-b-20 text-muted"><?php echo htmlspecialchars($userinfo['account_type']) ?></div>
                                <div class="profile-social m-b-20">
                                    <a href="javascript:;"><i class="fa fa-twitter"></i></a>
                                    <a href="javascript:;"><i class="fa fa-facebook"></i></a>
                                    <a href="javascript:;"><i class="fa fa-pinterest"></i></a>
                                    <a href="javascript:;"><i class="fa fa-dribbble"></i></a>
                                </div>
                                <div>
                                    <button class="btn btn-info btn-rounded m-b-5"><i class="fa fa-plus"></i> Follow</button>
                                    <button class="btn btn-default btn-rounded m-b-5">Message</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="ibox">
                            <div class="ibox-body">
                                <ul class="nav nav-tabs tabs-line">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Overview</a>
                                    </li>
                                    <?php if ($allowed): ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#tab-2" data-toggle="tab"><i class="ti-settings"></i> Settings</a>
                                        </li>
                                    <?php endif ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-3" data-toggle="tab"><i class="ti-announcement"></i>Assign Task</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-1">
                                        <div class="row">
                                            <div class="col-md-12" style="border-right: 1px solid #eee;">
                                                <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-bar-chart"></i> Month Statistics</h5>
                                                <div class="h2 m-0">$12,400<sup>.60</sup></div>
                                                <div><small>Month income</small></div>
                                                <div class="m-t-20 m-b-20">
                                                    <div class="h4 m-0">120</div>
                                                    <div class="d-flex justify-content-between"><small>Month income</small>
                                                        <span class="text-success font-12"><i class="fa fa-level-up"></i> +24%</span>
                                                    </div>
                                                    <div class="progress m-t-5">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" style="width:50%; height:5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="m-b-20">
                                                    <div class="h4 m-0">86</div>
                                                    <div class="d-flex justify-content-between"><small>Month income</small>
                                                        <span class="text-warning font-12"><i class="fa fa-level-down"></i> -12%</span>
                                                    </div>
                                                    <div class="progress m-t-5">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:50%; height:5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <ul class="list-group list-group-full list-group-divider">
                                                    <li class="list-group-item">Projects
                                                        <span class="pull-right color-orange">15</span>
                                                    </li>
                                                    <li class="list-group-item">Tasks
                                                        <span class="pull-right color-orange">148</span>
                                                    </li>
                                                    <li class="list-group-item">Articles
                                                        <span class="pull-right color-orange">72</span>
                                                    </li>
                                                    <li class="list-group-item">Friends
                                                        <span class="pull-right color-orange">44</span>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                        <h4 class="text-info m-b-20 m-t-20"><i class="fa fa-shopping-basket"></i> Latest Orders</h4>

                                    </div>
                                    <?php
                                    if (isset($_SESSION['accounttype']) && $_SESSION['accounttype'] == 'admin'):

                                    ?>
                                        <div class="tab-pane fade" id="tab-2">
                                            <form method="post" id="userprofile-form">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                                <?php
                                                userDetailsProfileForm();

                                                ?>
                                            </form>
                                            <!-- deleteform -->
                                            <div>
                                                <form id="delete-account">
                                                    <div class='col-sm-6 form-group'>
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">

                                                        <input class='form-control' type='hidden' name='id'
                                                         value="<?php echo htmlspecialchars($_SESSION['userdetails']['id']) ?>">
                                                    </div>
                                                    <div class='form-group item-center'>
                                                        <button class='btn btn-danger btn-rounded' id="del-btn" type='submit'>Delete User Account</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    <?php endif ?>

                                    <div class="tab-pane fade" id="tab-3">
                                        <h5 class="text-info m-b-20 m-t-20"><i class="fa fa-bullhorn"></i> Assign New Task</h5>
                                        <form id="taskform-create">
                                            <div class="row mt-3">

                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">

                                                <div class="col-6 mt-2">
                                                    <label class="m-2" for="name">Task Name</label>
                                                    <input type="text" name="name" class="form-control" placeholder="Task Name" required>
                                                </div>
                                                <div class="col-6 mt-2">
                                                    <label class="m-2" for="description">Description</label>
                                                    <textarea name="description" cols="3" rows="4" class="form-control"
                                                        placeholder="Task Description"></textarea>
                                                </div>

                                            </div>
                                            <div class="row">


                                                <div class="col-6 mt-2">
                                                    <label for="user">Assign To</label>
                                                    <h3 class="form-control"><?php
                                                                                echo htmlspecialchars($_SESSION['userdetails']['firstname']) . '' .
                                                                                    htmlspecialchars($_SESSION['userdetails']['lastname'])
                                                                                ?>

                                                    </h3>
                                                    <input class='form-control'  id="userId" type='hidden' name='assigned_to'
                                                        value='<?php echo htmlspecialchars($_SESSION['userdetails']['id']) ?>'>

                                                </div>

                                                <div class="col-6 mt-2">
                                                    <label for="department-select">Department</label>
                                                    <select name="department_id" id="department-select" class="form-control" required>
                                                        <option value="">Select Department</option>

                                                    </select>
                                                </div>
                                                <div class="col-6 mt-2">
                                                    <label for="user-head">Assigned By</label>
                                                    <h3><?php
                                                        echo htmlspecialchars($_SESSION['userinfo']['firstname']) . '' .
                                                            htmlspecialchars($_SESSION['userinfo']['lastname'])
                                                        ?></h3>
                                                    <input type="hidden" name="assigned_by"
                                                        value='<?php echo htmlspecialchars($_SESSION['userinfo']['id']) ?>' />
                                                </div>
                                                <div class="col-6 mt-3">
                                                    <label for="due_date">Due Date</label>
                                                    <input type="date" name="due_date" class="form-control" />

                                                </div>
                                            </div>

                                            <div class="row mt-3 w-auto d-flex justify-content-center ">
                                                <button class="btn btn-primary w-auto">Create New Task</button>
                                            </div>
                                            <div class=" row flex-box">
                                                <span class="errormsg text-danger fs-5"></span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END PAGE CONTENT-->

        </div>

    </div>

    <!-- END THEME CONFIG PANEL-->
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS-->
    <?php
    include('../includes/scripts.php');
    ?>
    <script type="module">
        import processForm from '../assets/js/processForm.js';
        import alertFunction from '../assets/js/alertFunction.js';
        import handleFormMessage from '../assets/js/handleFormMessage.js';
        import fetchUserId from '../assets/js/fetchUserId.js';

        document.addEventListener("DOMContentLoaded", () => {

            const userprofileForm = document.querySelector("#userprofile-form");
            const deleteForm = document.querySelector("#delete-account");
            const departmentSelect = document.querySelector('#department-select');
            const addTaskform = document.getElementById("taskform-create");
            const userId=document.getElementById("userId")

            if (userprofileForm) {
                userprofileForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(userprofileForm, '../api/userview/process.updateoneuser.php');
                    if (resultData) {
                        handleFormMessage(resultData);
                    }
                });
            }

            if (deleteForm) {
                deleteForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(deleteForm, '../api/userauth/process.deleteuser.php');
                    handleFormMessage(resultData);
                });
            }
            if (addTaskform) {
                addTaskform.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(addTaskform, '../api/tasks/process.addtask.php');
                    handleFormMessage(resultData);
                });
            }

            (async () => {
               
                const id=userId.value
                const departmentdata = await fetchUserId('../api/departments/process.fetchuserdepartment.php',id)
                fillUserDepartment(departmentdata.departments)
            })()

            const fillUserDepartment = (departments) => {


                departmentSelect.innerHTML += departments.map(dpt => (`<option value="${dpt.id}" >${dpt.name}</option>`))
            }







        })
    </script>
</body>

</html>