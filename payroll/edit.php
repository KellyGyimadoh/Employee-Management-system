<?php
$title = "Pay Workers";
require '../includes/sessions.php';
include '../includes/head.php';

if (
    !isloggedin() || !isset($_SESSION['accounttype']) ||
    !in_array($_SESSION['accounttype'], [ 'admin']) || $_SESSION['userinfo']['status'] !== 1
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
                <h1 class="page-title">MAKE PAYMENT</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">WORKER PAYMENT</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Salary Payment</div>
                    </div>

                    <div class="ibox-body">

                        <form id="payrollform-edit">
                        <input type='hidden' name='csrf_token' value='<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>'>
                        <?php
                        editPayrollForm();
                        ?>   
                        <div class="row mt-3 ">
                                <button class="btn btn-primary m-3">Edit Payment</button>
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
        import fetchUserId from '../assets/js/fetchUserId.js';
        import alertFunction from '../assets/js/alertFunction.js';
        import handleFormMessage from '../assets/js/handleFormMessage.js';
        import processForm from '../assets/js/processForm.js';
        document.addEventListener("DOMContentLoaded", () => {
            const userSelect = document.getElementById("user-select")
            const editPayrollForm = document.getElementById("payrollform-edit")
            //const errorinfomsg=document.querySelector('.errormsg')


            if (editPayrollForm) {
                editPayrollForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(editPayrollForm, '../api/payroll/process.updatepayroll.php');
                    handleFormMessage(resultData);
                });
            }


        

          
        })
    </script>
</body>

</html>