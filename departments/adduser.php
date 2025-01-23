<?php
$title = "Add User";
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
                <h1 class="page-title">ADD EMPLOYEES </h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">ADD NEW EMPLOYEE</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Add New User</div>
                    </div>

                    <div class="ibox-body">

                        <form id="adduserform-create">
                            <div class="row">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <div class="col-6">
                                <label for="department">Department</label>
                                    <select name="department_id" id="department-select" class="form-select">
                                        <option value="">Select Department</option>

                                    </select>     </div>

                                <div class="col-6">
                                    <label for="departmenthead-select">Worker name</label>
                                    <select name="user_id" id="user-select" class="form-select">
                                        <option value="">Select Worker</option>

                                    </select>
                                </div>
                                
                            </div>
                            <div class="row mt-3 ">
                                    <button class="btn btn-primary m-3">Assign User</button>
                                </div>
                                <div class=" row flex-box m-3">
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
        import fetchAll from '../assets/js/fetchAll.js'
         import alertFunction from '../assets/js/alertFunction.js'
         import handleFormMessage from '../assets/js/handleFormMessage.js';
         import processForm from '../assets/js/processForm.js';
        document.addEventListener("DOMContentLoaded", () => {
            const departmentselect = document.getElementById("department-select")
            const userSelect = document.getElementById("user-select")
            const addUserform = document.getElementById("adduserform-create")
            //const errorinfomsg=document.querySelector('.errormsg')


           
            if (addUserform) {
                addUserform.addEventListener("submit", async (e) => {
                e.preventDefault();
                const resultData = await processForm(addUserform, '../api/departments/process.adduser.php');
                handleFormMessage(resultData);
            });
            }

           
           
           
           
            const fillUserSelect = (users) => {
                userSelect.innerHTML = "";
                
                userSelect.innerHTML += `<option value="">Select Worker</option>`;
                userSelect.innerHTML += users.map(user => (`<option value="${user.id}" >${user.firstname} ${user.lastname}</option>`))
            }

            const fillDepartmentSelect = (departments) => {
                departmentselect.innerHTML = "";
                
                departmentselect.innerHTML += `<option value="">Select Department</option>`;
                departmentselect.innerHTML += departments.map(department => (`<option value="${department.id}" >${department.name}</option>`))
            }
          
            
            (async()=>{
                const userdata= await fetchAll('../api/userview/process.viewallusers.php')
               fillUserSelect(userdata.users)
               const data= await fetchAll('../api/departments/process.viewalldpt.php')
               fillDepartmentSelect(data.departments)
            })()
            
         
           
        })
    </script>
</body>

</html>