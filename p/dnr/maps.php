<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CharitEase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="icon" href="../../lib/imgs/charitease_icon.png">
    <link rel="stylesheet" href="https://openlayers.org/en/v6.5.0/css/ol.css" type="text/css">
    <style>
        #map {
            height: 400px;
            width: 100%;
        } 

        .ol-popup {
            position: absolute;
            background-color: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
            white-space: normal; /* Change from nowrap to normal */
            max-width: 200px;
            max-height: none; /* Remove the max-height property */
            overflow: visible; /* Add overflow: visible property */
        }

        .ol-popup:after {
            content: '';
            position: absolute;
            border-style: solid;
            border-width: 8px 8px 0;
            border-color: #ccc transparent;
            display: block;
            width: 0;
            z-index: 1;
            margin-left: -8px;
            bottom: -8px;
            left: 50%;
        }

    </style>
</head>
<body>
<?php
    session_start();
    if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'donor')) {
      header('Location: ../../p/reg/login.php');
      exit;
    }
  ?>

  <!-- HEADER -->
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <div class="navbar-start">
        <a href="" class="navbar-item">
          <img src="../../lib/imgs/charitease_icon.png" alt="Logo">
          <h1 class="subtitle">CharitEase</h1>
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
        <a href="../../index.php" class="navbar-item">Home</a>
        <div class="navbar-item has-dropdown is-hoverable">
          <a href="" class="navbar-link">CharitEase</a>
          <div class="navbar-dropdown">
            <a href="../../p/com/features.php" class="navbar-item">Features</a>
            <a href="../../p/com/company.php" class="navbar-item">Company</a>
            <a href="../../p/com/about.php" class="navbar-item">About</a>
          </div>
        </div>
        <?php
          if (isset($_SESSION['user'])) {
            if ($_SESSION['user'] == 'donor') {
        ?>
          <!-- DONOR BUTTON -->
              <div class="navbar-item has-dropdown is-hoverable">
                <a href="#" class="navbar-link">Donor</a>
                <div class="navbar-dropdown">
                  <a href="../../p/dnr/donate.php" class="navbar-item">Donate</a>
                  <a href="../../p/dnr/dashboard.php" class="navbar-item">Dashboard</a>
                </div>
              </div>
        <?php 
            } else if ($_SESSION['user'] == 'charity') {
        ?>
          <!-- ORGANIZATION BUTTON -->
			        <div class="navbar-item has-dropdown is-hoverable">
                <a href="" class="navbar-link">Charity</a>
                <div class="navbar-dropdown">
				          <a href="../../p/org/donors.php" class="navbar-item">Donors</a>
                  <a href="../../p/org/dashboard.php" class="navbar-item">Dashboard</a>
                </div>
              </div>
        <?php 
          } else if ($_SESSION['user'] == 'admin') {
        ?>
          <!-- ADMIN BUTTONS -->
          <a href="../../p/adm/dashboard.php" class="navbar-item">Dashboard</a>
        <?php 
            }
        ?>
            <a href="../../p/reg/logout.php" class="navbar-item">Logout</a>
        <?php 
          } else {
        ?>
			<a href="../../p/reg/register.php" class="navbar-item">Register</a>
            <a href="../../p/reg/login.php" class="navbar-item">Login</a>
        <?php } ?>
      </div>
      <span class="navbar-item"></span>
      <span class="navbar-item"></span>
    </div>
  </nav>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Get all "navbar-burger" elements
      const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
      // Add a click event on each of them
      $navbarBurgers.forEach( el => {
          el.addEventListener('click', () => {
            // Get the target from the "data-target" attribute
            const target = el.dataset.target;
            const $target = document.getElementById(target);
            // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
            el.classList.toggle('is-active');
            $target.classList.toggle('is-active');
          });
      });
    });
  </script>

<!-- CONTENT HERE -->
  <section class="section">
      <div class="container content">
          <h1 class="subtitle has-text-centered">
              <a href="../../index.php" class="button is-pulled-left is-info">Back</a> Nearby Charitable Organizations
          </h1>
          <div class="columns">
              <div class="column is-two-thirds">
                  <div class="box">
                      <div id="map"></div>
                  </div>
              </div>
              <div class="column">
                  <div class="box">
                      <p style="font-family:'Times New Roman', Times, serif;">
                          <strong>Map Guide:</strong>
                      </p>
                      <ul style="font-family:'Times New Roman', Times, serif;">
                        <li>Your address is automatically mark as red in map</li>
                        <li>The Green Mark is the Charitable Organization</li>
                        <li>You can zoom in/out the map</li>
                        <li>You can hover and see the name and address of Charitable Organization</li>
                        <li>You can click the green mark of Charitable Organization and proceed to page</li>
                      </ul>
                  </div>
              </div>
          </div>
      </div>

      <div id="popup" class="ol-popup">
          <div id="popup-content"></div>
      </div>
  </section>

<?php

include '../../lib/database.php';

$db = new Database();
$conn = $db->connect();

$user = $_SESSION['user'];
$id = $_SESSION['id'];

$getDonorText = "SELECT * FROM `tbldonors` WHERE donor_id = $id";
$resultDonor = mysqli_query($conn, $getDonorText);

if (mysqli_num_rows($resultDonor) > 0) {
    $donor = mysqli_fetch_assoc($resultDonor);
}

$address = $donor["donor_address"];

$geocode_url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($address);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $geocode_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
$geocode_response = curl_exec($curl);
curl_close($curl);

$geocode_data = json_decode($geocode_response, true);

