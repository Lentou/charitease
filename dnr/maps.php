<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CharitEase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="icon" href="../lib/imgs/charitease_icon.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;700&display=swap">

    <style>
        body {
            font-family: 'Red Hat Display', sans-serif;
        }

        .custom-marker {
            background: none;
        }
    </style>

</head>
<body>
    <?php
        //use lib\dbpdo;
        if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'donor')) {
            header('Location: ../reg/login.php');
            exit;
        }

        //include '../lib/dbpdo.php';
        include 'action/mappa.php';

    ?>

  <!-- HEADER -->
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <div class="navbar-start">
                <a href="" class="navbar-item">
                    <img src="../lib/imgs/charitease_icon.png" alt="Logo">
                    <?php 
				$role = "";
				$icon = "";
				if (isset($_SESSION['user'])) {
					$role = " // " . ucfirst($_SESSION['user']);

					$icon = match ($_SESSION['user']) {
						'donor' => "volunteer_activism",
						'charity' => "real_estate_agent",
						'admin' => "shield_person",
						default => ""
					};
				}
            ?>
            <h1 class="subtitle">CharitEase <span class="material-symbols-outlined"><?= $icon; ?></span><?= $role; ?></h1>
                </a>
            </div>
            <span class="navbar-burger burger" data-target="navbarMenu">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </span>
        </div>

        <div id="navbarMenu" class="navbar-menu">
        <div class="navbar-end">
            <a href="../index.php" class="navbar-item"><span class="material-symbols-outlined">home</span>Home</a>
            <?php
            if (isset($_SESSION['user'])) {
                if ($_SESSION['user'] == 'donor') {
            ?>
                <a href="../dnr/donate.php" class="navbar-item"><span class="material-symbols-outlined">real_estate_agent</span>List</a>
                <a href="../dnr/maps.php" class="navbar-item"><span class="material-symbols-outlined">home_pin</span>Map</a>
                <a href="#" class="navbar-item"><span class="material-symbols-outlined">chat</span>Chat</a>
                <a href="../dnr/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
            <?php 
                } else if ($_SESSION['user'] == 'charity') {
            ?>
            <!-- ORGANIZATION BUTTON -->
                <a href="../org/donors.php" class="navbar-item"><span class="material-symbols-outlined">chat</span>Chat</a>
                <a href="../org/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
            <?php 
                } else if ($_SESSION['user'] == 'admin') {
            ?>
            <!-- ADMIN BUTTONS -->
            <a href="../adm/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
            <?php 
                }
            ?>
              <div class="navbar-item has-dropdown is-hoverable">
                <a href="" class="navbar-link"><span class="material-symbols-outlined">account_circle</span><?= $_SESSION['name']; ?></a>
                <div class="navbar-dropdown">
                  <a href="../reg/settings.php" class="navbar-item"><span class="material-symbols-outlined">manage_accounts</span>Settings</a>
                  <a href="../reg/logout.php" class="navbar-item"><span class="material-symbols-outlined">logout</span>Logout</a>
                </div>
              </div>
            <?php 
              } else {
            ?>
                <a href="../reg/register.php" class="navbar-item"><span class="material-symbols-outlined">how_to_reg</span>Register</a>
                <a href="../reg/login.php" class="navbar-item"><span class="material-symbols-outlined">login</span>Login</a>
            <?php } ?>
            <span class="navbar-item"></span>
            <span class="navbar-item"></span>
        </div>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
        $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {
                const target = el.dataset.target;
                const $target = document.getElementById(target);
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
        });
    </script>

    <!-- CONTENT HERE -->
    <section class="section has-background-info">
        <div class="container content">
            <h1 class="subtitle has-text-centered">
                <a href="../index.php" class="button is-small is-pulled-left is-link has-text-white">Back</a> <p class="has-text-white">Nearby Charitable Organizations</p>
            </h1>
            <div class="columns">
                <div class="column is-two-thirds">
                    <div class="box">
                        <div id="map" style="height: 410px;"></div>
                    </div>
                </div>
                <div class="column">

                    <div class="box">
                        <form action="action/a.search.php" method="GET">
                            <div class="field">
                                <label class="label">Search for Charity to Locate:</label>
                                <div class="control">
                                    <input class="input" type="text" name="charity_search" placeholder="Enter Charity Name">
                                </div>
                            </div>
                            <div class="control">
                                <button class="button is-link" type="submit" id="search_button">Search</button>
                            </div>
                        </form>
                    </div>

                    <div class="box">
                        <p class="label">
                            Your Address's Nearest Charity: <span class="button is-white is-loading" id="loading">Loading</span>
                        </p>
                        <p id="nearest_org"></p>
                        <p id="nearest_address"></p>
                        <p id="nearest_km"></p>
                    </div>

                </div>
            </div>
        </div>

    </section>

    <script>
        const urlParams = new URLSearchParams(window.location.search);

        var map = null;
        if (urlParams.has('lat') && urlParams.has('lng')) {
            const lat = parseFloat(urlParams.get('lat'));
            const lng = parseFloat(urlParams.get('lng'));
            map = L.map('map').setView([lat, lng], 15);
        } else {
            map = L.map('map').setView([14.6760, 120.5365], 14); // Set the initial map view
        }

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        function geocodeAddress(address) {
            return new Promise((resolve, reject) => {
                const geocodeUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address);
                const geocodeXhr = new XMLHttpRequest();
                geocodeXhr.open('GET', geocodeUrl, true);
                geocodeXhr.onreadystatechange = function () {
                    if (geocodeXhr.readyState === 4) {
                        if (geocodeXhr.status === 200) {
                            const geocodeData = JSON.parse(geocodeXhr.responseText);
                            if (geocodeData.length > 0) {
                                const lat = parseFloat(geocodeData[0].lat);
                                const lon = parseFloat(geocodeData[0].lon);
                                resolve({ lat, lon });
                            } else {
                                reject('Geocoding failed');
                            }
                        } else {
                            reject('Geocoding request failed');
                        }
                    }
                };
                geocodeXhr.send();
            });
        }

        function createMarker(lat, lon, orgName, orgAddress, distance, mcolor) {
            var markerIcon = L.divIcon({
                className: 'custom-marker',
                html: `<svg width="30" height="42" viewBox="0 0 30 42" xmlns="http://www.w3.org/2000/svg" fill="${mcolor}"><path d="M15 0C6.716 0 0 6.716 0 15c0 9.5 15 27 15 27s15-17.5 15-27C30 6.716 23.284 0 15 0zm0 21.25c-2.48 0-4.5-2.02-4.5-4.5s2.02-4.5 4.5-4.5 4.5 2.02 4.5 4.5-2.02 4.5-4.5 4.5z"/></svg>`,
                iconSize: [30, 42],
                iconAnchor: [15, 42]
            });

            var marker = L.marker([lat, lon], {
                icon: markerIcon
            }).addTo(map).bindPopup(orgName + '<br>' + orgAddress + '<br>' + distance);
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const earthRadius = 6371; // Radius of the Earth in kilometers

            const lat1Rad = toRadians(lat1);
            const lon1Rad = toRadians(lon1);
            const lat2Rad = toRadians(lat2);
            const lon2Rad = toRadians(lon2);

            const dLat = lat2Rad - lat1Rad;
            const dLon = lon2Rad - lon1Rad;

            const a =
                Math.sin(dLat / 2) ** 2 +
                Math.cos(lat1Rad) * Math.cos(lat2Rad) * Math.sin(dLon / 2) ** 2;

            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            const distance = earthRadius * c; // Distance in kilometers
            return distance.toFixed(2);
        }

        function toRadians(degrees) {
            return degrees * (Math.PI / 180);
        }

        async function getNearestOrganizationAddress(donorAddress) {
            const orgsResponse = await fetch('action/a.org.php');
            const orgsData = await orgsResponse.json();

            const { lat, lon } = await geocodeAddress(donorAddress);

            let nearestOrgAddress = '';
            let nearestOrgName = '';
            let nearestDistance = Number.MAX_VALUE;

            orgsData.forEach(function (org) {
                const orgLocation = L.latLng(org.lat, org.lng);

                const distance = calculateDistance(
                    lat,
                    lon,
                    org.lat,
                    org.lng
                );

                if (distance < nearestDistance) {
                    nearestOrgName = org.name;
                    nearestOrgAddress = org.address;
                    nearestDistance = distance;
                }
            });

            return [nearestOrgName, nearestOrgAddress, nearestDistance];
        }

        async function fetchAndDisplayOrganizations() {
            try {
                const response = await fetch('action/a.map.php');
                const responseData = await response.json();
                const orgs = responseData.orgs;
                const donor = responseData.donor;

                const donorAddress = donor.donor_address;
                const donorLocation = await geocodeAddress(donorAddress);

                for (const org of orgs) {
                    if (org.org_lat !== null && org.org_lng !== null) {
                        // Calculate distance between donor and organization
                        const distance = calculateDistance(
                            donorLocation.lat,
                            donorLocation.lon,
                            parseFloat(org.org_lat),
                            parseFloat(org.org_lng)
                        );

                        createMarker(
                            parseFloat(org.org_lat),
                            parseFloat(org.org_lng),
                            org.org_name,
                            org.org_address,
                            distance,
                            '#FF3535'
                        );
                    } else {
                        const address = org.org_address;
                        if (address) {
                            try {
                                const { lat, lon } = await geocodeAddress(address);

                                const distance = calculateDistance(
                                    donorLocation.lat,
                                    donorLocation.lon,
                                    lat,
                                    lon
                                );

                                createMarker(lat, lon, org.org_name, address, distance, '#FF3535');
                            } catch (error) {
                                console.error('Geocoding error:', error);
                            }
                        }
                    }
                }

                // Fetch the nearest organization and display it
                getNearestOrganizationAddress(donorAddress)
                    .then(function (array) {
                        const nearestOrgName = array[0];
                        const nearestOrgAddress = array[1];
                        const nearestDistance = array[2];

                        document.getElementById('nearest_org').innerHTML = nearestOrgName;
                        document.getElementById('nearest_address').innerHTML = nearestOrgAddress;
                        document.getElementById('nearest_km').innerHTML = nearestDistance + " km";
                        document.getElementById('loading').classList.add('is-hidden');
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
                if (donor.donor_lat !== null && donor.donor_lng !== null) {
                    createMarker(parseFloat(donor.donor_lat), parseFloat(donor.donor_lng), 'Your Address', donorAddress, '#24D4FF');
                } else {
                    try {
                        const { lat, lon } = await geocodeAddress(donorAddress);
                        createMarker(lat, lon, 'Your Address', donorAddress, 0, '#24D4FF');
                    } catch (error) {
                        console.error('Geocoding error:', error);
                    }
                }

            } catch (error) {
                console.error('Error fetching organizations:', error);
            }
        }

        fetchAndDisplayOrganizations();
    </script>

    <!-- FOOTER -->
    <footer class="section">
        <div class="container">
        <div class="pb-5 is-flex is-flex-wrap-wrap is-justify-content-between is-align-items-center">
            <div class="mr-auto mb-1">
            <a href="#" class="is-inline-block">
                <img src="../lib/imgs/charitease_icon.png" alt="" class="image is-64x64">
            </a>
            </div>
            <div>
            <ul class="is-flex is-flex-wrap-wrap is-align-items-center is-justify-content-center">
                <li class="mr-4"><a href="../com/about.php" class="button is-white">About</a></li>
                <li class="mr-4"><a href="../com/company.php" class="button is-white">Company</a></li>
            </ul>
            </div>
        </div>
        </div>
        <div class="pt-5" style="border-top: 1px solid #dee2e6;"></div>
        <div class="container">
        <div class="is-flex-tablet is-justify-content-between is-align-items-center">
            <p>All rights reserved © CharitEase 2023</p>
            <div class="py-2 is-hidden-tablet"></div>
        </div>
        </div>
    </footer>

    <?php include '../lib/alert.php'; ?>
</body>
</html>
