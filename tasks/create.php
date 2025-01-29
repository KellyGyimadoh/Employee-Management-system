<?php
$title = "Create New Task";
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
                <h1 class="page-title">CREATE NEW TASK</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">NEW TASK</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Setup New Task</div>
                    </div>

                    <div class="ibox-body">

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
                                    <label for="user-select">Assign To</label>
                                    <select name="assigned_to" id="user-select" class="form-control" required>
                                        <option value="">Select Worker</option>

                                    </select>
                                </div>

                                <div class="col-6 mt-2">
                                    <label for="department-select">Department</label>
                                    <select name="department_id" id="department-select" class="form-control" required>
                                        <option value="">Select Department</option>

                                    </select>
                                </div>
                                <div class="col-6 mt-2">
                                    <label for="user-head">Assigned By</label>
                                    <select name="assigned_by" id="user-head" class="form-control" required>
                                        <option value="">Select Head</option>

                                    </select>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="due_date">Due Date</label>
                                   <input type="date" name="due_date" class="form-control"/>
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
        import fetchAll from '../assets/js/fetchAll.js';
        import alertFunction from '../assets/js/alertFunction.js';
        import handleFormMessage from '../assets/js/handleFormMessage.js';
        import processForm from '../assets/js/processForm.js';
        import fetchUserId from '../assets/js/fetchUserId.js'
        document.addEventListener("DOMContentLoaded", () => {
            const userSelect = document.getElementById("user-select")
            const userHead = document.getElementById("user-head")
            const addTaskform = document.getElementById("taskform-create")
            const departmentSelect = document.querySelector('#department-select')
            //const errorinfomsg=document.querySelector('.errormsg')


            if (addTaskform) {
                addTaskform.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(addTaskform, '../api/tasks/process.addtask.php');
                    handleFormMessage(resultData);
                });
            }


            (async () => {
                const data = await fetchAll('../api/userview/process.fetchall.php')
                fillUserSelect(data.users)

                const headDepartmentData = await fetchAll('../api/departments/process.getheads.php')
                fillHeadSelect(headDepartmentData.departmentheads)

                const departmentdata = await fetchAll('../api/departments/process.viewalldpt.php')
                fillUserDepartment(departmentdata.departments)
            })()

           


            const fillUserSelect = (users) => {
                userSelect.innerHTML = "";

                userSelect.innerHTML += `<option value="">Select Worker</option>`;
                userSelect.innerHTML += users.map(user => (`<option value="${user.id}" >${user.firstname} ${user.lastname}</option>`))
            }

            const fillHeadSelect = (departments) => {
                userHead.innerHTML = "";

                userHead.innerHTML += `<option value="">Select Head</option>`;
                userHead.innerHTML += departments.map(dept => (`<option value="${dept.user_id}" >
                ${dept.firstname} ${dept.lastname}-${dept.name} HOD</option>`))
            }

            const fillUserDepartment = (departments) => {
                departmentSelect.innerHTML = "";

                departmentSelect.innerHTML += `<option value="">Select Department </option>`;
                departmentSelect.innerHTML += departments.map(dpt => (`<option value="${dpt.id}" >${dpt.name}</option>`))
            }

            //get departments of user assigned to
            userSelect.addEventListener("change", (e) => {
                const id = e.target.value;
                (async () => {
                    const data = await fetchUserId('../api/departments/process.fetchuserdepartment.php', id)
                    const department = document.querySelector('#department-select')
                    if(data?.departments){
                    department.innerHTML = "";
                    department.innerHTML += `<option value="">Select Department </option>`;
                    department.innerHTML += data.departments
                    .map(dpt => (`<option value="${dpt.id}" >${dpt.name}</option>`))
                    }

                })()

            })

            //saving for automatic selection of head
            // departmentSelect.addEventListener("change", (e) => {
            //     const id = e.target.value;
            //     (async () => {
            //         const data = await fetchUserId('../api/payroll/process.fetchusersalary.php', id)
            //         const head = document.querySelector('#user-head')
            //         head.innerHTML += `<option value="">Select Head</option>`;
            //         head.innerHTML += users.map(user => (`<option value="${user.id}" >${user.firstname} ${user.lastname}</option>`))

            //     })()

            // })



        })
    </script>
</body>

</html>