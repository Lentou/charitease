<?php

include '../../lib/config.php';
include '../../lib/database.php';

if (!isset($_SESSION)) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();

    if (isset($_POST['edit_submit'])) {

        $client_id = $_POST['client_id'];
        $client_name = $_POST['client_name'] ?? "";
        $client_contact_name = $_POST['client_contact_name'] ?? "";
        $client_address = $_POST['client_address'] ?? "";
        $client_user_type = $_POST['client_user_type'] ?? "";
        $client_org_type = $_POST['client_org_type'] ?? "";
        $client_phone = $_POST['client_phone'] ?? "";

        if (!empty($_POST['profile_pic'])) {
            
            $files = $_FILES['profile_pic'];

            $tmpFilePath = $files["tmp_name"];
            $fileType = $files["type"];
            $imageName = $files["name"];
            $imageData = base64_encode(file_get_contents(addslashes($tmpFilePath)));

            $has_icon = $db->query("SELECT * FROM `tblimages` WHERE client_id = '$client_id' AND category = 'icon'");
            if ($has_icon && $has_icon->num_rows > 0) {
                $update_pic = $db->query("UPDATE `tblimages` SET image_name = '$imageName', image_data = '$imageData' WHERE client_id = '$client_id' AND category = 'icon'");
            } else {
                $insert_pic = $db->query("INSERT INTO `tblimages` (category, image_name, image_data, client_id, event_id, sub_event_id) VALUES ('icon', '$imageName', '$imageData', '$client_id', NULL, NULL)");
            }
        }

        if ($client_contact_name == "" || $client_contact_name == null) {
            $client_contant_name = $client_name;
        }

        $newProfileText = "UPDATE `tblclients` SET
            client_name = '$client_name',
            client_phone = '$client_phone',
            client_contact_name = '$client_contact_name',
            client_address = '$client_address',
            client_user_type = '$client_user_type',
            client_org_type = '$client_org_type' WHERE client_id = '$client_id'
        ";

        $newProfileResult = $db->query($newProfileText);

        $status = ($newProfileResult ? "Success" : "Failed");
        $status_text = ($newProfileResult ? "Profile updated successfully!" : "Error updating profile, please try again!");
        $status_code = ($newProfileResult ? "success" : "error");
        $_SESSION["status"] = "Edit Profile " . $status;
        $_SESSION["status_text"] = $status_text;
        $_SESSION["status_code"] = $status_code;
        location("../reg/settings.php");

    }

    if (isset($_POST['edit_bio_submit'])) {
        if (isset($_POST["client_bio"])) {

            $client_id = $_POST['client_id'];
            $client_bio = $_POST["client_bio"];

            $updateDesc = "UPDATE `tblclients` SET client_bio = '$client_bio' WHERE client_id = '$client_id'";
            $descResult = $db->query($updateDesc);

            $status = ($descResult ? "Success" : "Failed");
            $status_text = ($descResult ? "Bio updated successfully!" : "Error updating bio, please try again!");
            $status_code = ($descResult ? "success" : "error");
            $_SESSION["status"] = "Edit Bio " . $status;
            $_SESSION["status_text"] = $status_text;
            $_SESSION["status_code"] = $status_code;
            location("../reg/settings.php");
        }
    }

    if (isset($_POST['edit_email_submit'])) {
        if (isset($_POST["new_email"], $_POST["email_password"])) {

            $client_id = $_POST['client_id'];
            $new_email = $_POST["new_email"];
            $current_pass = $_POST["current_password"];
            $check_pass = $_POST["email_password"];

            if (!password_verify($check_pass, $current_pass)) {
                $_SESSION["status"] = "Edit Email Failed";
                $_SESSION["status_text"] = "Wrong password!";
                $_SESSION["status_code"] = "error";
                location("../reg/settings.php");
            }

            $newUserEmailText = "UPDATE `tblusers` SET email = '$new_email' WHERE user_id = '$client_id'";
            $newEmailResult = $db->query($newUserEmailText);

            $status = ($newEmailResult ? "Success" : "Failed");
            $status_text = ($newEmailResult ? "Email updated successfully!" : "Error updating email, please try again!");
            $status_code = ($newEmailResult ? "success" : "error");
            $_SESSION["status"] = "Edit Email " . $status;
            $_SESSION["status_text"] = $status_text;
            $_SESSION["status_code"] = $status_code;
            location("../reg/settings.php");
        }
    }

    if (isset($_POST['edit_pass_submit'])) {
        if (isset($_POST["old_password"], $_POST["new_password"], $_POST["confirm_password"])) {

            $client_id = $_POST['client_id'];
            $old_pass = $_POST["old_password"];
            $new_pass = $_POST["new_password"];
            $confirm_pass = $_POST["confirm_password"];
            $current_pass = $_POST["current_password"];

            if (!password_verify($old_pass, $current_pass)) {
                $_SESSION["status"] = "Edit Password Failed";
                $_SESSION["status_text"] = "Current password didnt match!";
                $_SESSION["status_code"] = "error";
                location("../reg/settings.php");
            }

            if ($new_pass !== $confirm_pass) {
                $_SESSION["status"] = "Edit Password Failed";
                $_SESSION["status_text"] = "New and Confirm Pass didnt match!";
                $_SESSION["status_code"] = "error";
                location("../reg/settings.php");
            }

            $new_hash_pass = password_hash($new_pass, PASSWORD_DEFAULT);

            $newUserPassText = "UPDATE `tblusers` SET password = '$new_hash_pass' WHERE user_id = $client_id";
            $newPassResult = $db->query($newUserPassText);

            $status = ($newPassResult ? "Success" : "Failed");
            $status_text = ($newPassResult ? "Password updated successfully!" : "Error updating password, please try again!");
            $status_code = ($newPassResult ? "success" : "error");
            $_SESSION["status"] = "Edit Password " . $status;
            $_SESSION["status_text"] = $status_text;
            $_SESSION["status_code"] = $status_code;
            location("../reg/settings.php");

        }
    }
}