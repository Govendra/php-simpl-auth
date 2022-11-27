<?php
include "app/config.php";

$err = 0;
$errmsg = "";
$msgBg = "bg-danger";

if ($_SESSION['signupMassage'] != "") {
    $err = 1;
    $msgBg = "bg-success";
    $errmsg = $_SESSION['signupMassage'];
    $_SESSION['signupMassage'] = "";
}

if (isset($_POST['submit'])) {
    
    // realScSt() function is self define function for my sqli real scape string
    $email = realScSt($_POST['email']);
    $password = realScSt($_POST['password']);
    

    if ($email != "" && $password != "") {
        $finalPass = md5($password);
        $selUser = "SELECT * FROM user WHERE email = '$email' AND password = '$finalPass'";
        $exeUser = mysqli_query($conn, $selUser);
        $fetchUser = mysqli_fetch_assoc($exeUser);
        if (isset($fetchUser['id'])) {
            $_SESSION['userId'] = $fetchUser['id'];
            $_SESSION['userName'] = $fetchUser['name'];
            $_SESSION['userEmail'] = $fetchUser['email'];

            $email = "";
            header("LOCATION: index.php");
        } else {
            $err = 1;
            $errmsg = "Invalid Credentiales";
        }
    } else {
        $err = 1;
        $errmsg = "All fields are required";
    }
}

if (isset($_POST['forget'])) {
    $email = realScSt($_POST['email']);

    if ($email != "") {
        $selUser = "SELECT * FROM user WHERE email = '$email'";
        $exeUser = mysqli_query($conn, $selUser);
        $fetchUser = mysqli_fetch_assoc($exeUser);
        $userId = $fetchUser['id'];
        if (isset($fetchUser['id'])) {
            function tokenGen()
            {
                global $conn;
                $token = bin2hex(random_bytes(50));
                $selTkn = "SELECT * FROM reset_password WHERE token = '$token'";
                $exeTkn = mysqli_query($conn, $selTkn);
                $countTkn = mysqli_num_rows($exeTkn);
                if ($countTkn > 0) {
                    return tokenGen();
                }
                return $token;
            }
            $token = tokenGen();
            $insTkn = "INSERT INTO reset_password SET user_id = '$userId', token = '$token'";
            $exeIns = mysqli_query($conn, $insTkn);

            // after mail configuration mail this variable $resetLink to  $email user's email id
            $resetLink = "http://localhost/my-admin/reset-password.php?token=$token";

            // after mail configuration remove this line
            echo "password reset link is $resetLink";

            $err = 1;
            $errmsg = "password reset link sent to your email";

            $email = "";
        } else {
            $err = 1;
            $errmsg = "You have entered an incorrect email";
        }
    } else {
        $err = 1;
        $errmsg = "Email is required";
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
                <h2 class="text-center">Login</h2>
                <?php
                if ($err == 1) { ?>
                    <h6 class=" text-center mt-3 p-2 text-white <?php echo $msgBg?>"><?php echo $errmsg ?></h6>
                <?php } ?>
                <form method="POST">
                    <div class="form-main mt-3">
                        <div class="input-item">
                            <label for="email">User Email</label>
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <input required type="email" name="email" placeholder="Enter your email" value="<?php echo $email ?>">
                        </div>

                        <div class="input-item mt-3">
                            <label for="password">User Password</label>
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Enter your password">
                            <i class="fa-regular fa-eye" id="passwordEye"></i>
                        </div>
                        <div class="input-item mt-3 text-end" style="border: none;">
                            <button type="submit" name="forget" id="forgotBtn">Forgot Password ?</button>
                        </div>
                        <div class="input-item" style="border: none;">
                            <button type="submit" name="submit" id="submitBtn">Login</button>
                        </div>
                        <div class="input-item mt-3" style="border: none;">
                            <p>Not an Accout ? <a href="signup.php">Signup Now</a></p>
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