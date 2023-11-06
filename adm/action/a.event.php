<?php

include '../../lib/config.php';
include '../../lib/database.php';
require '../../lib/email.php';

if (!isset($_SESSION)) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();

    $queue_id = $_POST['event_id'];
    $org_id = $_POST['client_id'];

    if (isset($_POST["event_accept"])) {

        $updateEvent = $db->query("UPDATE `tblevents` SET event_status = 'approved' AND is_approved = '1' WHERE event_id = '$queue_id' AND org_id = '$org_id'");

        // email

        $status = ($updateEvent) ? "success" : "error";
        $status_text = ($updateEvent) ? "Successfully posted the timeline!" : "Failed to post timeline";

        $_SESSION["status"] = "Post Timeline " . ucfirst($status);
        $_SESSION["status_text"] = $status_text;
        $_SESSION["status_code"] = $status;
        location("../adm/dashboard.php");
    }

    if (isset($_POST["event_deny"])) {
        
        $deleteImage = $db->query("DELETE FROM `tblimages` WHERE category = 'event_image' AND client_id = '$org_id'");
        $deleteEvent = $db->query("DELETE FROM `tblevents` WHERE event_id = '$queue_id' AND org_id = '$org_id'");

        // email

        $status = ($deleteEvent) ? "success" : "error";
        $status_text = ($updateEvent) ? "Successfully denied the timeline!" : "Failed to deny timeline";

        $_SESSION["status"] = "Post Timeline " . ucfirst($status);
        $_SESSION["status_text"] = $status_text;
        $_SESSION["status_code"] = $status;
        location("../adm/dashboard.php");
    }
}