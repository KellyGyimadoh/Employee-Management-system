<?php
$title = "Login";
include '../includes/head.php';
include '../includes/sessions.php';

?>

<body class="bg-silver-300">
    <div class="alertbox  hidden">
        <span class="alertmsg"></span>
    </div>
    <div class="content">
        <div class="brand">
            <a class="link" href="login.php">Login</a>
        </div>
        <form id="login-form" method="post">
            <h2 class="login-title">Log in</h2>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-envelope"></i></div>
                    <input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                </div>
            </div>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group d-flex justify-content-between">
                <label class="ui-checkbox ui-checkbox-info">
                    <input type="checkbox">
                    <span class="input-span"></span>Remember me</label>
                <a href="forgot_password.html">Forgot password?</a>
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block" type="submit">Login</button>
            </div>
            <div>
                <span class="error text-danger fs-6 text-center"></span>
                <span class="errormsg text-danger fs-6 text-center"></span>
            </div>
            <div class="social-auth-hr">
                <span>Or login with</span>
            </div>
            <div class="text-center social-auth m-b-20">
                <a class="btn btn-social-icon btn-twitter m-r-5" href="javascript:;"><i class="fa fa-twitter"></i></a>
                <a class="btn btn-social-icon btn-facebook m-r-5" href="javascript:;"><i class="fa fa-facebook"></i></a>
                <a class="btn btn-social-icon btn-google m-r-5" href="javascript:;"><i class="fa fa-google-plus"></i></a>
                <a class="btn btn-social-icon btn-linkedin m-r-5" href="javascript:;"><i class="fa fa-linkedin"></i></a>
                <a class="btn btn-social-icon btn-vk" href="javascript:;"><i class="fa fa-vk"></i></a>
            </div>
            <div class="text-center">Not a member?
                <a class="color-blue" href="register.php">Create account</a>
            </div>
        </form>
    </div>
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <!-- <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div> -->
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS -->
    <?php
    require '../includes/scripts.php';
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const loginform = document.querySelector("#login-form");
            const errorinfo = document.querySelector(".error");
            const errorinfomsg = document.querySelector(".errormsg");
            const alertbox = document.querySelector(".alertbox");
            const alertboxmsg = document.querySelector(".alertmsg");
            if (loginform) {
                loginform.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formdata = new FormData(loginform);
                    const formobj = Object.fromEntries(formdata.entries())
                    try {
                        const response = await fetch('../api/userauth/process.login.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(formobj)
                        })
                        if (!response.ok) {
                            throw new Error('error processing data' + response.status);
                        }
                        
                        const data = await response.json();



                        if (data.success) {

                            alert(data.message);

                            window.location.href = data.redirecturl;
                        } else {
                            errorinfomsg.innerHTML = data.errors ? Object.values(data.errors).join("<br>") : data.message;
                           

                           
                        }
                    } catch (error) {
                        console.error(error);
                        errorinfomsg.innerHTML="Unexpected error occured.Refresh page and try again"
                    }

                })
            }
        })
    </script>
</body>

</html>