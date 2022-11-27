<?php
include "app/config.php";

if(!isset($_SESSION['userId'])){
    header("LOCATION: login.php");
    die;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard my admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <h1 class="text-center">Dashboard</h1>

    <div class="row mt-4 text-success">
      <div class="col-md-4 border d-flex justify-content-center align-items-center">
            <h3>Hello <?php echo $_SESSION['userName']?></h3>
      </div>
      <div class="col-md-4 border d-flex justify-content-center align-items-center">
        <h3>You'r LogedIn</h3>
      </div>
      <div class="col-md-4 border d-flex justify-content-center align-items-center">
        <a href="logout.php"><button>log Out</button></a>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>