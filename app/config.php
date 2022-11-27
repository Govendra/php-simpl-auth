<?php
try{
    $conn = mysqli_connect("localhost", "root", "", "my_admin");
}catch(Exception $err){
    echo "error in db connection";
    echo $err->getMessage();
}

// session start
session_start();

// hide error from pages
error_reporting(0);

// realscape string funtion define
function realScSt($str){
    global $conn;
    return mysqli_real_escape_string($conn, $str);
}