if (!empty($geocode_data)) {
    $latitude = $geocode_data[0]['lat'];
    $longitude = $geocode_data[0]['lon'];
    $nearaddress = $address;

    echo '<script src="https://openlayers.org/en/v6.5.0/build/ol.js" type="text/javascript"></script>';
    echo '<script>
        var latitude = ' . $latitude . ';
        var longitude = ' . $longitude . ';

        var map = new ol.Map({
            target: "map",
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([longitude, latitude]),
                zoom: 15
            })
        });

        var markerLayer = new ol.layer.Vector({
            source: new ol.source.Vector(),
            style: new ol.style.Style({
                image: new ol.style.Icon({
                    src: "https://openlayers.org/en/v6.5.0/examples/data/icon.png"
                })
            })
        });
        map.addLayer(markerLayer);

        function handleMarkerHover(event) {
            var feature = event.target;
            var coordinates = feature.getGeometry().getCoordinates();

            var charityName = feature.get("charityName");
            var charityAddress = feature.get("charityAddress");

            var popupContent = document.getElementById("popup-content");
            popupContent.innerHTML = "<strong>" + charityName + "</strong><br>" + charityAddress;

            var popup = document.getElementById("popup");
            var mapRect = map.getTargetElement().getBoundingClientRect();
            var pixel = map.getPixelFromCoordinate(coordinates);
            var popupWidth = popup.offsetWidth;
            var popupHeight = popup.offsetHeight;

            var left = mapRect.left + pixel[0] - popupWidth / 2 + "px";
            var top = mapRect.top + pixel[1] - popupHeight - 10 + "px";
            popup.style.left = left;
            popup.style.top = top;

            popup.style.display = "block";
        }

        function handleMarkerHoverEnd() {
            var popup = document.getElementById("popup");
            popup.style.display = "none";
        }

        var hoverInteraction = new ol.interaction.Select({
            condition: ol.events.condition.pointerMove,
            layers: [markerLayer],
            style: null
        });
        hoverInteraction.on("select", function(event) {
            if (event.selected.length > 0) {
                handleMarkerHover({ target: event.selected[0] });
            } else {
                handleMarkerHoverEnd();
            }
        });
        map.addInteraction(hoverInteraction);

        function handleMarkerClick(event) {
            var feature = event.target;
            var charityId = feature.get("charityID");

            if (charityId == "id") {
                window.location.href = "donate.php";
            } else {
                window.location.href = "donation.php?oid="  + charityId;
            }
        }

        map.on("singleclick", function(event) {
            map.forEachFeatureAtPixel(event.pixel, function(feature, layer) {
                handleMarkerClick({ mapBrowserEvent: event, target: feature });
            });
        });

        var yourMarker = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.fromLonLat([longitude, latitude])),
            charityName: "Your Address",
            charityAddress: "' . $address . '",
            charityID: "id"
        });

        var yourMarkerStyle = new ol.style.Style({
            image: new ol.style.Circle({
                radius: 8,
                fill: new ol.style.Fill({
                    color: "red"
                }),
                stroke: new ol.style.Stroke({
                    color: "black",
                    width: 2
                })
            })
        });

        yourMarker.setStyle(yourMarkerStyle);
        markerLayer.getSource().addFeature(yourMarker);
    
        exactLocationMarker.setStyle(exactLocationMarkerStyle);
        markerLayer.getSource().addFeature(exactLocationMarker);

    </script>';

    $query = "SELECT org_id, org_name, org_address FROM tblorgs";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $charity_name = $row['org_name'];
            $charity_address = $row['org_address'];
            $charity_id = $row['org_id'];

            $geocode_url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($charity_address);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $geocode_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
            $geocode_response = curl_exec($curl);
            curl_close($curl);

            $geocode_data = json_decode($geocode_response, true);

            if (!empty($geocode_data)) {
                $charity_latitude = $geocode_data[0]['lat'];
                $charity_longitude = $geocode_data[0]['lon'];

                echo '<script>
                    var charityMarker = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat([' . $charity_longitude . ', ' . $charity_latitude . '])),
                        charityName: "' . $charity_name . '",
                        charityAddress: "' . $charity_address . '",
                        charityID: "'. $charity_id . '"
                    });
                    markerLayer.getSource().addFeature(charityMarker);
                </script>';
            }
        }
    } else {
        echo '<script>
            var charityMarker = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([' . $longitude . ', ' . $latitude . '])),
                charityName: "Your Address",
                charityAddress: "' . $address . '",
                charityID: "id"
            });
            markerLayer.getSource().addFeature(charityMarker);
        </script>';
    }
    mysqli_close($conn);
}
?>

<!-- CONTENT END -->

  <!-- FOOTER -->
  <footer class="section">
    <div class="container">
      <div class="pb-5 is-flex is-flex-wrap-wrap is-justify-content-between is-align-items-center">
        <div class="mr-auto mb-1">
          <a href="#" class="is-inline-block">
            <img src="../../lib/imgs/charitease_icon.png" alt="" class="image is-64x64">
          </a>
        </div>
        <div>
          <ul class="is-flex is-flex-wrap-wrap is-align-items-center is-justify-content-center">
            <li class="mr-4"><a href="../../p/com/about.php" class="button is-white">About</a></li>
            <li class="mr-4"><a href="../../p/com/company.php" class="button is-white">Company</a></li>
            <li class="mr-4"><a href="../../p/com/features.php" class="button is-white">Features</a></li>
            <li class="mr-4"><a href="#" class="button is-white">Testimonials</a></li>
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

  <?php include '../../lib/alert.php'; ?>
</body>
</html>
