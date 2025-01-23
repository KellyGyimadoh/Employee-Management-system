<?php
include '../includes/sessions.php';
$title = isset($_SESSION['userinfo']) ? ucfirst($_SESSION['userinfo']['firstname']) . ' ' . 'Profile' : 'User Profile';
include '../includes/head.php';

if (
    !isloggedin() || !isset($_SESSION['accounttype']) ||
    !in_array($_SESSION['accounttype'], ['staff', 'admin'])
) {
    header("Location: ../auth/login.php");
    die();
}
if (isset($_SESSION['userinfo'])) {
    $userinfo = $_SESSION['userinfo'];
}
?>

<body class="fixed-navbar sidebar-mini">
    <div class="page-wrapper">

        <!-- START HEADER-->
        <?php
        include('../includes/header.php');
        include('../includes/sidebar.php');

        ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <?php
            include '../includes/alert.php'
            ?>
            <!-- START PAGE CONTENT-->
            <div class="page-heading">
                <h1 class="page-title">Profile</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="la la-home font-20"></i></a>
                    </li>
                    <li class="breadcrumb-item">Profile</li>
                </ol>
            </div>
            <div class="page-content fade-in-up">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="ibox">
                            <div class="ibox-body text-center">
                                <div class="m-t-20">
                                    <img class="img-circle" src="<?php echo isset($userinfo['image']) ? htmlspecialchars($userinfo['image']) : '../assets/img/users/u3.jpg' ?>" />

                                </div>
                                <h5 class="font-strong m-b-10 m-t-10"><?php echo htmlspecialchars($userinfo['firstname'] . ' ' . $userinfo['lastname']) ?></h5>
                                <div class="m-b-20 text-muted"><?php echo htmlspecialchars($userinfo['account_type']) ?></div>
                                <div class="profile-social m-b-20">
                                    <a href="javascript:;"><i class="fa fa-twitter"></i></a>
                                    <a href="javascript:;"><i class="fa fa-facebook"></i></a>
                                    <a href="javascript:;"><i class="fa fa-pinterest"></i></a>
                                    <a href="javascript:;"><i class="fa fa-dribbble"></i></a>
                                </div>
                                <div>
                                    <button class="btn btn-info btn-rounded m-b-5"><i class="fa fa-plus"></i> Follow</button>
                                    <button class="btn btn-default btn-rounded m-b-5">Message</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="ibox">
                            <div class="ibox-body">
                                <ul class="nav nav-tabs tabs-line">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-2" data-toggle="tab"><i class="ti-settings"></i> Settings</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-3" data-toggle="tab"><i class="ti-announcement"></i> Feeds</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-1">
                                        <div class="row">
                                            <div class="col-md-12" style="border-right: 1px solid #eee;">
                                                <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-bar-chart"></i> Month Statistics</h5>
                                                <div class="h2 m-0">$12,400<sup>.60</sup></div>
                                                <div><small>Month income</small></div>
                                                <div class="m-t-20 m-b-20">
                                                    <div class="h4 m-0">120</div>
                                                    <div class="d-flex justify-content-between"><small>Month income</small>
                                                        <span class="text-success font-12"><i class="fa fa-level-up"></i> +24%</span>
                                                    </div>
                                                    <div class="progress m-t-5">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" style="width:50%; height:5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="m-b-20">
                                                    <div class="h4 m-0">86</div>
                                                    <div class="d-flex justify-content-between"><small>Month income</small>
                                                        <span class="text-warning font-12"><i class="fa fa-level-down"></i> -12%</span>
                                                    </div>
                                                    <div class="progress m-t-5">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:50%; height:5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <ul class="list-group list-group-full list-group-divider">
                                                    <li class="list-group-item">Projects
                                                        <span class="pull-right color-orange">15</span>
                                                    </li>
                                                    <li class="list-group-item">Tasks
                                                        <span class="pull-right color-orange">148</span>
                                                    </li>
                                                    <li class="list-group-item">Articles
                                                        <span class="pull-right color-orange">72</span>
                                                    </li>
                                                    <li class="list-group-item">Friends
                                                        <span class="pull-right color-orange">44</span>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                        <h4 class="text-info m-b-20 m-t-20"><i class="fa fa-shopping-basket"></i> Latest Orders</h4>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Customer</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th width="91px">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>11</td>
                                                    <td>@Jack</td>
                                                    <td>$564.00</td>
                                                    <td>
                                                        <span class="badge badge-success">Shipped</span>
                                                    </td>
                                                    <td>10/07/2017</td>
                                                </tr>
                                                <tr>
                                                    <td>12</td>
                                                    <td>@Amalia</td>
                                                    <td>$220.60</td>
                                                    <td>
                                                        <span class="badge badge-success">Shipped</span>
                                                    </td>
                                                    <td>10/07/2017</td>
                                                </tr>
                                                <tr>
                                                    <td>13</td>
                                                    <td>@Emma</td>
                                                    <td>$760.00</td>
                                                    <td>
                                                        <span class="badge badge-default">Pending</span>
                                                    </td>
                                                    <td>10/07/2017</td>
                                                </tr>
                                                <tr>
                                                    <td>14</td>
                                                    <td>@James</td>
                                                    <td>$87.60</td>
                                                    <td>
                                                        <span class="badge badge-warning">Expired</span>
                                                    </td>
                                                    <td>10/07/2017</td>
                                                </tr>
                                                <tr>
                                                    <td>15</td>
                                                    <td>@Ava</td>
                                                    <td>$430.50</td>
                                                    <td>
                                                        <span class="badge badge-default">Pending</span>
                                                    </td>
                                                    <td>10/07/2017</td>
                                                </tr>
                                                <tr>
                                                    <td>16</td>
                                                    <td>@Noah</td>
                                                    <td>$64.00</td>
                                                    <td>
                                                        <span class="badge badge-success">Shipped</span>
                                                    </td>
                                                    <td>10/07/2017</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="tab-2">
                                        <form method="post" id="profile-form">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">
                                            <?php
                                            if(isset($_SESSION['accounttype']) && $_SESSION['accounttype']=='admin')
                                            {
                                             managerProfileForm(); 
                                            }else
                                            {
                                                userProfileForm();

                                            }
                                            ?>
                                        </form>
                                        <!-- passwordform -->
                                        <div class="mt-3">
                                            <h5 class="text-danger m-b-20 mt-4 border-bottom-1"><i class="fa fa-arrow"></i>Change Password</h5>

                                            <form method="post" id="password-form">
                                                <div class='col-sm-6 form-group'>
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']) ?>">

                                                    <input class='form-control' type='hidden' name='userid' value="<?php echo htmlspecialchars($_SESSION['userinfo']['id']) ?>">
                                                </div>
                                                <div class='col-sm-6 form-group'>
                                                    <label>Old Password</label>
                                                    <input class='form-control' type='password' name='old_password' placeholder="Old Password" />
                                                </div>
                                                <div class='col-sm-6 form-group'>
                                                    <label>New Password</label>
                                                    <input class='form-control' type='password' name='new_password' placeholder="New Password" />
                                                </div>
                                                <div class='form-group item-center'>
                                                    <button class='btn btn-primary btn-rounded' type='submit'>Change Password</button>
                                                </div>
                                            </form>
                                            <div class="flex-box">
                                                    <span class="errormsg text-danger fs-5"></span>
                                            </div>
                                            <!-- passwordform -->
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tab-3">
                                        <h5 class="text-info m-b-20 m-t-20"><i class="fa fa-bullhorn"></i> Latest Feeds</h5>
                                        <ul class="media-list media-list-divider m-0">
                                            <li class="media">
                                                <div class="media-img"><i class="ti-user font-18 text-muted"></i></div>
                                                <div class="media-body">
                                                    <div class="media-heading">New customer <small class="float-right text-muted">12:05</small></div>
                                                    <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <div class="media-img"><i class="ti-info-alt font-18 text-muted"></i></div>
                                                <div class="media-body">
                                                    <div class="media-heading text-warning">Server Warning <small class="float-right text-muted">12:05</small></div>
                                                    <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <div class="media-img"><i class="ti-announcement font-18 text-muted"></i></div>
                                                <div class="media-body">
                                                    <div class="media-heading">7 new feedback <small class="float-right text-muted">Today</small></div>
                                                    <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <div class="media-img"><i class="ti-check font-18 text-muted"></i></div>
                                                <div class="media-body">
                                                    <div class="media-heading text-success">Issue fixed <small class="float-right text-muted">12:05</small></div>
                                                    <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <div class="media-img"><i class="ti-shopping-cart font-18 text-muted"></i></div>
                                                <div class="media-body">
                                                    <div class="media-heading">7 New orders <small class="float-right text-muted">12:05</small></div>
                                                    <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <div class="media-img"><i class="ti-reload font-18 text-muted"></i></div>
                                                <div class="media-body">
                                                    <div class="media-heading text-danger">Server warning <small class="float-right text-muted">12:05</small></div>
                                                    <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .profile-social a {
                        font-size: 16px;
                        margin: 0 10px;
                        color: #999;
                    }

                    .profile-social a:hover {
                        color: #485b6f;
                    }

                    .profile-stat-count {
                        font-size: 22px
                    }
                </style>

            </div>
            <!-- END PAGE CONTENT-->
           <?php
           
           include '../includes/footer.php'
           ?>
        </div>
    </div>

    <!-- END THEME CONFIG PANEL-->
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS-->
    <?php
    include('../includes/scripts.php');
    ?>
    <script type="module">
         import fetchAll from '../assets/js/fetchAll.js'
         import alertFunction from '../assets/js/alertFunction.js'
         import handleFormMessage from '../assets/js/handleFormMessage.js';
         import processForm from '../assets/js/processForm.js';
        document.addEventListener("DOMContentLoaded", () => {
            const profileForm = document.querySelector("#profile-form");
            const passwordForm = document.querySelector("#password-form");
            if (profileForm) {
                profileForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const resultData = await processForm(profileForm, '../api/userauth/process.updateuser.php');
                handleFormMessage(resultData);
            });
            }
            if (passwordForm) {
                passwordForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const resultData = await processForm(passwordForm, '../api/userauth/process.updatepassword.php');
                handleFormMessage(resultData);
            });
            }

        })


        
       
        
        
    </script>
</body>

</html>