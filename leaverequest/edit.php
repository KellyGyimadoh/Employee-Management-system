<?php
$title = " Edit  Leave Request";
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
                <h1 class="page-title">EDIT LEAVE REQUEST</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="allworkers.php"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">EDIT REQUEST</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Edit Leave Request</div>
                    </div>

                    <div class="ibox-body">

                        <form id="leaveform-create">
                            <div class="row mt-3">

                                <input type="hidden" name="csrf_token"
                                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                   <?php
                                   editUserLeaveForm();
                                   ?>

                            <div class="row mt-3 w-auto d-flex justify-content-center ">
                                <button class="btn btn-primary w-auto">Edit Leave</button>
                            </div>
                            <div class=" row flex-box">
                                <span class="errormsg text-danger fs-5"></span>
                            </div>
                        </form>

                        <form id="leaveform-delete">
                            <div class="row mt-3">

                                <input type="hidden" name="csrf_token"
                                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="id"
                                    value="<?php echo htmlspecialchars($_SESSION['userleavedetails']['id']) ?>">
                              

                            <div class="row m-3 w-auto d-flex justify-content-center ">
                                <button class="btn btn-danger w-auto">Delete request</button>
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
            const editUserLeave = document.getElementById("leaveform-create")
            const deleteUserLeave = document.getElementById("leaveform-delete")
          
            const userHead = document.getElementById("user-head")

            if (editUserLeave) {
                editUserLeave.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(editUserLeave, '../api/leave/process.updateleave.php');
                    handleFormMessage(resultData);
                });
            }

            if (deleteUserLeave) {
                deleteUserLeave.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(deleteUserLeave, '../api/leave/process.deleterequest.php');
                    handleFormMessage(resultData);
                });
            }
            (async () => {
              

                const headDepartmentData = await fetchAll('../api/departments/process.getheads.php')
                fillHeadSelect(headDepartmentData.departmentheads)

              
            })()

           
            const fillHeadSelect = (departments) => {
                

               
                userHead.innerHTML += departments.map(dept => (`<option value="${dept.user_id}" >
                ${dept.firstname} ${dept.lastname}-${dept.name} HOD</option>`))
            }



        })
    </script>
</body>

</html>