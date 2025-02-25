<?php
$title = "Pay Workers";
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
$allowed = checkAccount(['admin']);
$nextMonth = date('M-Y', strtotime('first day of +1 month'));
        
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
            <?php if ($allowed): ?>
                <div class="d-flex justify-content-end">
                    <form id="add-payrollrecord">
                        <input type="hidden" id="user_id" name="id" class="form-control"
                            value="<?php echo htmlspecialchars($_SESSION['userinfo']['id']); ?>">
                        <input type="hidden" name="csrf_token"
                            value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">

                        <button class="btn btn-success">Insert Payroll For &nbsp;<?php echo htmlspecialchars($nextMonth) ?></button>

                    </form>
                </div>
            <?php endif ?>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Salary Payment</div>
                    </div>

                    <div class="ibox-body">

                        <form id="payrollform-create">
                            <div class="row mt-3">

                                <div class="col-6 mt-2">
                                    <label for="user-select">Worker Name</label>
                                    <select name="user_id" id="user-select" class="form-control" required>
                                        <option value="">Select Worker</option>

                                    </select>
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="m-2" for="total_salary">Total Salary</label>
                                    <input type="number" id="totalsalary" name="total_salary" class="form-control" value="0" step="0.01" min="0" placeholder="Base Salary" required>
                                </div>

                            </div>
                            <div class="row">
                                <input type="hidden" name="csrf_token" 
                                value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <div class="col-6 mt-2">
                                    <label class="m-2" for="name">Payment Date</label>
                                    <input type="date" name="date" class="form-control">
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="m-2" for="name">Due Date</label>
                                    <input type="date" name="due_date" class="form-control">
                                </div>
                                <div class="col-6 mt-2">
                                    <label for="user-select">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">Select Payment Status</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                        <option value="pending">Pending</option>

                                    </select>
                                </div>

                            </div>

                            <div class="row mt-3 ">
                                <button class="btn btn-primary m-3">Pay Worker</button>
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
            const addPayrollform = document.getElementById("payrollform-create")
            const insertPayrollRecordform = document.getElementById("add-payrollrecord")
            //const errorinfomsg=document.querySelector('.errormsg')


            if (addPayrollform) {
                addPayrollform.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(addPayrollform, '../api/payroll/process.addpayroll.php');
                    handleFormMessage(resultData);
                });
            }

            if (insertPayrollRecordform) {
                insertPayrollRecordform.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const resultData = await processForm(insertPayrollRecordform, '../api/payroll/process.insertpayroll.php');
                    handleFormMessage(resultData);
                });
            }


            (async () => {
                const data = await fetchAll('../api/userview/process.fetchall.php')
                fillUserSelect(data.users)
            })()

            const fillUserSelect = (users) => {
                userSelect.innerHTML = "";

                userSelect.innerHTML += `<option value="">Select Worker</option>`;
                userSelect.innerHTML += users.map(user => (`<option value="${user.id}" >${user.firstname} ${user.lastname}</option>`))
            }

            userSelect.addEventListener("change", (e) => {
                const id = e.target.value;
                (async () => {
                    const data = await fetchUserId('../api/payroll/process.fetchusersalary.php', id)
                    const totalSalary = document.querySelector('#totalsalary')
                    totalSalary.innerHTML =""
                    totalSalary.innerHTML =parseFloat(data.salary.total_salary)
                    totalSalary.value = parseFloat(data.salary.total_salary);

                })()

            })
        })
    </script>
</body>

</html>