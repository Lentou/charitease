<?php

include '../../lib/config.php';
include '../../lib/database.php';
require '../../lib/email.php';

if (!isset($_SESSION)) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();

    if (isset($_POST["donation_accept"])) {

        $org_id = $_POST['org_id'];
        $donation_id = $_POST['donation_id'];
        $donor_id = $_POST['donor_id'];
        $event_id = $_POST['event_id'];

        $donations = $db->query("SELECT * FROM `tbldonations` WHERE donation_id = '$donation_id'");
        if ($donations) {
          $donation = $donations->fetch_assoc();
          $inkind_quantity = $donation['donation_amount'];
        }

        //$events = $db->query("SELECT * FROM `tblevents` WHERE event_id = '$event_id' AND is_approved = '1'");
        //if ($events) {
          //$event = $events->fetch_assoc();
          //$current_inkind = $event['current_inkind'];
          
        //}
        $collections = $db->query("SELECT * FROM `tblcollections` WHERE event_id = '$event_id'");
        if ($collections) {
          $collect = $collections->fetch_assoc();
          $current_inkind = $collections['current_inkind'];
        }

        $donation_status = $db->query("UPDATE `tbldonations` SET donation_status = 'a' WHERE donation_id = '$donation_id'");
        
        $amount = (int) $inkind_quantity + (int) $current_inkind;
        $update_inkind = $db->query("UPDATE `tblcollections` SET current_inkind = '$amount' WHERE event_id = '$event_id'");

        // the validation will be on processed like, if (accept = pending delivery then if delivered = completed!)
        $status = ($donation_status && $update_inkind) ? "Validation Success" : "Validation Failed";
        $status_text = ($donation_status && $update_inkind) ? "Successfully accept the inkind donation!" : "Failed to validate the inkind donation!";
        $status_code = ($donation_status && $update_inkind) ? "success" : "error";
        $_SESSION["status"] = "Donation " . $status;
        $_SESSION["status_text"] = $status_text;
        $_SESSION["status_code"] = $status_code;
        location("../dashboard.php");
    }

    if (isset($_POST["donation_deny"])) {
      $org_id = $_POST["org_id"];
      $donation_id = $_POST["donation_id"];
      $donor_id = $_POST["donor_id"];
      $event_id = $_POST["event_id"];

      $delete = $db->query("DELETE FROM `tbldonations` WHERE donation_id = '$donation_id' AND org_id = '$org_id' AND donor_id = '$donor_id' AND event_id = '$event_id'");

      /*
      $imageEventQuery = mysqli_query($conn, "SELECT * FROM `tblimages` WHERE table_id = '$donation_id' AND permit_type = 'inkind' AND category = 'donation_image'");
      if (mysqli_num_rows($imageEventQuery) > 0) {
        while ($row = mysqli_fetch_assoc($imageEventQuery)) {
            $image_id = $row['image_id'];
            $deleteID = mysqli_query($conn, "DELETE FROM `tblimages` WHERE image_id = '$image_id'");
        }
      }*/
      $status = ($delete) ? "Validation Success" : "Validation Failed";
      $status_text = ($delete) ? "Successfully denied the inkind donation!" : "Failed to validate the inkind donation!";
      $status_code = ($delete) ? "success" : "error";
      $_SESSION["status"] = "Donation " . $status;
      $_SESSION["status_text"] = $status_text;
      $_SESSION["status_code"] = $status_code;
      location("../dashboard.php");

    }

}