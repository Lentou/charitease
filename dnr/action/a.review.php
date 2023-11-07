<?php
session_start();

include '../../lib/database.php';

$db = new Database();
$conn = $db->connect();
$id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $reviewId = $_POST['reviewId'];

  //$deleteQuery = "DELETE FROM `tblratings` WHERE rating_id = ?";
  $deleteQuery = "DELETE FROM `tblratings` WHERE rating_id = '$reviewId'";
  $stmt = $db->query($deleteQuery);

  //$stmt = $conn->prepare($deleteQuery);
  //$stmt->bind_param("i", $reviewId);

  if ($stmt) {
  //if ($stmt->execute()) {
    $response = [
      'success' => true
    ];
    echo json_encode($response);
    exit;
  } else {
    $response = [
      'success' => false
    ];
    echo json_encode($response);
    exit;
  }
}
?>
