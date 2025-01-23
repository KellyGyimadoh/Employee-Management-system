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
                <h1 class="page-title">ALL EMPLOYEES PROFILE</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">EMPLOYEES PROFILE</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Create New Department</div>
                    </div>

                    <div class="ibox-body">

                        <form id="departmentform-create">
                            <div class="row">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <div class="col-6">
                                    <label for="name">Name Of Department</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name of Department">
                                </div>
                                <div class="col-6">
                                    <label for="email">Email Of Department</label>
                                    <input type="text" name="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <label for="phone">Department Phone</label>
                                    <input type="tel" name="phone" class="form-control" placeholder="Department Phone">
                                </div>
                                <div class="col-6">
                                    <label for="departmenthead-select">Head Of Department</label>
                                    <select name="head" id="departmenthead-select" class="form-select">
                                        <option value="">Select Head Of Department</option>

                                    </select>
                                </div>
                                
                            </div>
                            <div class="row mt-3 ">
                                    <button class="btn btn-primary m-3">Create Department</button>
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
        document.addEventListener("DOMContentLoaded", () => {
            const departmentselect = document.getElementById("departmenthead-select")
            const addDepartmentform = document.getElementById("departmentform-create")
            //const errorinfomsg=document.querySelector('.errormsg')


            if (addDepartmentform) {
                addDepartmentform.addEventListener("submit", async (e) => {
                e.preventDefault();
                const resultData = await processForm(addDepartmentform, '../api/departments/process.createdepartment.php');
                handleFormMessage(resultData);
            });
            }
           

            (async()=>{
                const data= await fetchAll('../api/userview/process.fetchall.php')
                fillDepartmentSelect(data.users)
            })()
          
            const fillDepartmentSelect = (users) => {
                departmentselect.innerHTML = "";
                
                departmentselect.innerHTML += `<option value="">Select Department head</option>`;
                departmentselect.innerHTML += users.map(user => (`<option value="${user.id}" >${user.firstname} ${user.lastname}</option>`))
            }
            
            
        })
    </script>
</body>

</html>