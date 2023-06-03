<?php
  session_start();
  unset($_SESSION['user']);
  unset($_SESSION['id']);
  $_SESSION["status"] = "Logout Success";
  $_SESSION["status_text"] = "Successfully Logged-Out";
  $_SESSION["status_code"] = "success";
  header('Location: ../../index.php');
  die();
?>
