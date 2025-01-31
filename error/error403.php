<?php
$title = "Error Page";
include '../includes/head.php';
include '../includes/sessions.php';

?>


<body class="bg-silver-100">
    <div class="content">
        <h1 class="m-t-20">403</h1>
        <p class="error-title">REQUEST NOT AUTHORIZED</p>
        <p class="m-b-20">FORBIDDEN!!!
            <a class="color-green" href="../auth/login.php">Go homepage</a></p>
        
    </div>
    <style>
        .content {
            max-width: 450px;
            margin: 0 auto;
            text-align: center;
        }

        .content h1 {
            font-size: 160px
        }

        .error-title {
            font-size: 22px;
            font-weight: 500;
            margin-top: 30px
        }
    </style>
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <?php
    require '../includes/scripts.php';
    ?>
</body>

</html>