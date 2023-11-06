<?php

session_start();

include '../../lib/database.php';

$db = new Database();
$conn = $db->connect();

$org_id = $_GET['oid'];
$event_id = $_GET['eid'];
$donor_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["monetary"])) {

        $payment = $_POST["monetary_payment"];
        // donor details
        $details = $_POST["monetary_details"];
        $amount = $_POST["monetary_amount"];

        $pay = $db->query("SELECT * FROM `tblpayments` WHERE payment_id = '$payment'");
        if ($pay) {
            $payr = mysqli_fetch_assoc($pay);
            $account = json_decode($payr['account_details'], true);
        }

        $timeline = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id' AND org_id = '$org_id'");
        if ($timeline->num_rows > 0) {
            $tline = mysqli_fetch_assoc($timeline);
            $get_current_funds = $tline['current_funds'];
            $target_funds = $tline['target_funds'];
        }

        $donor = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
        if ($donor) {
            $donors = mysqli_fetch_assoc($donor);
            $donor_name = $donors['donor_name'];
        }

        // check if the amount is equal to 40 below
        if ($amount >= 50) {
            
            // check if the current funds is less than to target_funds
            if ($get_current_funds < $target_funds) {
                $donation = $db->query(
                    "INSERT INTO `tbldonations` (
                        donor_id,
                        org_id,
                        event_id,
                        donation_type,
                        donation_amount,
                        donation_name,
                        donation_description,
                        donation_category,
                        donation_date,
                        status
                    ) VALUES (
                        '$donor_id',
                        '$org_id',
                        '$event_id',
                        'monetary',
                        '$amount',
                        NULL,
                        NULL,
                        NULL,
                        NOW(),
                        'approved'
                    )"
                );
                
                $new_current = (int)$get_current_funds + (int)$amount;
                $current_funds = $db->query("UPDATE `tblorgtimeline` SET current_funds = '$new_current' WHERE event_id = '$event_id' AND org_id = '$org_id'");

                if ($donation && $current_funds) {
                    // success
                    $_SESSION["status"] = "Donate Success";
                    $_SESSION["status_text"] = "Successfully donated! Thank you so much!";
                    $_SESSION["status_code"] = "success";
                    header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                    die();
                } else {
                    $_SESSION["status"] = "Donate Failed";
                    $_SESSION["status_text"] = "Failed to donate, please try again";
                    $_SESSION["status_code"] = "error";
                    header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                    die();
                }
            } else {
                $_SESSION["status"] = "Donate Failed";
                $_SESSION["status_text"] = "Thank you for your support but our Target Funds is now Reached. Please try on our other charity events!";
                $_SESSION["status_code"] = "warning";
                header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                die();
            }
        } else {
            // they put atleast 50+
            $_SESSION["status"] = "Donate Failed";
            $_SESSION["status_text"] = "Failed to donate, put atleast PHP 50+";
            $_SESSION["status_code"] = "error";
            header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
            die();
        }
    }

    if (isset($_POST["inkind"])) {

        $item_name = $_POST["inkind_name"];
        $item_category = $_POST["inkind_category"];
        $item_desc = $_POST["inkind_description"];
        $item_quantity = $_POST["inkind_quantity"];
        $deliver_date = $_POST["inkind_date"];

        $timeline = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id' AND org_id = '$org_id'");
        if ($timeline->num_rows > 0) {
            $tline = mysqli_fetch_assoc($timeline);
            $current_inkind = $tline['current_inkind'];
            $target_inkind = $tline['target_inkind'];
        }

        $donor = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
        if ($donor) {
            $donors = mysqli_fetch_assoc($donor);
        }

        if ($item_quantity >= 1) {

            if ($current_inkind < $target_inkind) {
                $donation = $db->query(
                    "INSERT INTO `tbldonations` (
                        donor_id,
                        org_id,
                        event_id,
                        donation_type,
                        donation_amount,
                        donation_name,
                        donation_description,
                        donation_category,
                        donation_date,
                        status
                    ) VALUES (
                        '$donor_id',
                        '$org_id',
                        '$event_id',
                        'inkind',
                        '$item_quantity',
                        '$item_name',
                        '$item_desc',
                        '$item_category',
                        '$deliver_date',
                        'pending'
                    )"
                );
                $donation_id = mysqli_insert_id($conn);

                if (isset($_FILES['inkind_images'])) {
                    $files = $_FILES["inkind_images"];
                    $checkimages = ["image/jpeg", "image/jpg", "image/png"];
        
                    $insertImage = $conn->prepare("INSERT INTO `tblimages` (table_id, permit_type, category, image_name, image_type, image_data) VALUES (?, ?, ?, ?, ?, ?)");
                    $insertImage->bind_param("isssss", $tableId, $permitType, $category, $imageName, $fileType, $imageData);
        
                    for ($i = 0; $i < count($files['name']); $i++) {
                        $tmpFilePath = $files["tmp_name"][$i];
                        $fileType = $files["type"][$i];
        
                        if (!empty($tmpFilePath) && in_array($fileType, $checkimages)) {
                            $tableId = $donation_id;
                            $permitType = 'inkind';
                            $category = 'donation_image';
                            $imageName = $files["name"][$i];
                            $imageData = base64_encode(file_get_contents(addslashes($tmpFilePath)));
        
                            $insertImage->execute();
                        }
                    }
        
                    $insertImage->close();
                }

                if ($donation) {
                    $_SESSION["status"] = "Donate Success";
                    $_SESSION["status_text"] = "Successfully posted, We will validate first your donation and email you if approved!";
                    $_SESSION["status_code"] = "success";
                    header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                    die();
                } else {
                    $_SESSION["status"] = "Donate Failed";
                    $_SESSION["status_text"] = "Failed to donate inkind, please try again!";
                    $_SESSION["status_code"] = "error";
                    header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                    die();
                }
            } else {
                $_SESSION["status"] = "Donate Failed";
                $_SESSION["status_text"] = "Thank you for your support but our Target Inkind is now Reached. Please try on our other charity events!";
                $_SESSION["status_code"] = "warning";
                header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                die();
            }
        } else {
            $_SESSION["status"] = "Donate Failed";
            $_SESSION["status_text"] = "The amount must be atleast 1+";
            $_SESSION["status_code"] = "error";
            header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
            die();
        }
    }

    if (isset($_POST["both"])) {

        // monetary
        $monetary_method = $_POST['mi_method'];
        $monetary_details = $_POST['mi_details'];
        $monetary_amount = $_POST['mi_amount'];
        // inkind
        $inkind_name = $_POST['mi_name'];
        $inkind_category = $_POST['mi_category'];
        $inkind_desc = $_POST['mi_desc'];
        $inkind_quantity = $_POST['mi_quantity'];
        $inkind_date = $_POST['mi_date'];

        $pay = $db->query("SELECT * FROM `tblpayments` WHERE payment_id = '$monetary_method'");
        if ($pay) {
            $payr = mysqli_fetch_assoc($pay);
            $account = json_decode($payr['account_details'], true);
        }

        $timeline = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id' AND org_id = '$org_id'");
        if ($timeline->num_rows > 0) {
            $tline = mysqli_fetch_assoc($timeline);
            $get_current_funds = $tline['current_funds'];
            $target_funds = $tline['target_funds'];
            $current_inkind = $tline['current_inkind'];
            $target_inkind = $tline['target_inkind'];
        }

        $donor = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
        if ($donor) {
            $donors = mysqli_fetch_assoc($donor);
        }

        if ($monetary_amount >= 50 && $inkind_quantity >= 1) {

            $canDonateFunds = false;
            $canDonateInkind = false;

            $is_donated_funds = false;
            $is_donated_inkind = false;

            if ($get_current_funds < $target_funds) {
                $canDonateFunds = true;
            }

            if ($current_inkind < $target_inkind) {
                $canDonateInkind = true;
            }

            if ($canDonateFunds) { 
                $monetary_donation = $db->query(
                    "INSERT INTO `tbldonations` (
                        donor_id,
                        org_id,
                        event_id,
                        donation_type,
                        donation_amount,
                        donation_name,
                        donation_description,
                        donation_category,
                        donation_date,
                        status
                    ) VALUES (
                        '$donor_id',
                        '$org_id',
                        '$event_id',
                        'monetary',
                        '$monetary_amount',
                        NULL,
                        NULL,
                        NULL,
                        NOW(),
                        'approved'
                    )"
                );

                $new_current = (int)$get_current_funds + (int)$monetary_amount;
                $current_funds = $db->query("UPDATE `tblorgtimeline` SET current_funds = '$new_current' WHERE event_id = '$event_id' AND org_id = '$org_id'");
                $is_donated_funds = true;
            }

            if ($canDonateInkind) {
                $inkind_donation = $db->query(
                    "INSERT INTO `tbldonations` (
                        donor_id,
                        org_id,
                        event_id,
                        donation_type,
                        donation_amount,
                        donation_name,
                        donation_description,
                        donation_category,
                        donation_date,
                        status
                    ) VALUES (
                        '$donor_id',
                        '$org_id',
                        '$event_id',
                        'inkind',
                        '$inkind_quantity',
                        '$inkind_name',
                        '$inkind_desc',
                        '$inkind_category',
                        '$inkind_date',
                        'pending'
                    )"
                );

                $inkind_donation_id = mysqli_insert_id($conn);

                if (isset($_FILES['mi_images'])) {
                    $files = $_FILES["mi_images"];
                    $checkimages = ["image/jpeg", "image/jpg", "image/png"];
        
                    $insertImage = $conn->prepare("INSERT INTO `tblimages` (table_id, permit_type, category, image_name, image_type, image_data) VALUES (?, ?, ?, ?, ?, ?)");
                    $insertImage->bind_param("isssss", $tableId, $permitType, $category, $imageName, $fileType, $imageData);
        
                    for ($i = 0; $i < count($files['name']); $i++) {
                        $tmpFilePath = $files["tmp_name"][$i];
                        $fileType = $files["type"][$i];
        
                        if (!empty($tmpFilePath) && in_array($fileType, $checkimages)) {
                            $tableId = $inkind_donation_id;
                            $permitType = 'inkind';
                            $category = 'donation_image';
                            $imageName = $files["name"][$i];
                            $imageData = base64_encode(file_get_contents(addslashes($tmpFilePath)));
        
                            $insertImage->execute();
                        }
                    }
        
                    $insertImage->close();
                }

                $is_donated_inkind = true;
            }

            if ($is_donated_funds && $is_donated_inkind) {
                $_SESSION["status"] = "Donate Success";
                $_SESSION["status_text"] = "Successfully donated monetary, Thank you so much and we will validate first your inkind donation and email you if approved!";
                $_SESSION["status_code"] = "success";
                header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                die();
            } elseif ($is_donated_funds) {
                $_SESSION["status"] = "Donate Success";
                $_SESSION["status_text"] = "Sucessfully donated monetary. Thank you so much and for your inkind donation, Target Inkind has been reached, please try on other charity events!";
                $_SESSION["status_code"] = "success";
                header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                die();
            } elseif ($is_donated_inkind) {
                $_SESSION["status"] = "Donate Success";
                $_SESSION["status_text"] = "Sucessfully donated inkind. Thank you so much and for your monetary donation, Target Funds has been reached, please try on other charity events!";
                $_SESSION["status_code"] = "success";
                header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                die();
            } else {
                $_SESSION["status"] = "Donate Failed";
                $_SESSION["status_text"] = "Failed to donate, please try again!";
                $_SESSION["status_code"] = "error";
                header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
                die();
            }

        } else {
            $_SESSION["status"] = "Donate Failed";
            $_SESSION["status_text"] = "The monetary amount must be atleast 50+ and the inkind quantity must be atleast 1+";
            $_SESSION["status_code"] = "error";
            header('Location: ../dnr/contribute.php?oid=' . $org_id . '&eid=' . $event_id);
            die();
        }
    }
}
?>