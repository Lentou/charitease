<?php

include '../../lib/config.php';
include '../../lib/database.php';
require '../../lib/email.php';

if (!isset($_SESSION)) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $account_type = $_POST['account_type'];

    // can be empty
    $client_user_type = isset($_POST['client_user_type']) ? $_POST['client_user_type'] : "";

    $client_name = $_POST['client_name'];
    $client_contact_name = $_POST['client_contact_name'];
    $bday = $_POST['bday'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $client_phone = $_POST['client_phone'];

    //$client_permit = $_FILES['client_permit'];

    // can be empty
    $date_founded = isset($_POST['date_founded']) ? $_POST['date_founded'] : "";
    $client_org_type = isset($_POST['client_org_type']) ? $_POST['client_org_type'] : "";

    $client_address = $_POST['client_address'];
    $client_lat = $_POST['client_lat'];
    $client_lng = $_POST['client_lng'];

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // the logical statement
    $db = new Database();

    $today = new \DateTime();
    $birthday = new \DateTime($bday);
    $age = $today->diff($birthday)->y;

    $log = "AT: " . $account_type . "\n";
    $log .= "UT: " . $client_user_type . "\n";
    $log .= "N: " . $client_name . "\n";
    $log .= "CN: " . $client_contact_name . "\n";
    $log .= "BD: " . $bday . "\n";
    $log .= "G: " . $gender . "\n";
    $log .= "EM: " . $email . "\n";
    $log .= "CP#: " . $client_phone . "\n";
    $log .= "DF: " . $date_founded . "\n";
    $log .= "COT: " . $client_org_type . "\n";
    $log .= "ADD: " . $client_address . "\n";
    $log .= "LAT: " . $client_lat . "\n";
    $log .= "LNG: " . $client_lng . "\n";
    $log .= "PASS: " . $password . "\n";
    //console_log($log);

    if ($age < 18) {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "You are 18 below!";
        $_SESSION['status_code'] = "error";
        location("../../reg/register.php");
    }

    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Please confirm your password!";
        $_SESSION['status_code'] = "error";
        location("../../reg/register.php");
    }

    if ($account_type == "c") {
        if ($client_user_type == "" || $date_founded == "" || $client_org_type == "") {
            $_SESSION['status'] = "Registration Failed";
            $_SESSION['status_text'] = "Please fill up the organization requirements!";
            $_SESSION['status_code'] = "error";
            location("../../reg/register.php");
        }
    }

    if ($client_lat == "" && $client_lng == "") {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Please mark your address to clarify your location!";
        $_SESSION['status_code'] = "error";
        location("../../reg/register.php");
    }

    // checking email from db
    $check_email = $db->query("SELECT email FROM `tblusers` WHERE email = '$email'");

    if ($check_email) {
        if ($check_email->num_rows > 0) {
            $_SESSION['status'] = "Registration Failed";
            $_SESSION['status_text'] = "Email has already registered!";
            $_SESSION['status_code'] = "error";
            location("../../reg/register.php");
        }
    }

    $hash_pass = password_hash($password, PASSWORD_DEFAULT);
    $verify_pin = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

    $add_user = $db->query("INSERT INTO `tblusers` (
        email,
        password,
        account_type,
        is_verified,
        verification_pin,
        bday,
        gender,
        date_created
    ) VALUES (
        '$email',
        '$hash_pass',
        '$account_type',
        '0',
        '$verify_pin',
        '$bday',
        '$gender',
        NOW()
    )");

    if ($add_user) {
        $user_id = $db->lastInsertId();

        $add_client = $db->query("INSERT INTO `tblclients` (
            client_id,
            client_name,
            client_phone,
            client_contact_name,
            client_address,
            client_bio,
            client_lat,
            client_lng,
            client_user_type,
            client_org_type,
            date_founded,
            is_approved
        ) VALUES (
            '$user_id',
            '$client_name',
            '$client_phone',
            '$client_contact_name',
            '$client_address',
            '',
            '$client_lat',
            '$client_lng',
            '$client_user_type',
            '$client_org_type',
            '$date_founded',
            '0'
        )");

        if (isset($_FILES['client_permit'])) {
            $files = $_FILES['client_permit'];
            $totalFiles = count($files['name']);

            $category = ($account_type == "d") ? (($client_user_type == "i") ? "valid_ids" : "permit") : "permit";

            for ($i = 0; $i < $totalFiles; $i++) {
                if (!empty($files["tmp_name"][$i])) {
                        
                    $image_name = $files["name"][$i];
                    $image_data = base64_encode(file_get_contents(addslashes($files["tmp_name"][$i])));
                    $client_id = $user_id;

                    $images = $db->query("INSERT INTO `tblimages`(
                        category,
                        image_name,
                        image_data,
                        client_id,
                        event_id,
                        sub_event_id
                    ) VALUES (
                        '$category',
                        '$image_name',
                        '$image_data',
                        '$client_id',
                        NULL,
                        NULL
                    )");
                }
            }
        }

        if ($add_client) {
            $title = "CharitEase Registration";
            $body = "Hello " . $client_name . "!<br>";
            $body .= "Please click the button below to verify your email address <br>";
            //$body .= "We hope this message finds you well. Thank you for choosing us as your trusted charity service provider.<br> ";
            //$body .= "If this email is from you, we kindly request you to click the link below to verify your account in CharitEase.<br> <br>";
            $body .= "<button formaction='http://localhost:8081/charitease/p/reg/action/verify.php?email=$email&code=$verify_pin'>Verify Email Address</button>";
            $body .= " <br> If you did not create an account, no further action is required.";
            $body .= " <br><br> For your information.";
            $body .= " <br><br> (c) 2023 CharitEase - All Right Reserves.";

            email::sendEmail($email, $title, $body);

            $_SESSION['status'] = "Registration Success";
            $_SESSION['status_text'] = "Registration Success! we sent you a verification in your email!";
            $_SESSION['status_code'] = "success";
            location("../../reg/login.php");
        } else {
            $delete_images = $db->query("DELETE FROM `tblimages` WHERE table_id = '$user_id'");
            $delete_user = $db->query("DELETE FROM `tblusers` WHERE user_id = '$user_id'");

            $_SESSION['status'] = "Registration Failed";
            $_SESSION['status_text'] = "Registration failed! Please try again later [Add Client Failed]";
            $_SESSION['status_code'] = "error";
            location("../../reg/register.php");
        }
    } else {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Registration failed! Please try again later [Add User Failed]";
        $_SESSION['status_code'] = "error";
        location("../../reg/register.php");
    }
}
?>