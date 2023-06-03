<?php

session_start();

include '../../lib/database.php';

$db = new Database();
$conn = $db->connect();

$user = $_SESSION['user'];
$id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $queue_id = $_POST["queue_id"];

  if (isset($_POST["acceptTimeline"])) {

    // GETTING THE QUEUE TIMELINE
    $getQueue = "SELECT * FROM `tblorgqueuetimeline` WHERE queue_id = '$queue_id'";
    $getR = mysqli_query($conn, $getQueue);
    if ($getR->num_rows > 0) {
      $queue = mysqli_fetch_assoc($getR);
    }

    // IF EVENT_ID IS NULL (INSERT ONE)
    if ($queue["event_id"] === null) {
      $event_id = $queue_id;
      $org_id = $queue['org_id'];
      $event_title = $queue['queue_title'];
      $event_type = $queue['queue_type'];
      $event_desc = $queue['queue_description'];
      $event_start_date = $queue['queue_start_date'];
      $event_end_date = $queue['queue_end_date'];
      $current_inkind = $queue['current_inkind'];
      $target_inkind = $queue['target_inkind'];
      $current_funds = $queue['current_funds'];
      $target_funds = $queue['target_funds'];
      $timestamp = $queue['timestamp'];
      $status = "ongoing";

      $insertOrgTimeline = "INSERT INTO `tblorgtimeline` (
        event_id,
        org_id,
        event_title,
        event_type,
        event_description,
        event_start_date,
        event_end_date,
        current_inkind,
        target_inkind,
        current_funds,
        target_funds,
        timestamp,
        status
      ) VALUES (
        '$event_id',
        '$org_id',
        '$event_title',
        '$event_type',
        '$event_desc',
        '$event_start_date',
        '$event_end_date',
        '$current_inkind',
        '$target_inkind',
        '$current_funds',
        '$target_funds',
        '$timestamp',
        '$status'
      )";
      $insertR = mysqli_query($conn, $insertOrgTimeline);

      // GETTING THE QUEUE IMAGES AND UPDATE TO EVENT_IMAGE
      $imageEventQuery = mysqli_query($conn, "SELECT * FROM `tblimages` WHERE table_id = '$event_id' AND permit_type = '$event_type' AND category = 'queue_image'");
      if (mysqli_num_rows($imageEventQuery) > 0) {
        while ($row = mysqli_fetch_assoc($imageEventQuery)) {
            $up = mysqli_query($conn, "UPDATE `tblimages` SET category = 'event_image' WHERE table_id = '$event_id'");
        }
      }

      // DELETING THE QUEUE
      $deleteQueueQuery = "DELETE FROM `tblorgqueuetimeline` WHERE queue_id = '$queue_id'";
      $deleteQueueResult = mysqli_query($conn, $deleteQueueQuery);

      if ($insertR && $deleteQueueResult) {
        // todo insert email to charity and everyone who registered
        $_SESSION["status"] = "Post Timeline Success";
        $_SESSION["status_text"] = "Successfully posted the timeline!";
        $_SESSION["status_code"] = "success";
        header('Location: ../../p/adm/dashboard.php');
        die();
      } else {
        $_SESSION["status"] = "Post Timeline Failed";
        $_SESSION["status_text"] = "Failed to post timeline";
        $_SESSION["status_code"] = "error";
        header('Location: ../../p/adm/dashboard.php');
        die();
      }
    } else {
      // UPDATING THE ORG TIMELINE INSTEAD OF ADDING NEW
      $event_id = $queue["event_id"];
      $org_id = $queue['org_id'];
      $event_title = $queue['queue_title'];
      $event_type = $queue['queue_type'];
      $event_desc = $queue['queue_description'];
      $event_start_date = $queue['queue_start_date'];
      $event_end_date = $queue['queue_end_date'];
      $current_inkind = $queue['current_inkind'];
      $target_inkind = $queue['target_inkind'];
      $current_funds = $queue['current_funds'];
      $target_funds = $queue['target_funds'];
      $timestamp = $queue['timestamp'];
      $status = "ongoing";

      $updateQuery = "UPDATE `tblorgtimeline` SET 
        event_title = '$event_title',
        event_type = '$event_type',
        event_description = '$event_desc',
        event_start_date = '$event_start_date',
        event_end_date = '$event_end_date',
        current_inkind = '$current_inkind',
        target_inkind = '$target_inkind',
        current_funds = '$current_funds',
        target_funds = '$target_funds',
        status = '$status' WHERE event_id = '$event_id'  
      ";

      $updateResult = mysqli_query($conn, $updateQuery);

      // GETTING THE QUEUE IMAGE TO UPDATE EVENT IMAGE
      $imageEventQuery = mysqli_query($conn, "SELECT * FROM `tblimages` WHERE table_id = '$event_id' AND permit_type = '$event_type' AND category = 'queue_image'");
      if (mysqli_num_rows($imageEventQuery) > 0) {
        while ($row = mysqli_fetch_assoc($imageEventQuery)) {
            $up = mysqli_query($conn, "UPDATE `tblimages` SET category = 'event_image' WHERE table_id = '$event_id'");
        }
      }

      // DELETE QUEUE TIMELINE
      $deleteQueueQuery = "DELETE FROM `tblorgqueuetimeline` WHERE queue_id = '$queue_id'";
      $deleteQueueResult = mysqli_query($conn, $deleteQueueQuery);

      if ($updateResult && $deleteQueueResult) {
        // todo insert email to charity and everyone who registered
        $_SESSION["status"] = "Post Timeline Success";
        $_SESSION["status_text"] = "Successfully posted the timeline!";
        $_SESSION["status_code"] = "success";
        header('Location: ../../p/adm/dashboard.php');
        die();
      } else {
        $_SESSION["status"] = "Post Timeline Failed";
        $_SESSION["status_text"] = "Failed to post timeline";
        $_SESSION["status_code"] = "error";
        header('Location: ../../p/adm/dashboard.php');
        die();
      }

    }
  }

  if (isset($_POST["denyTimeline"])) {

    // GETTING THE QUEUE
    $getQueue = "SELECT * FROM `tblorgqueuetimeline` WHERE queue_id = '$queue_id'";
    $getR = mysqli_query($conn, $getQueue);

    if ($getR->num_rows > 0) {
      $queue = mysqli_fetch_assoc($getR);
    }
    $queue_type = $queue['queue_type'];
    $event_id = $queue['event_id'];

    // UPDATE THE STATUS IF EVENT_ID IS NOT NULL
    if ($event_id !== NULL) {
      $updateEID = mysqli_query($conn, "UPDATE `tblorgtimeline` SET status = 'ongoing' WHERE event_id = '$event_id'");
    }

    // DELETING THE QUEUE IMAGE
    $imageEventQuery = mysqli_query($conn, "SELECT * FROM `tblimages` WHERE table_id = '$queue_id' AND permit_type = '$queue_type' AND category = 'queue_image'");
    if (mysqli_num_rows($imageEventQuery) > 0) {
      while ($row = mysqli_fetch_assoc($imageEventQuery)) {
          $image_id = $row['image_id'];
          $deleteID = mysqli_query($conn, "DELETE FROM `tblimages` WHERE image_id = '$image_id'");
      }
    }

    // DELETING THE QUEUE TIMELINE
    $deleteQueueQuery = "DELETE FROM `tblorgqueuetimeline` WHERE queue_id = '$queue_id'";
    $deleteQueueResult = mysqli_query($conn, $deleteQueueQuery);

    // todo insert email to charity
    // OPTIONAL
    // $updateQueue = "UPDATE `tblorgqueuetimeline` SET queue_status = 'denied' WHERE queue_id = '$queue_id'";
    // $updateR = mysqli_query($conn, $updateQueue);

    if ($deleteQueueQuery) {
      $_SESSION["status"] = "Post Timeline Success";
      $_SESSION["status_text"] = "Successfully denied the timeline!";
      $_SESSION["status_code"] = "success";
      header('Location: ../../p/adm/dashboard.php');
      die();
    } else {
      $_SESSION["status"] = "Post Timeline Failed";
      $_SESSION["status_text"] = "Failed to post timeline";
      $_SESSION["status_code"] = "error";
      header('Location: ../../p/adm/dashboard.php');
      die();
    }
  }

  // VALIDATION OF TWO REGISTRATIONS
  if (isset($_POST["donorValidYes"])) {
    
  }

  if (isset($_POST["donorValidNo"])) {

  }

  if (isset($_POST["orgValidYes"])) {

  }

  if (isset($_POST["orgValidNo"])) {

  }

}
?>