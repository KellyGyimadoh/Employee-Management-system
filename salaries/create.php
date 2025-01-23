<?php
$title = "Create User Salary";
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
                <h1 class="page-title">USER SALARY SETUP</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">USER SALARY</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Setup Salary</div>
                    </div>

                    <div class="ibox-body">

                        <form id="salaryform-create">
                        <div class="row mt-3">
                               
                               <div class="col-6 mt-2">
                                   <label for="user-select">Worker Name</label>
                                   <select name="user_id" id="user-select" class="form-select" required>
                                       <option value="">Select Worker</option>

                                   </select>
                               </div>
                               <div class="col-6 mt-2">
                                   <label class="m-2" for="base_salary">Base Salary</label>
                                   <input type="number"  name="base_salary" class="form-control" value="0" step="0.01" min="0" placeholder="Base Salary" required>
                               </div>
                               
                           </div>
                            <div class="row">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <div class="col-6 mt-2">
                                    <label class="m-2" for="name">Bonus</label>
                                    <input type="number"  name="bonus" class="form-control" value="0" step="0.01" min="0" placeholder="Bonus" required>
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="m-2" for="deductions">Deductions</label>
                                    <input type="number"  name="deductions" class="form-control" value="0" step="0.01" min="0" placeholder="Deductions" required>
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="m-2" for="deductions">Overtime</label>
                                    <input type="number"  name="overtime" class="form-control" value="0" step="0.01" min="0" placeholder="Overtime" required>
                                </div>
                                <div class="col-6 mt-5  w-25 border border-primary d-flex flex-column justify-content-center rounded ">
                                  
                                    <h3 class="text-center">Total Salary GHS</h3>
                                    <div class="col-6 m-auto border border-success d-flex flex-column justify-content-center">
                                        <p class="text-center fs-3"><span class="totalsalary fs-3">0.00</span></p></div> 
                                </div>
                            </div>
                            
                            <div class="row mt-3 ">
                                    <button class="btn btn-primary m-3">Create User Salary</button>
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
            const userSelect = document.getElementById("user-select")
            const addSalaryform = document.getElementById("salaryform-create")
            //const errorinfomsg=document.querySelector('.errormsg')


            if (addSalaryform) {
                addSalaryform.addEventListener("submit", async (e) => {
                e.preventDefault();
                const resultData = await processForm(addSalaryform, '../api/salaries/process.addsalary.php');
                handleFormMessage(resultData);
            });
            }
           

            (async()=>{
                const data= await fetchAll('../api/userview/process.fetchall.php')
                fillUserSelect(data.users)
            })()
          
            const fillUserSelect = (users) => {
               userSelect.innerHTML = "";
                
               userSelect.innerHTML += `<option value="">Select Worker</option>`;
               userSelect.innerHTML += users.map(user => (`<option value="${user.id}" >${user.firstname} ${user.lastname}</option>`))
            }
            
            
        })

        
    function calculateTotalSalary() {
        var baseSalary = parseFloat(document.querySelector('[name="base_salary"]').value) || 0;
        var bonus = parseFloat(document.querySelector('[name="bonus"]').value) || 0;
        var overtime = parseFloat(document.querySelector('[name="overtime"]').value) || 0;
        var deductions = parseFloat(document.querySelector('[name="deductions"]').value) || 0;
        
        var totalSalary = baseSalary + bonus + overtime - deductions;
        document.querySelector('.totalsalary').innerText =  totalSalary;
    }

    // Add event listeners to trigger calculation when input changes
    document.querySelectorAll('input').forEach(function(input) {
        input.addEventListener('input', calculateTotalSalary);
    });


    </script>
</body>

</html>


