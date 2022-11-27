<?php
include "app/config.php";
// error_reporting(E_ALL);
$err = 0;
$errmsg = "";

$token = $_GET['token'];

$seltkn = "SELECT * FROM reset_password WHERE token = '$token'";
$exeTkn = mysqli_query($conn, $seltkn);
$fetchToken = mysqli_fetch_assoc($exeTkn);

$userId = $fetchToken['user_id'];

if (isset($fetchToken['user_id'])) {
    $tokenActive = 1;
} else {
    $err = 1;
    $errmsg = "Your password reset link is expired or invalid";
}

if (isset($_POST['submit'])) {
    // realScSt() function is self define function for my sqli real scape string
    $password = realScSt($_POST['password']);
    $confirmPass = realScSt($_POST['confirmPassword']);
    // echo "$email $password";

    if ($password != "" && $confirmPass != "") {
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $err = 1;
            $errmsg = "Password should be at least <br/> 8 characters <br/> 
            one upper case letter <br/> one number <br/> and one special character.";
        } else {
            if ($password == $confirmPass) {
                $finalPass = md5($password);
                $upd = "UPDATE user SET password = '$finalPass' WHERE id = '$userId'";
                try {
                    $exeUpd = mysqli_query($conn, $upd);
                    $passUpdated = 1;
                } catch (Exception $excErr) {
                    $err = 1;
                    $errmsg = "Error in internal server try again later";
                }
                if ($passUpdated == 1) {
                    $delQry = "DELETE FROM reset_password WHERE token = '$token'";
                    $exeDel = mysqli_query($conn, $delQry);
                    $_SESSION['signupMassage'] = "Your password changed successfully login now";
                    header("LOCATION: login.php");
                }
            } else {
                $err = 1;
                $errmsg = "Password and confirm password must match";
            }
        }
    } else {
        $err = 1;
        $errmsg = "All fields are required";
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
                <h2 class="text-center">Reset Password</h2>
                <?php
                if ($err == 1) { ?>
                    <h6 class=" text-center bg-danger mt-3 p-2 text-white"><?php echo $errmsg ?></h6>
                <?php }
                if ($tokenActive == 1) {
                ?>
                    <form method="POST">
                        <div class="form-main mt-3">

                            <div class="input-item mt-3">
                                <label for="password">New Password</label>
                                <i class="fa-solid fa-lock"></i>
                                <input required type="password" name="password" id="password" placeholder="Enter your New password">
                                <i class="fa-regular fa-eye" id="passwordEye"></i>
                            </div>

                            <div class="input-item mt-3">
                                <label for="confirmPassword">Confirm Password</label>
                                <i class="fa-solid fa-lock"></i>
                                <input required type="password" name="confirmPassword" placeholder="Confirm your New password">
                            </div>

                            <div class="input-item mt-3" style="border: none;">
                                <button type="submit" name="submit" id="submitBtn">Reset Password</button>
                            </div>
                        </div>
                    </form>
                <?php } ?>

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