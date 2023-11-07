<?php
//use lib\dbpdo;
//include '../../lib/dbpdo.php';
include '../../lib/database.php';
session_start();

//$dbo = new dbpdo();
//$orgsQuery = "SELECT org_id, org_name, org_address, org_lat, org_lng FROM tblorgs";
//$orgs = $dbo->fetchAll($orgsQuery);
$db = new Database();

$getOrgText = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'c' AND c.is_approved = '1'";
$orgs = $db->query($getOrgText);

// Convert the $orgs array to JSON format
include 'mappa.php';

$orgsJson = [];

$orgs = $orgs->fetch_all(1);

foreach ($orgs as $org) {
    if ($org['client_lat'] == null && $org['client_lng'] == null) {
    //if ($org['org_lat'] == null && $org['org_lng'] == null) {
        //$location = mappa::geocodeAddress($org['org_address']);
        $location = mappa::geocodeAddress($org["client_address"]);
    } else {
        //$location = ['lat' => $org['org_lat'], 'lng' => $org['org_lng']];
        $location = ['lat' => $org['client_lat'], 'lng' => $org['client_lng']];
    }

    /*
    $orgsJson[] = [
        'name' => $org['org_name'],
        'address' => $org['org_address'],
        'lat' => $location['lat'], // Latitude of the organization
        'lng' => $location['lng'], // Longitude of the organization
    ];*/

    $orgsJson[] = [
        'name' => $org['client_name'],
        'address' => $org['client_address'],
        'lat' => $location['lat'],
        'lng' => $location['lng']
    ];
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($orgsJson);


?>