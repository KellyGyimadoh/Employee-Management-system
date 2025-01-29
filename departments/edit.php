<?php
$title = "Manager Dashboard";
require '../includes/sessions.php';
include '../includes/head.php';

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "admin") {
    header('location:../auth/login.php');
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
                        <div class="ibox-title">Edit Department</div>
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
                                        <input type="search" name="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                        <input type="hidden" name="limit">
                                        <input type="hidden" name="page">
                                        <input type="hidden" name="deptid" id="deptid" value="<?php echo htmlspecialchars($_SESSION['departmentdetails']['id'])?>">
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
                                    <?php
                                    if (isset($_SESSION['departmentworkerdetails'])) {
                                        foreach ($_SESSION['departmentworkerdetails'] as $index => $worker): ?>
                                            <tr>
                                                <td><?php echo $index ?></td>
                                                <td><?php echo $worker['firstname'] ?></td>
                                                <td><?php echo $worker['lastname'] ?></td>
                                                <td><?php echo $worker['email'] ?></td>
                                                <td><?php echo $worker['phone'] ?></td>
                                                <td>
                                                    <?php
                                                    $status = $worker['status'];
                                                    echo $status == 1 ? "<button class='btn btn-success'>Active</button>"
                                                        : "<button class='btn btn-danger'>Suspended</button>";
                                                    ?>

                                                </td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="../../api/userview/process.selectuser.php?userid=<?php echo htmlspecialchars($worker['user_id']) ?>">Edit</a>

                                                </td>
                                            </tr>



                                    <?php endforeach;
                                    } else {
                                        echo "<tr>No workers available</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="col-md-6">
                                <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                    <ul class="dptworkerpagination pagination">

                                    </ul>
                                </nav>
                            </div>
                        </div>

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

                                    <input class='form-control' type='hidden' name='id' value="<?php echo htmlspecialchars($_SESSION['departmentdetails']['id']) ?>">
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
    
    <script type="module">
        import fetchAll from '../assets/js/fetchAll.js'
        import alertFunction from '../assets/js/alertFunction.js'
        import handleFormMessage from '../assets/js/handleFormMessage.js';
        import processForm from '../assets/js/processForm.js';
        document.addEventListener("DOMContentLoaded", () => {
            const departmentselect = document.getElementById("departmenthead-select-edit")
            const editDepartmentform = document.getElementById("departmentform-edit")
            const deletedeptForm = document.querySelector("#deletedept-account");
            const deletedeptHeadForm = document.querySelector("#deletedepthead");

            if (editDepartmentform) {
                editDepartmentform.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(editDepartmentform, '../api/departments/process.updatedept.php');
                    handleFormMessage(resultData);
                });
            }
            if (deletedeptHeadForm) {
                deletedeptHeadForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(deletedeptHeadForm, '../api/departments/process.deletedepthead.php');
                    handleFormMessage(resultData);
                });
            }

            if (deletedeptForm) {
                deletedeptForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(deletedeptForm, '../api/departments/process.delete.php');
                    handleFormMessage(resultData);
                });
            }


            (async () => {
                const data = await fetchAll('../api/userview/process.fetchall.php')
                fillDepartmentSelect(data.users)
            })()

            const fillDepartmentSelect = (users) => {

                departmentselect.innerHTML += users.map((user) =>

                    (
                        `<option value="${user.id}">${user.firstname} ${user.lastname}</option>`

                    )
                )
            }

          




        })
    </script>
</body>

</html>