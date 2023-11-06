<?php

include '../../lib/config.php';
include '../../lib/database.php';
require '../../lib/email.php';

if (!isset($_SESSION)) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();

    // Donor and Org Validation
    if (isset($_POST['client_yes'])) {
        $client_id = $_POST["client_id"];
        $account_type = $_POST["account_type"];

        $users = $db->query("SELECT * FROM `tblusers` WHERE user_id = '$client_id' AND account_type = '$account_type'")->fetch_assoc();
        $clients = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$client_id'")->fetch_assoc();

        $email = $users['email'];
        $client_name = $clients["client_name"];

        $user_yes = $db->query("UPDATE `tblusers` SET date_approved = NOW() WHERE user_id = '$client_id' AND account_type = '$account_type'");
        $client_yes = $db->query("UPDATE `tblclients` SET is_approved = '1' WHERE client_id = '$client_id'");

        $account = ($account_type == "d") ? "donor" : "charity";

        if ($user_yes && $client_yes) {

            $body = "Dear " . $client_name . "<br> <br>";
            $body .= "We hope this message finds you well. Thank you for choosing us as your trusted charity service provider.<br> ";
            $body .= "We appreciate the time and effort you invested in providing us with detailed information.<br><br> ";
            $body .= "After careful review of the application, your " . $account . " account is now registered!<br>";
            $body .= "You can now login in our system<br> <br>";
            $body .= "<a href='http://localhost:8081/charitease/index.php?cid=$client_id&ctype=$account_type&cname=$client_name'><button>Click To Login</button></a>";
            $body .= " <br><br> For your information.";
            $body .= " <br><br> (c) 2023 CharitEase - All Right Reserves.";

            email::sendEmail($email, "CharitEase - Account Registration", $body);

            $text = "Successfully validated the " . $account . " account!";

            $_SESSION["status"] = "Registrer Validation Success";
            $_SESSION["status_text"] = $text;
            $_SESSION["status_code"] = "success";
            location('../adm/dashboard.php');
        } else {

            $text = "Failed to validate the " . $account;

            $_SESSION["status"] = "Register Validation Failed";
            $_SESSION["status_text"] = $text;
            $_SESSION["status_code"] = "error";
            location('../adm/dashboard.php');
        }
    }

    if (isset($_POST['client_no'])) {
        $client_id = $_POST["client_id"];
        $account_type = $_POST["account_type"];

        $clients = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$client_id'")->fetch_assoc();
        $users = $db->query("SELECT * FROM `tblusers` WHERE user_id = '$client_id' AND account_type = '$account_type'")->fetch_assoc();

        $email = $users['email'];
        $client_name = $clients["client_name"];

        $reason = $_POST["reason"];

        if ($reason == "") {
            $_SESSION["status"] = "Register Validation Failed";
            $_SESSION["status_text"] = "You didnt put any reason to deny the validation!";
            $_SESSION["status_code"] = "error";
            location('../adm/dashboard.php');
        }

        $image_no = $db->query("DELETE FROM `tblimages` WHERE client_id = '$client_id'");
        $client_no = $db->query("DELETE FROM `tblclients` WHERE client_id = '$client_id'");
        $user_no = $db->query("DELETE FROM `tblusers` WHERE user_id = '$client_id'");

        //$reason = $_POST["reason"];
        $account = ($account_type == "d") ? "donor" : "charity";

        if ($client_no && $user_no) {
            $body = "Dear " . $client_name . "<br> <br>";
            $body .= "We hope this message finds you well. Thank you for choosing us as your trusted charity service provider.<br> ";
            $body .= "We appreciate the time and effort you invested in providing us with detailed information.<br><br> ";
            $body .= "After careful review of the application, it is with regret that we inform you that the registration has not been approved at this time.<br>";
            $body .= " <br><br> <strong>Reason: </strong> " . $reason;
            $body .= " <br><br> For your information.";
            $body .= " <br><br> (c) 2023 CharitEase - All Right Reserves.";

            email::sendEmail($email, "CharitEase - Account Registration", $body);

            $text = "Successfully denied the " . $account;

            $_SESSION["status"] = "Register Validation Success";
            $_SESSION["status_text"] = $text;
            $_SESSION["status_code"] = "success";
            location('../adm/dashboard.php');
        } else {

            $text = "Failed to validate the " . $account;

            $_SESSION["status"] = "Register Validation Failed";
            $_SESSION["status_text"] = $text;
            $_SESSION["status_code"] = "error";
            location('../adm/dashboard.php');
        }

    }

}
?>