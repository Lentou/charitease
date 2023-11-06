<?php


session_start();

include '../../lib/config.php';
include '../../lib/database.php';

if (isset($_GET['email']) && isset($_GET['code'])) {

    $email = $_GET['email'];
    $code = $_GET['code'];

    $db = new Database();

    $get_all = $db->query("SELECT * FROM `tblusers` WHERE email = '$email' AND verification_pin = '$code'");

    if ($get_all) {
        $get_all_arr = $get_all->fetch_assoc();
        if ($get_all_arr["is_verified"] == '1') {
            $status_text = "Your email is already verified!";
            $status_code = "error";
        } else {

            $update = $db->query("UPDATE `tblusers` SET is_verified = '1' WHERE email = '$email' AND verification_pin = '$code'");
            
            if ($update) { 
                $status_text = "You are now verified! The admin verifying your account details";
                $status_code = "success";
            } else {
                $status_text = "Email or verification pin does not exists!";
                $status_code = "error";
            }
        }
    } else {
        $status_text = "Email or verification pin does not exists!";
        $status_code = "error";
    }

    $_SESSION['status'] = "Email Verify";
    $_SESSION['status_text'] = $status_text;
    $_SESSION['status_code'] = $status_code;
    location("../reg/login.php");

} 
?>