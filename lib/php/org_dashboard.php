<?php

session_start();

include '../database.php';

$db = new Database();
$conn = $db->connect();

$org_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // EDIT DESCRIPTION
    if (isset($_POST["editDescSubmit"])) {
        if (isset($_POST["orgDesc"])) {
          $orgDesc = $_POST["orgDesc"];

          $updateDesc = "UPDATE `tblorgs` SET org_description = '$orgDesc' WHERE org_id = '$org_id'";
          $descResult = mysqli_query($conn, $updateDesc);

            if ($descResult) {
                $_SESSION["status"] = "Edit Description Success";
                $_SESSION["status_text"] = "Description updated successfully!";
                $_SESSION["status_code"] = "success";
                header("Location: ../../p/org/dashboard.php");
                die();
            } else {
                $_SESSION["status"] = "Edit Description Failed";
                $_SESSION["status_text"] = "Error updating description: " . mysqli_error($conn);
                $_SESSION["status_code"] = "error";
                header("Location: ../../p/org/dashboard.php");
                die();
            }
        }
    }

    // EDIT PROFILE
    if (isset($_POST["editProfileSubmit"])) {
        //if (isset($_POST["orgName"], $_POST["orgContactName"], $_POST["orgAddress"], $_POST["orgFoundingDate"], $_POST["orgPhone"])) {
          $orgName = $_POST["orgName"];
          $orgContactName = $_POST["orgContactName"];
          $orgAddress = $_POST["orgAddress"];
          $orgDateFounded = $_POST["orgFoundingDate"];
          $orgPhone = $_POST["orgPhone"];
  
          // org_name, org_contact_name, org_address, org_type, org_phone, org_icon
          $newProfileText = "UPDATE `tblorgs` SET 
            org_name = '$orgName', 
            org_person_name = '$orgContactName',
            org_address = '$orgAddress',
            org_phone = '$orgPhone',
            date_founded = '$orgDateFounded' WHERE org_id = '$org_id'";
  
          $newProfileResult = mysqli_query($conn, $newProfileText);
  
          if ($newProfileResult) {
            $_SESSION["status"] = "Edit Profile Success";
            $_SESSION["status_text"] = "Profile updated successfully!";
            $_SESSION["status_code"] = "success";
            header("Location: ../../p/org/dashboard.php");
            die();
          } else {
            $_SESSION["status"] = "Edit Profile Failed";
            $_SESSION["status_text"] = "Error updating profile: " . mysqli_error($conn);
            $_SESSION["status_code"] = "success";
            header("Location: ../../p/org/dashboard.php");
            die();
          }
        //}
    }

    // EDIT EMAIL ADDRESS
    if (isset($_POST["editEmailSubmit"])) {
        if (!empty($_POST["userEmail"]) || !empty($_POST["userPass"])) {

            $currentEmail = $_POST["userEmail"];
            $currentPass = $user["password"];
            $checkPass = $_POST["userPass"];

            if ($currentPass !== $checkPass) {
                $_SESSION["status"] = "Edit Email Failed";
                $_SESSION["status_text"] = "Wrong password!";
                $_SESSION["status_code"] = "error";
                header("Location: ../../p/org/dashboard.php");
                die();
            }

            $newUserEmailText = "UPDATE `tblusers` SET email = '$currentEmail' WHERE user_id = $org_id";
            $newEmailResult = mysqli_query($conn, $newUserEmailText);

            if ($newEmailResult) {
                $_SESSION["status"] = "Edit Email Success";
                $_SESSION["status_text"] = "Email updated successfully!";
                $_SESSION["status_code"] = "success";
                header("Location: ../../p/org/dashboard.php");
                die();
            } else {
                $_SESSION["status"] = "Edit Email Failed";
                $_SESSION["status_text"] = "Error updating email: " . mysqli_error($conn);
                $_SESSION["status_code"] = "success";
                header("Location: ../../p/org/dashboard.php");
                die();
            }
        } else {
            $_SESSION["status"] = "Edit Email Failed";
            $_SESSION["status_text"] = "Input box must not be empty";
            $_SESSION["status_code"] = "error";
            header("Location: ../../p/org/dashboard.php");
            die();
        }
    }
    
    // EDIT PASSWORD
    if (isset($_POST["editPassSubmit"])) {
        if (!empty($_POST["orgOldPass"]) || !empty($_POST["orgNewPass"] || !empty($_POST["orgConfirmPass"]))) {
            
            $currentPass = $user["password"];
            $oldPass = $_POST["orgOldPass"];
            $newPass = $_POST["orgNewPass"];
            $confirmPass = $_POST["orgConfirmPass"];

            if ($currentPass !== $oldPass) {
                $_SESSION["status"] = "Edit Password Failed";
                $_SESSION["status_text"] = "Current password didnt match";
                $_SESSION["status_code"] = "error";
                header("Location: ../../p/org/dashboard.php");
                die();
            }

            if ($newPass !== $confirmPass) {
                $_SESSION["status"] = "Edit Password Failed";
                $_SESSION["status_text"] = "New and Confirm Pass didnt match!";
                $_SESSION["status_code"] = "error";
                header("Location: ../../p/org/dashboard.php");
                die();
            }
            
            $newUserPassText = "UPDATE `tblusers` SET password = '$newPass' WHERE user_id = $org_id";
            $newPassResult = mysqli_query($conn, $newUserPassText);

            if ($newPassResult) {
                $_SESSION["status"] = "Edit Password Success";
                $_SESSION["status_text"] = "Password updated successfully!";
                $_SESSION["status_code"] = "success";
                header('Location: ../../p/org/dashboard.php');
                die();
            } else {
                $_SESSION["status"] = "Edit Password Failed";
                $_SESSION["status_text"] = "Error updating password: " . mysqli_error($conn);
                $_SESSION["status_code"] = "error";
                header("Location: ../../p/org/dashboard.php");
                die();
            }
        } else {
            $_SESSION["status"] = "Edit Password Failed";
            $_SESSION["status_text"] = "Input box must not be empty";
            $_SESSION["status_code"] = "error";
            header("Location: ../../p/org/dashboard.php");
            die();
        }
    }

    // ADD PAYMENT METHOD
    if (isset($_POST["addPaymentSubmit"])) {
        if (!empty($_POST["addName"]) || !empty($_POST["addDetails"])) {
            $selectedPayment = $_POST["addPayment"];

            $name = $_POST["addName"];

            $details = $_POST["addDetails"];

            $accountDetails = [
                "account_name" => $name,
                "account_value" => $details
            ];
            
            $accountDetailsJSON = json_encode($accountDetails);

            $query = "INSERT INTO `tblpayments` (event_id, org_id, method_type, account_details) VALUES (NULL, '$org_id', '$selectedPayment', '$accountDetailsJSON')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $_SESSION["status"] = "Payment Success";
                $_SESSION["status_text"] = "Successfully added Payment Donation Method";
                $_SESSION["status_code"] = "success";
                header('Location: ../../p/org/dashboard.php');
                die();
            } else {
                $_SESSION["status"] = "Payment Failed";
                $_SESSION["status_text"] = "Failed to add Payment Donation Method";
                $_SESSION["status_code"] = "error";
                header('Location: ../../p/org/dashboard.php');
                die();
            }
        } else {
            $_SESSION["status"] = "Payment Failed";
            $_SESSION["status_text"] = "Failed to add Payment Donation Method";
            $_SESSION["status_code"] = "error";
            header('Location: ../../p/org/dashboard.php');
            die();
        }
    }

    // DELETE PAYMENT METHOD
    if (isset($_POST["delPaymentMethod"])) {
        $payment_id = $_POST['delPaymentMethod'];

        $payQ = "SELECT * FROM `tblpayments` WHERE payment_id = '$payment_id'";
        $payR = mysqli_query($conn, $payQ);

        if ($payR->num_rows > 0) {
            $payment = mysqli_fetch_assoc($payR);
        }

        $event_id = $payment["event_id"];

        if ($event_id == NULL) {
            $payD = "DELETE FROM `tblpayments` WHERE payment_id = '$payment_id'";
            if (mysqli_query($conn, $payD)) {
                $response = array('success' => true);
                echo json_encode($response);
                header('Location: ../../p/org/dashboard.php');
                die();
            } else {
                $response = array('success' => false, 'message' => 'Error occurred during deletion.');
                echo json_encode($response);
                exit;
            }
        } else {
            $response = array('success' => false, 'message' => 'Deletion not allowed for payments with connected event. Edit the event post if you want to delete it');
            echo json_encode($response);
            exit;
        }
    }

    // POST TIMELINE
    if (isset($_POST["postTimelineSubmit"])) {

        if (isset($_POST["timelineType"])) {

            $check = true;

            $post_type = $_POST["timelineType"];
            $title = $_POST["timelineTitle"];
            $description = $_POST["timelineDesc"];

            $queueStartDate = isset($_POST['timelineStartDate']) ? $_POST['timelineStartDate'] : NULL;
            $queueEndDate = isset($_POST['timelineEndDate']) ? $_POST['timelineEndDate'] : NULL;
            $targetInkind = isset($_POST['targetInkind']) ? $_POST['targetInkind'] : '0';
            $targetFunds = isset($_POST['targetMonetary']) ? $_POST['targetMonetary'] : '0';

            $eventQuery = "INSERT INTO `tblorgqueuetimeline` 
                (
                    event_id, 
                    org_id, 
                    queue_title, 
                    queue_type, 
                    queue_description,
                    queue_start_date,
                    queue_end_date,
                    current_inkind,
                    target_inkind,
                    current_funds,
                    target_funds,
                    timestamp,
                    queue_status
                ) VALUES (
                    NULL,
                    '$org_id',
                    '$title',
                    '$post_type',
                    '$description',
                    '$queueStartDate',
                    '$queueEndDate',
                    '0',
                    '$targetInkind',
                    '0',
                    '$targetFunds',
                    NOW(),
                    'pending'
                )";

            $eventResult = mysqli_query($conn, $eventQuery);
            $queueId = mysqli_insert_id($conn);

            if (isset($_POST["gcash"])) {
                $gcash = $_POST['gcash'];
                $addGcash = "UPDATE `tblpayments` SET event_id = '$queueId' WHERE payment_id = '$gcash'";
                $gcashR = mysqli_query($conn, $addGcash);
            }

            if (isset($_POST['maya'])) {
                $maya = $_POST['maya'];
                $addMaya = "UPDATE `tblpayments` SET event_id = '$queueId' WHERE payment_id = '$maya'";
                $mayaR = mysqli_query($conn, $addMaya);
            }

            if (isset($_POST['paypal'])) {
                $paypal = $_POST['paypal'];
                $addPaypal = "UPDATE `tblpayments` SET event_id = '$queueId' WHERE payment_id = '$paypal'";
                $paypalR = mysqli_query($conn, $addPaypal);
            }


            if (isset($_FILES['timelineImages'])) {
                if (!empty($_FILES['timelineImages'])) {
                    $files = $_FILES['timelineImages'];

                    $checkimages = ["image/jpeg", "image/jpg", "image/png"];
                    for ($i = 0; $i < count($files['name']); $i++) {
                        $imageTmpName = $files["tmp_name"][$i];
                        if (!empty($imageTmpName)) {
                            $tableId = $queueId;
                            $permitType = $post_type;
                            $category = "queue_image";
                            $imageName = $files["name"][$i];
                            $imageType = $files["type"][$i];
                            $imageData = base64_encode(file_get_contents(addslashes($imageTmpName)));

                            if (!in_array($imageType, $checkimages)) {
                                $check = false;
                                $_SESSION["status"] = "Post Timeline Failed";
                                $_SESSION["status_text"] = "Failed to post timeline, images must be jpeg, jpg, png";
                                $_SESSION["status_code"] = "error";
                                header('Location: ../../p/org/dashboard.php');
                                die();
                            }
            
                            $insertImage = "INSERT INTO `tblimages` (table_id, permit_type, category, image_name, image_type, image_data)
                                            VALUES ('$tableId', '$permitType', '$category', '$imageName', '$imageType', '$imageData')";

                            $insertResult = mysqli_query($conn, $insertImage);
                        }
                    }
                } 
            }
  
            if ($eventResult && $check == true) {
                $_SESSION["status"] = "Post Timeline Success";
                $_SESSION["status_text"] = "Successfully post timeline, We will email you once the timeline is published!";
                $_SESSION["status_code"] = "success";
                header('Location: ../../p/org/dashboard.php');
                die();
            } else {
                $_SESSION["status"] = "Post Timeline Failed";
                $_SESSION["status_text"] = "Failed to post timeline";
                $_SESSION["status_code"] = "error";
                header('Location: ../../p/org/dashboard.php');
                die();
            }

        }
    }

    // SAVE TIMELINE

    if (isset($_POST["tline_status"])) {

        $edit_org_id = $_POST["edit_org_id"];
        $tline = $_POST["tline_status"];

        if ($tline === "edit") {
            $eventID = $_POST["edit_event_id"];
            $editEventType = $_POST["editEventType"];
            $editEventTitle = $_POST["editEventTitle"];
            $editEventDesc = $_POST["editEventDesc"];
            $editEventStartDate = $_POST["editEventStartDate"];
            $editEventEndDate = $_POST["editEventEndDate"];
            $editCurrentInkind = $_POST["editCurrentInkind"];
            $editTargetInkind = $_POST["editTargetInkind"];
            $editCurrentFunds = $_POST["editCurrentFunds"];
            $editTargetFunds = $_POST["editTargetFunds"];

            if (isset($_FILES['editImages'])) {
                if (!empty($_FILES['editImages'])) {
                    $files = $_FILES["editImages"];
                    $totalFiles = count($files['name']);
                
                    $checkimages = ["image/jpeg", "image/jpg", "image/png"];
                    for ($i = 0; $i < $totalFiles; $i++) {
                        if (!empty($files["tmp_name"][$i])) {
                            $tableId = $eventID;
                            $permitType = $editEventType;
                            $category = "queue_image";
                            $imageName = $files["name"][$i];
                            $imageType = $files["type"][$i];
                            $imageData = base64_encode(file_get_contents(addslashes($files["tmp_name"][$i])));

                            if (!in_array($imageType, $checkimages)) {
                                $response = [
                                    "success" => false,
                                    "message" => "images must be jpeg, jpg, png"
                                ];
                                echo json_encode($response);
                                exit;
                            }

                            $insertImage = "INSERT INTO `tblimages` (table_id, permit_type, category, image_name, image_type, image_data)
                                            VALUES ('$tableId', '$permitType', '$category', '$imageName', '$imageType', '$imageData')";

                            $insertResult = mysqli_query($conn, $insertImage);
                        }
                    }
                }
            }

            $eventQuery = "INSERT INTO `tblorgqueuetimeline` 
            (
                event_id, 
                org_id, 
                queue_title, 
                queue_type, 
                queue_description,
                queue_start_date,
                queue_end_date,
                current_inkind,
                target_inkind,
                current_funds,
                target_funds,
                timestamp,
                queue_status
            ) VALUES (
                '$eventID',
                '$edit_org_id',
                '$editEventTitle',
                '$editEventType',
                '$editEventDesc',
                '$editEventStartDate',
                '$editEventEndDate',
                '$editCurrentInkind',
                '$editTargetInkind',
                '$editCurrentFunds',
                '$editTargetFunds',
                NOW(),
                'pending'
            )";

            $eventResult = mysqli_query($conn, $eventQuery);
            $queueId = mysqli_insert_id($conn);

            $editQuery = mysqli_query($conn, "UPDATE `tblorgtimeline` SET status = 'pending' WHERE event_id = '$eventID'");
    
            if ($eventResult) {
                $response = array(
                    'success' => true, 
                );
                echo json_encode($response);
                exit;
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to queue edit to administrator'
                ];
                echo json_encode($response);
                exit;
            }
        } else if ($tline === "delete") {
            $eventID = $_POST["edit_event_id"];
            $editEventType = $_POST["editEventType"];

            $imageEventQuery = mysqli_query($conn, "SELECT * FROM `tblimages` WHERE table_id = '$eventID' AND permit_type = '$editEventType' AND category = 'event_image'");

            $deleteEventQuery = mysqli_query($conn, "DELETE FROM `tblorgtimeline` WHERE event_id = '$eventID'");

            if (mysqli_num_rows($imageEventQuery) > 0) {
                while ($row = mysqli_fetch_assoc($imageEventQuery)) {
                    $image_id = $row['image_id'];
                    $deleteID = mysqli_query($conn, "DELETE FROM `tblimages` WHERE image_id = '$image_id'");
                }
            }

            if ($deleteEventQuery) {
                $response = array(
                    'success' => true, 
                );
                echo json_encode($response);
                exit;
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to delete the timeline post'
                ];
                echo json_encode($response);
                exit;
            }
            
        } else {
            $response = [
                'success' => false,
                'message' => 'Error'
            ];
            echo json_encode($response);
            exit;
        }
    }
}
?>