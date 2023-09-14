<?php
session_start();

include '../database.php';

$db = new Database();
$conn = $db->connect();
$id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $reviewId = $_POST['reviewId'];

  $deleteQuery = "DELETE FROM `tbldonorrating` WHERE rating_id = ?";
  $stmt = $conn->prepare($deleteQuery);
  $stmt->bind_param("i", $reviewId);

  if ($stmt->execute()) {
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
