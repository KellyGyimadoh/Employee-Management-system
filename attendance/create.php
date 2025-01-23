<?php
$title = "Mark Attendance";
require '../includes/sessions.php';
include '../includes/head.php';

if (
    !isloggedin() || !isset($_SESSION['accounttype']) ||
    !in_array($_SESSION['accounttype'], ['staff', 'admin']) || $_SESSION['userinfo']['status'] !== 1
) {
    header("Location: ../auth/login.php");
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
                <h1 class="page-title">MARK ATTENDANCE</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">ATTENDANCE</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">MY ATTENDANCE</div>
                    </div>

                    <div class="ibox-body">

                        <form id="mark-attendance">

                            <input type="hidden" id="user_id" name="id" class="form-control"
                                value="<?php echo htmlspecialchars($_SESSION['userinfo']['id']); ?>">
                            
                                <input type="hidden" name="csrf_token" 
                                value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">

                            <div class="row mt-2">
                                <label class="m-2">Employee Name</label>
                                <input type="text"  class="col-6" value="<?php echo isset($_SESSION['userinfo']) ?
                                                                htmlspecialchars($_SESSION['userinfo']['firstname'] . ' ' . $_SESSION['userinfo']['firstname']) : null ?>" disabled />
                            </div>



                            <div class="row mt-3 ">
                                <button class="btn btn-primary m-3">Mark Attendance</button>
                            </div>
                            <div class=" row flex-box">
                                <span class="errormsg text-danger fs-5"></span>
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
       
        import alertFunction from '../assets/js/alertFunction.js';
        import handleFormMessage from '../assets/js/handleFormMessage.js';
        import processForm from '../assets/js/processForm.js';
        document.addEventListener("DOMContentLoaded", () => {

            const attendanceform = document.getElementById("mark-attendance")


            if (attendanceform) {
                attendanceform.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(attendanceform, '../api/attendance/process.updateattendance.php');
                    handleFormMessage(resultData);
                });
            }
        })
    </script>
</body>

</html>