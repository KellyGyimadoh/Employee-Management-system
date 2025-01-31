<?php
$title = "Create User Salary";
require '../includes/sessions.php';
include '../includes/head.php';

if (
    !isloggedin() || !isset($_SESSION['accounttype']) ||
    !in_array($_SESSION['accounttype'], ['admin']) || $_SESSION['userinfo']['status'] !== 1
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

                        <form id="salaryform-edit">
                            <input type="hidden" name="csrf_token"
                                value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">

                            <?php

                            editSalariesForm();
                            ?>
                            <div class="row mt-3 d-flex justify-content-center  w-auto m-auto ">
                                <button class="btn btn-primary m-3 col-3">Edit User Salary</button>
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
            const editSalaryform = document.getElementById("salaryform-edit")
            //const errorinfomsg=document.querySelector('.errormsg')


            if (editSalaryform) {
    editSalaryform.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Preprocess the form data
        const formData = new FormData(editSalaryform);
        const formObj = Object.fromEntries(formData.entries());

        // Convert relevant fields to numbers
        const processedFormObj = {
            ...formObj,
            base_salary: parseFloat(formObj.base_salary) || 0,
            bonus: parseFloat(formObj.bonus) || 0,
            deductions: parseFloat(formObj.deductions) || 0,
            overtime: parseFloat(formObj.overtime) || 0,
            totalSalary:
                (parseFloat(formObj.baseSalary) || 0) +
                (parseFloat(formObj.bonus) || 0) +
                (parseFloat(formObj.overtime) || 0) -
                (parseFloat(formObj.deductions) || 0),
        };

        // Call the processForm module with the preprocessed data
        const resultData = await fetch('../api/salaries/process.updatesalary.php', {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(processedFormObj)
        });

        handleFormMessage(await resultData.json());
    });
}



            (async () => {
                const data = await fetchAll('../api/userview/process.fetchall.php')
                fillUserSelect(data.users)
            })()

            const fillUserSelect = (users) => {
    
                userSelect.innerHTML += users.map(user => (`<option value="${user.id}" >${user.firstname} ${user.lastname}</option>`))
            }


        })


        function calculateTotalSalary() {
            var baseSalary = parseFloat(document.querySelector('[name="base_salary"]').value) || 0;
            var bonus = parseFloat(document.querySelector('[name="bonus"]').value) || 0;
            var overtime = parseFloat(document.querySelector('[name="overtime"]').value) || 0;
            var deductions = parseFloat(document.querySelector('[name="deductions"]').value) || 0;

            var totalSalary = baseSalary + bonus + overtime - deductions;
            document.querySelector('.totalsalary').innerText = totalSalary;
        }

        // Add event listeners to trigger calculation when input changes
        document.querySelectorAll('input').forEach(function(input) {
            input.addEventListener('input', calculateTotalSalary);
        });
    </script>
</body>

</html>