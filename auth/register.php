<?php
$title="Register Account";
include '../includes/head.php';
require '../includes/sessions.php'
?>

<body class="bg-silver-300">
    <div class="content mb-4">
        <div class="brand">
            <a class="link" href="index.html">AdminCAST</a>
        </div>
        <form id="register-form"  method="post" enctype="multipart/form-data">
            <h2 class="login-title">Sign Up</h2>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']);?>"/>
           <?php
           
           signUpForm();
           if(isset($_SESSION['signuperrors'])){
            foreach($_SESSION['signuperrors'] as $error){
                echo "<div class='text-danger'>
                $error
                </div>";

            }
            unset($_SESSION['signuperrors']);
           }
           ?>
            <span class='error fs-6' style='text-align:center; color:red;'></span> <br>
                          
            <div class="social-auth-hr">
                <span>Or Sign up with</span>
            </div>
            <div class="text-center social-auth m-b-20">
                <a class="btn btn-social-icon btn-twitter m-r-5" href="javascript:;"><i class="fa fa-twitter"></i></a>
                <a class="btn btn-social-icon btn-facebook m-r-5" href="javascript:;"><i class="fa fa-facebook"></i></a>
                <a class="btn btn-social-icon btn-google m-r-5" href="javascript:;"><i class="fa fa-google-plus"></i></a>
                <a class="btn btn-social-icon btn-linkedin m-r-5" href="javascript:;"><i class="fa fa-linkedin"></i></a>
                <a class="btn btn-social-icon btn-vk" href="javascript:;"><i class="fa fa-vk"></i></a>
            </div>
            <div class="text-center">Already a member?
                <a class="color-blue" href="login.php">Login here</a>
            </div>
        </form>
    </div>
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS -->
     <?php
     include '../includes/scripts.php'
     ?>
     <script>
        const signUpForm=document.getElementById("register-form");
        const logerror=document.querySelector(".error")
        if(signUpForm){

            signUpForm.addEventListener("submit",async (e)=>{
                e.preventDefault()
                
            const formdata= new FormData(signUpForm);
            const formobj=Object.fromEntries(formdata.entries());
             // Handle the image separately
        const imageFile = formdata.get("image"); // Get the file from FormData
        if (imageFile && imageFile.size > 0) {
            // If an image is provided, include its base64 representation
            const imageBase64 = await convertToBase64(imageFile);
            formobj.image = imageBase64;
        }
            try{
            const response= await fetch('../api/userauth/process.signup.php',{method:'POST',
                                                                            headers:{
                                                                                'Content-Type':'application/json'
                                                                            },
                                                                            body:JSON.stringify(formobj)
                                                                            
            })
            if(!response.ok){
                throw new Error("error fetching data");
            }

            const data= await response.json();
            console.log(data)
            if(data.success){
                alert(data.message)
                window.location.href=data.redirecturl;
            }else{
                //alert(data.message)
                
                logerror.innerHTML=Object.values(data.errors).join("<br>")
                // setInterval(() => {
                //     window.location.href=data.redirecturl;
                    
                // }, 4000);
                
            }
        }catch(error){
            console.error(error)
        }
        })
    }
    async function convertToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
        reader.readAsDataURL(file); // Converts the file to a Data URL (Base64 string)
    });
}
     </script>
  </body>

</html>