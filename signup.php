<?php
include "app/config.php";

if (isset($_POST['submit'])) {

    $err = 0;
    $errmsg = "";
    
    // realScSt() function is self define function for my sqli real scape string
    $name = realScSt($_POST['name']);
    $email = realScSt($_POST['email']);
    $password = realScSt($_POST['password']);
    $confirmPassword = realScSt($_POST['confirmPassword']);
    //  echo "$name $email $password $confirmPassword";
    if ($name != "" && $email != "" && $password != "" && $confirmPassword != "") {

        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $err = 1;
            $errmsg = "Password should be at least 8 characters, 
            one upper case letter, one number, and one special character.";
        } else {
            if ($password == $confirmPassword) {
                $selQry = "SELECT * FROM user WHERE email = '$email'";
                $exeUser = mysqli_query($conn, $selQry);
                $fetchUser = mysqli_fetch_assoc($exeUser);
                if(!isset($fetchUser['id'])){
                    $encodePass = md5($password);
                    $regQry = "INSERT INTO user SET 
                    name = '$name',
                    email = '$email',
                    password = '$encodePass'";
                    try{
                        mysqli_query($conn, $regQry);
                        $regFlage = 1;
                    }catch(Exception $regErr){
                        $regFlage = 0;
                        echo $regErr->getMessage();
                    }
                    if($regFlage == 1){
                        $err = 1;
                        $errmsg = "You Are Registerd Successfully";
                        $name = "";
                        $email = "";
                        $_SESSION['signupMassage'] = "You are registerd successfully login now";
                        header("LOCATION: login.php");
                    }else{
                        $err = 1;
                        $errmsg = "Error in internal server please try again later";
                    }
                }else{
                    $err = 1;
                    $errmsg = "Email allready exist";
                }

            } else {
                $err = 1;
                $errmsg = "Password and Confirm Password must match";
            }
        }
    } else {
        $err = 1;
        $errmsg = "All field is required";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login my admin</title>
    <link rel="stylesheet" href="css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/0718d125ef.js" crossorigin="anonymous"></script>
</head>

<body class="bg-secondary p-2">

    <div class="container-xxl pt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 p-4 rounded bg-white">
                <h2 class="text-center">Signup</h2>
                <?php
                if ($err == 1) { ?>
                    <h6 class=" text-center bg-danger mt-3 p-2 text-white"><?php echo $errmsg ?></h6>
                <?php } ?>
                <form method="POST">
                    <div class="form-main">

                        <div class="input-item">
                            <label for="name">Name</label>
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <input required type="text" name="name" placeholder="Enter your name" value="<?php echo $name ?>">
                        </div>

                        <div class="input-item mt-3">
                            <label for="email">Email</label>
                            <i class="fa-solid fa-envelope"></i>
                            <input required type="email" name="email" placeholder="Enter your email" value="<?php echo $email ?>">
                        </div>

                        <div class="input-item mt-3">
                            <label for="password">Password</label>
                            <i class="fa-solid fa-lock"></i>
                            <input required type="password" name="password" id="password" placeholder="Enter your password">
                            <i class="fa-regular fa-eye" id="passwordEye"></i>
                        </div>

                        <div class="input-item mt-3">
                            <label for="confirmPassword">Confirm Password</label>
                            <i class="fa-solid fa-lock"></i>
                            <input required type="password" name="confirmPassword" placeholder="Confirm your password">

                        </div>

                        <div class="input-item mt-3" style="border: none;">
                            <button type="submit" name="submit" id="submitBtn">Signup</button>
                        </div>
                        <div class="input-item mt-3" style="border: none;">
                            <p>All ready have an Accout ? <a href="login.php">Login Now</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script>
        $("#passwordEye").click(
            function() {
                var eyeBtn = $("#passwordEye");

                if (eyeBtn.hasClass("typeText")) {
                    eyeBtn.addClass("fa-eye");
                    eyeBtn.removeClass("fa-eye-slash");
                    eyeBtn.removeClass("typeText");
                    $("#password").attr("type", "password")
                } else {
                    eyeBtn.removeClass("fa-eye");
                    eyeBtn.addClass("fa-eye-slash");
                    eyeBtn.addClass("typeText");
                    $("#password").attr("type", "text")
                }
            }
        )
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>