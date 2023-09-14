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
      $status = "approved";

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
    // get the id of donor
    $donor_id = $_POST["valid_donor_id"];
    // set the is_approved to 1
    $up = $db->query("UPDATE `tbldonors` SET is_approved = '1', date_approved = NOW() WHERE donor_id = '$donor_id'");
    
    if ($up) {
      // todo email
      $_SESSION["status"] = "Donor Validation Success";
      $_SESSION["status_text"] = "Successfully validated the donor account!";
      $_SESSION["status_code"] = "success";
      header('Location: ../../p/adm/dashboard.php');
      die();
    } else {
      $_SESSION["status"] = "Donor Validation Failed";
      $_SESSION["status_text"] = "Failed to validate the donor!";
      $_SESSION["status_code"] = "error";
      header('Location: ../../p/adm/dashboard.php');
      die();
    }

  }

  if (isset($_POST["donorValidNo"])) {
    // get the id of donor
    $donor_id = $_POST["valid_donor_id"];
    // delete the donor
    // delete the images connected to donor
    $donor_delete = $db->query("DELETE FROM `tbldonors` WHERE donor_id = '$donor_id'");
    $user_delete = $db->query("DELETE FROM `tblusers` WHERE user_id = '$donor_id'");
    //$image_delete = $db->query("DELETE FROM `tblimages` WHERE table_id = '$donor_id' AND category = 'donor_permit' AND permit_type = 'valid_ids'");

    $imageEventQuery = mysqli_query($conn, "SELECT * FROM `tblimages` WHERE table_id = '$donor_id' AND permit_type = 'valid_ids' AND category = 'donor_permit'");
    if (mysqli_num_rows($imageEventQuery) > 0) {
      while ($row = mysqli_fetch_assoc($imageEventQuery)) {
          $image_id = $row['image_id'];
          $deleteID = mysqli_query($conn, "DELETE FROM `tblimages` WHERE image_id = '$image_id'");
      }
    }

    if ($user_delete) {
      // todo email
      $_SESSION["status"] = "Donor Validation Success";
      $_SESSION["status_text"] = "Successfully denied the donor account!";
      $_SESSION["status_code"] = "success";
      header('Location: ../../p/adm/dashboard.php');
      die();
    } else {
      $_SESSION["status"] = "Donor Validation Failed";
      $_SESSION["status_text"] = "Failed to denied the donor account!";
      $_SESSION["status_code"] = "error";
      header('Location: ../../p/adm/dashboard.php');
      die();
    }

  }

  if (isset($_POST["orgValidYes"])) {
    // get the id of org
    $org_id = $_POST["valid_org_id"];
    // set the is_approved to 1
    $up = $db->query("UPDATE `tblorgs` SET is_approved = '1', date_approved = NOW() WHERE org_id = '$org_id'");
    // add date_approved
    
    if ($up) {
      // todo email
      $_SESSION["status"] = "Organization Validation Success";
      $_SESSION["status_text"] = "Successfully validated the organization account!";
      $_SESSION["status_code"] = "success";
      header('Location: ../../p/adm/dashboard.php');
      die();
    } else {
      $_SESSION["status"] = "Organization Validation Failed";
      $_SESSION["status_text"] = "Failed to validate the organization!";
      $_SESSION["status_code"] = "error";
      header('Location: ../../p/adm/dashboard.php');
      die();
    }
  }

  if (isset($_POST["orgValidNo"])) {
    // get the id of org
    $org_id = $_POST["valid_org_id"];
    // delete the org
    // delete the images connected to donor
    $org_delete = $db->query("DELETE FROM `tblorgs` WHERE org_id = '$org_id'");
    $user_delete = $db->query("DELETE FROM `tblusers` WHERE user_id = '$org_id'");
    //$image_delete = $db->query("DELETE FROM `tblimages` WHERE table_id = '$org_id' AND category = 'org_permit' AND permit_type = 'permit'");

    $imageEventQuery = mysqli_query($conn, "SELECT * FROM `tblimages` WHERE table_id = '$org_id' AND permit_type = 'permit' AND category = 'org_permit'");
    if (mysqli_num_rows($imageEventQuery) > 0) {
      while ($row = mysqli_fetch_assoc($imageEventQuery)) {
          $image_id = $row['image_id'];
          $deleteID = mysqli_query($conn, "DELETE FROM `tblimages` WHERE image_id = '$image_id'");
      }
    }

    if ($org_delete) {
      // todo email
      $_SESSION["status"] = "Organization Validation Success";
      $_SESSION["status_text"] = "Successfully denied the organization account!";
      $_SESSION["status_code"] = "success";
      header('Location: ../../p/adm/dashboard.php');
      die();
    } else {
      $_SESSION["status"] = "Donor Validation Failed";
      $_SESSION["status_text"] = "Failed to denied the organization account!";
      $_SESSION["status_code"] = "error";
      header('Location: ../../p/adm/dashboard.php');
      die();
    }
  }

  if (isset($_POST['donationYes'])) {
    // admin-approved to charity approval?
    $org_id = $_POST['org_id'];
    $donation_id = $_POST['donation_id'];
    $donor_id = $_POST['donor_id'];
    $event_id = $_POST['event_id'];

    $donations = $db->query("SELECT * FROM `tbldonations` WHERE donation_id = '$donation_id'");
    if ($donations) {
      $donation = mysqli_fetch_assoc($donations);
      $inkind_quantity = $donation['donation_amount'];
    }

    $events = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id'");
    if ($events) {
      $event = mysqli_fetch_assoc($events);
      $current_inkind = $event['current_inkind'];
    }

    // update donation status
    $donation_status = $db->query("UPDATE `tbldonations` SET status = 'approved' WHERE donation_id = '$donation_id'");

    // update current_inkind
    $amount = (int) $inkind_quantity + (int) $current_inkind;
    $update_inkind = $db->query("UPDATE `tblorgtimeline` SET current_inkind = '$amount' WHERE event_id = '$event_id' AND org_id = '$org_id'");
    
    if ($donation_status && $update_inkind) {
      // todo email the donor and charity (if not proceed to validation of charity)
      $_SESSION["status"] = "Donation Validation Success";
      $_SESSION["status_text"] = "Successfully accept the inkind donation to charities!";
      $_SESSION["status_code"] = "success";
      header('Location: ../../p/adm/dashboard.php');
      die();
    } else {
      $_SESSION["status"] = "Donation Validation Failed";
      $_SESSION["status_text"] = "Failed to validate the inkind donation!";
      $_SESSION["status_code"] = "error";
      header('Location: ../../p/adm/dashboard.php');
      die();
    }


  }

  if (isset($_POST['donationNo'])) {
    // delete the donation inkind and pending
    $org_id = $_POST['org_id'];
    $donation_id = $_POST['donation_id'];
    $donor_id = $_POST['donor_id'];
    $event_id = $_POST['event_id'];

    $delete = $db->query("DELETE FROM `tbldonations` WHERE donation_id = '$donation_id' AND org_id = '$org_id' AND donor_id = '$donor_id' AND event_id = '$event_id'");

    $imageEventQuery = mysqli_query($conn, "SELECT * FROM `tblimages` WHERE table_id = '$donation_id' AND permit_type = 'inkind' AND category = 'donation_image'");
    if (mysqli_num_rows($imageEventQuery) > 0) {
      while ($row = mysqli_fetch_assoc($imageEventQuery)) {
          $image_id = $row['image_id'];
          $deleteID = mysqli_query($conn, "DELETE FROM `tblimages` WHERE image_id = '$image_id'");
      }
    }

    if ($delete) {
      $_SESSION["status"] = "Donation Validation Success";
      $_SESSION["status_text"] = "Successfully denied the inkind donation to charities!";
      $_SESSION["status_code"] = "success";
      header('Location: ../../p/adm/dashboard.php');
      die();
    } else {
      $_SESSION["status"] = "Donation Validation Failed";
      $_SESSION["status_text"] = "Failed to validate the inkind donation!";
      $_SESSION["status_code"] = "error";
      header('Location: ../../p/adm/dashboard.php');
      die();
    }
  }

}
?>