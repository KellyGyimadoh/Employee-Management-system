<?php
$title = "Request Leave";
require '../includes/sessions.php';
include '../includes/head.php';

if (
    !isloggedin() || !isset($_SESSION['accounttype']) ||
    !in_array($_SESSION['accounttype'], ['staff', 'admin'])
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
                <h1 class="page-title">CREATE NEW LEAVE REQUEST</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">NEW REQUEST</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Setup New Leave Request</div>
                    </div>

                    <div class="ibox-body">

                        <form id="leaveform-create">
                            <div class="row mt-3">

                                <input type="hidden" name="csrf_token"
                                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="user_id"
                                    value="<?php echo htmlspecialchars($_SESSION['userinfo']['id']) ?>">

                                <div class="col-6 mt-2">
                                    <label class="m-2" for="type">Reason</label>
                                    <input type="text" name="type" class="form-control" placeholder="Reason" required>
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="m-2" for="start_date">Start Date</label>
                                    <input type="date" name="start_date" class="form-control"/>
                                </div>

                            </div>
                            <div class="row">
                            <div class="col-6 mt-2">
                                    <label class="m-2" for="end_date">End Date</label>
                                    <input type="date" name="end_date" class="form-control"/>
                                </div>


                               
                               
                            </div>

                            <div class="row mt-3 w-auto d-flex justify-content-center ">
                                <button class="btn btn-primary w-auto">Request Leave</button>
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
            const addTaskform = document.getElementById("leaveform-create")
          


            if (addTaskform) {
                addTaskform.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(addTaskform, '../api/leave/process.addleave.php');
                    handleFormMessage(resultData);
                });
            }


           
            



        })
    </script>
</body>

</html>