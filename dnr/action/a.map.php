<?php

//use lib\dbpdo;
//include '../../lib/dbpdo.php';
include '../../lib/database.php';
include '../../lib/config.php';

session_start();

//$dbo = new dbpdo();
$db = new Database();

$id = $_SESSION['id'];

// Optimize the SQL query by selecting only necessary columns and using placeholders
$orgsQuery = "SELECT org_id, org_name, org_address, org_lat, org_lng FROM tblorgs";

$getOrgText = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'c' AND c.is_approved = '1'";
$get_orgs = $db->query($getOrgText);

$getDonorText = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE c.client_id = '$id' AND u.account_type = 'd'";
$get_donor = $db->query($getDonorText);

//$get_orgs = $dbo->fetchAll($orgsQuery);

// Optimize the SQL query by selecting only necessary columns and using a placeholder
//$get_donor = $dbo->fetch("SELECT donor_id, donor_address, donor_lat, donor_lng FROM `tbldonors` WHERE donor_id = ?", [$id]);


$data = [
    'orgs' => $get_orgs->fetch_all(1),
    'donor' => $get_donor->fetch_assoc(),
];

$jsonData = json_encode($data);

header('Content-Type: application/json');

echo $jsonData;
?>
