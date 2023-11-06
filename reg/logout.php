<?php
  if (!isset($_SESSION)) session_start();

  unset($_SESSION['name']);
  unset($_SESSION['user']);
  unset($_SESSION['id']);

  $_SESSION["status"] = "Logout";
  $_SESSION["status_text"] = "Successfully logged out";
  $_SESSION["status_code"] = "success";
  header("Location: ../index.php");
  die();
?>