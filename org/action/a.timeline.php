<?php

include '../../lib/config.php';
include '../../lib/database.php';
require '../../lib/email.php';

if (!isset($_SESSION)) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();

    // not tested
    if (isset($_POST["post_timeline"])) {

        //$org_id = $_POST["org_id"];
        $org_id = $_SESSION["id"];
        $event_type = $_POST["event_type"];
        $event_title = $_POST["event_title"];
        $event_desc = $_POST["event_description"];

        $event_start_date = isset($_POST["event_start_date"]) ? $_POST["event_start_date"] : NULL;
        $event_end_date = isset($_POST["event_end_date"]) ? $_POST["event_end_date"] : NULL;
        $target_inkind = isset($_POST["target_inkind"]) ? $_POST["targer_inkind"] : '0';
        $target_funds = isset($_POST["target_monetary"]) ? $_POST["target_monetary"] : '0';

        $post_event = "INSERT INTO `tblevents` (
            org_id,
            event_title,
            event_type,
            event_description,
            event_start_date,
            event_end_date,
            event_status,
            post_date,
            is_approved
        ) VALUES (
            '$org_id',
            '$event_title',
            '$event_type',
            '$event_desc',
            '$event_start_date',
            '$event_end_date',
            'pending',
            NOW(),
            '0'
        )";

        $eventResult = $db->query($post_event);
        $event_id = $db->lastInsertId();

        if ($target_inkind !== '0' || $target_funds !== '0') {
            $collection = "INSERT INTO `tblcollections` (
                event_id,
                current_inkind,
                target_inkind,
                current_funds,
                target_funds
            ) VALUES (
                '$event_id',
                '0',
                '$target_inkind',
                '0',
                '$target_funds'
            )";
            $collectResult = $db->query($collection);
        }

        if (isset($_FILES['event_images'])) {
            if (!empty($_FILES['event_images'])) {
                $files = $_FILES['event_images'];
                $totalFiles = count($files['name']);

                $category = "event_image";

                for ($i = 0; $i < $totalFiles; $i++) {
                    if (!empty($files["tmp_name"][$i])) {
                        $image_name = $files["name"][$i];
                        $image_data = base64_encode(file_get_contents(addslashes($files["tmp_name"][$i])));
                        $client_id = $org_id;

                        $images = $db->query("INSERT INTO `tblimages` (
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
                            '$event_id',
                            NULL
                        )");
                    }
                }
            }
        }

        $status = ($eventResult) ? "success" : "error";
        $status_text = ($eventResult) ? "Successfully post event, we will email you once the event is published!" : "Failed to post event!";
        $_SESSION["status"] = "Post Timeline " . $status;
        $_SESSION["status_text"] = $status_text;
        $_SESSION["status_code"] = $status;
        location("../dashboard.php");
    }

    if (isset($_POST["post_status"])) {
        $edit_org_id = $_POST["org_id"];
        $post_status = $_POST["post_status"];

        if ($post_status == "edit") {
            $event_id = $_POST["event_id"];
            $event_type = $_POST["event_type"];
            $event_title = $_POST["event_title"];
            $event_desc = $_POST["event_description"];

            $event_start_date = isset($_POST["event_start_date"]) ? $_POST["event_start_date"] : NULL;
            $event_end_date = isset($_POST["event_end_date"]) ? $_POST["event_end_date"] : NULL;
            $current_inkind = isset($_POST["current_inkind"]) ? $_POST["current_inkind"] : '0';
            $target_inkind = isset($_POST["target_inkind"]) ? $_POST["target_inkind"] : '0';
            $current_funds = isset($_POST["current_funds"]) ? $_POST["current_funds"] : '0';
            $target_funds = isset($_POST["target_funds"]) ? $_POST["target_funds"] : '0';

            if (isset($_FILES['event_images'])) {
                if (!empty($_FILES['event_images'])) {
                    $files = $_FILES['event_images'];
                    $totalFiles = count($files['name']);
    
                    $category = "event_image";
    
                    for ($i = 0; $i < $totalFiles; $i++) {
                        if (!empty($files["tmp_name"][$i])) {
                            $image_name = $files["name"][$i];
                            $image_data = base64_encode(file_get_contents(addslashes($files["tmp_name"][$i])));
                            $client_id = $edit_org_id;
    
                            $images = $db->query("INSERT INTO `tblimages` (
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
                                '$event_id',
                                NULL
                            )");
                        }
                    }
                }
            }

            $update_event = "UPDATE `tblevents` SET 
                event_title = '$event_title',
                event_description = '$event_desc',
                event_start_date = '$event_start_date',
                event_end_date = '$event_end_date'
                WHERE event_id = '$event_id' AND org_id = '$edit_org_id'";
            
            $updateResult = $db->query($update_event);

            if ($target_inkind !== '0' || $target_funds !== '0') {
                $update_collect = "UPDATE `tblcollections` SET 
                    target_inkind = '$target_inkind',
                    target_funds = '$target_funds'
                    WHERE event_id = '$event_id'";

                $collectResult = $db->query($update_collect);
            }

            if ($updateResult) {
                $response = ['success' => true];
                echo json_encode($response);
                exit;
            } else {
                $response = [
                    'success' => false,
                    'messsage' => 'Failed to edit a timeline event'
                ];
                echo json_encode($response);
                exit;
            }

        } else if ($post_event == "delete") {
            $event_id = $_POST["event_id"];
            $event_type = $_POST["event_type"];

            $delete_images = $db->query("DELETE FROM `tblimages` WHERE event_id = '$event_id' AND category = 'event_image'");
            $delete_event = $db->query("DELETE FROM `tblevents` WHERE event_id = '$event_id'");

            $delete_sub_images = $db->query("DELETE FROM `tblimages` WHERE event_id = '$event_id' AND category = 'sub_event_image'");
            $delete_sub_event = $db->query("DELETE FROM `tblsubevents` WHERE event_id = '$event_id'");

            if ($delete_event) {
                $response = ['success' => true];
                echo json_encode($response);
                exit;
            } else {
                $response = [
                    'success' => false,
                    'messsage' => 'Failed to delete a timeline event'
                ];
                echo json_encode($response);
                exit;
            }

        } else if ($post_event == "add_sub_event") {

        } else if ($post_event == "del_sub_event") {

        } else {
            $response = [
                'success' => false,
                'message' => 'error'
            ];
            echo json_encode($response);
            exit;
        }
    }

}
