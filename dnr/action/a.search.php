<?php
// Include your database connection code here
// For example: require_once('db_connection.php');
session_start();
use lib\dbpdo;

include '../../lib/dbpdo.php';
include 'mappa.php';

$db = new dbpdo();
$pdo = $db->getpdo();

// Check if the search query parameter is set
if (isset($_GET['charity_search']) || $_GET['charity_search'] != "") {
    $searchQuery = $_GET['charity_search'];

    // Sanitize the input to prevent SQL injection (you should use prepared statements for better security)
    $searchQuery = htmlspecialchars($searchQuery);

    // Perform a database query to search for charities based on the user's input
    $sql = "SELECT * FROM tblorgs WHERE org_name LIKE :searchQuery";
    
    // Prepare and execute the query (you should use prepared statements for better security)
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['searchQuery' => '%' . $searchQuery . '%']);

    // Fetch the results
    $charities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the search results
    if (count($charities) > 0) {
        // Assuming you have latitude and longitude columns in your database for each charity
        $latitude = $charities[0]['org_lat'];
        $longitude = $charities[0]['org_lng'];

        if ($latitude != null || $longitude != null) {
            // JavaScript code to update the map's view
            echo "<script>
                window.location.href = `../dnr/maps.php?lat=$latitude&lng=$longitude`;
            </script>";

        } else {
            $address = mappa::geocodeAddress($charities[0]['org_address']);
            $latitude = $address['lat'];
            $longitude = $address['lng'];

            echo "<script>
                window.location.href = `../dnr/maps.php?lat=$latitude&lng=$longitude`;
            </script>";

        }

        unset($_GET['charity_search']);
    } else {
        $_SESSION['status'] = "Search Failed";
        $_SESSION['status_text'] = "No charities found matching your search.";
        $_SESSION['status_code'] = "error";
        header('Location: ../dnr/maps.php');
        unset($_GET['charity_search']);
    }
} else {
    $_SESSION['status'] = "Search Failed";
    $_SESSION['status_text'] = "Please enter a search query.";
    $_SESSION['status_code'] = "error";
    header('Location: ../dnr/maps.php');
}
?>
