<?php

use lib\dbpdo;
include '../../lib/dbpdo.php';
include '../../lib/config.php';

session_start();

$dbo = new dbpdo();

$id = $_SESSION['id'];

// Optimize the SQL query by selecting only necessary columns and using placeholders
$orgsQuery = "SELECT org_id, org_name, org_address, org_lat, org_lng FROM tblorgs";
$get_orgs = $dbo->fetchAll($orgsQuery);

// Optimize the SQL query by selecting only necessary columns and using a placeholder
$get_donor = $dbo->fetch("SELECT donor_id, donor_address, donor_lat, donor_lng FROM `tbldonors` WHERE donor_id = ?", [$id]);

$data = [
    'orgs' => $get_orgs,
    'donor' => $get_donor,
];

$jsonData = json_encode($data);

header('Content-Type: application/json');

echo $jsonData;
?>
