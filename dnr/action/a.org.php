<?php
use lib\dbpdo;
include '../../lib/dbpdo.php';

session_start();

$dbo = new dbpdo();
$orgsQuery = "SELECT org_id, org_name, org_address, org_lat, org_lng FROM tblorgs";
$orgs = $dbo->fetchAll($orgsQuery);

// Convert the $orgs array to JSON format
include 'mappa.php';

$orgsJson = [];

foreach ($orgs as $org) {
    if ($org['org_lat'] == null && $org['org_lng'] == null) {
        $location = mappa::geocodeAddress($org['org_address']);
    } else {
        $location = ['lat' => $org['org_lat'], 'lng' => $org['org_lng']];
    }

    $orgsJson[] = [
        'name' => $org['org_name'],
        'address' => $org['org_address'],
        'lat' => $location['lat'], // Latitude of the organization
        'lng' => $location['lng'], // Longitude of the organization
    ];
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($orgsJson);


?>