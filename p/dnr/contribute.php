<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../../lib/imgs/charitease_icon.png">

  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://openlayers.org/en/v6.5.0/css/ol.css" type="text/css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios@0.24.0/dist/axios.min.js"></script>

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
            white-space: normal;
            max-width: 200px;
            max-height: none;
            overflow: visible;
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
                  <a href="../../p/dnr/donate.php" class="navbar-item">Charities</a>
                  <a href="../../p/dnr/maps.php" class="navbar-item">Nearest Charities</a>
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
                  <a href="../../p/org/donors.php" class="navbar-item">Direct Message</a>
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

  <?php 
    // Connect to database
    include '../../lib/database.php';

    $db = new Database();
		$conn = $db->connect();

    $org_id = $_GET['oid'];
    $event_id = $_GET['eid'];
    $donor_id = $_SESSION['id'];
  ?>

  <!-- CONTENT HERE! -->
  <section class="hero is-info is-small">
    <div class="hero-body">
      <div class="container">
        <h1 class="title">Donate Charity Organization</h1>
        <a href="timeline.php?oid=<?php echo $org_id; ?>" class="button is-pulled-left is-link is-small">Back</a>
      </div>
    </div>
  </section>

  <?php

    $org = $db->query("SELECT * FROM `tblorgs` WHERE org_id = '$org_id'");

    if ($org->num_rows > 0) {
      $orgs = mysqli_fetch_assoc($org);

      $org_name = $orgs['org_name'];
      $org_type = $orgs['org_type'];
      $org_address = $orgs['org_address'];
      $org_desc = $orgs['org_description'];
    }

    $event = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id' AND org_id = '$org_id'");

    if ($event->num_rows > 0) {
      $events = mysqli_fetch_assoc($event);

      $event_title = $events['event_title'];
      $event_type = $events['event_type'];
      $event_desc = $events['event_description'];
      $event_start_date = $events['event_start_date'];
      $event_end_date = $events['event_end_date'];
      $current_inkind = $events['current_inkind'];
      $target_inkind = $events['target_inkind'];
      $current_funds = $events['current_funds'];
      $target_funds = $events['target_funds'];
    }

    $donor = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");

    if ($donor->num_rows > 0) {
      $donors = mysqli_fetch_assoc($donor);

      $donor_address = $donors['donor_address'];
    }

  ?>

  <!-- TODO BACK BUTTON -->
  <section class="section">
      <div class="columns">
        <div class="column is-one-fifth">
          <div class="box">
          <aside class="menu">
            <p class="menu-label">Timeline</p>
            <ul class="menu-list">
              <li><a href="#location-tab" class="is-active">Location</a></li>
              <li><a href="#timeline-tab">Program</a></li>
              <li><a href="#aboutus-tab">About Us</a></li>
            </ul>
            <p class="menu-label">Donation Area</p>
            <ul class="menu-list">
              <?php 
                if ($target_funds != 0) {
              ?>
              <li><a href="#monetary-tab">Monetary</a></li>
              <?php 
                }
                if ($target_inkind != 0) {
              ?>
              <li><a href="#inkind-tab">In-kind</a></li>
              <?php 
                }
                if ($target_funds != 0 && $target_inkind != 0) {
              ?>
              <li><a href="#both-tab">Both Donate</a></li>
              <?php 
                }
              ?>
            </ul>
          </aside>
        </div>
      </div>

        <div class="column">
          
          <div id="monetary-tab" class="content is-hidden">
            <div class="box">
              <h1 class="subtitle">Monetary Donation</h1>
              <form action="../../lib/php/donor_donate.php?oid=<?php echo $org_id; ?>&eid=<?php echo $event_id; ?>" method="POST">

                <div class="field">
                  <label for="" class="label is-small">Payment Method</label>
                  <div class="control">
                    <div class="select is-small">
                      <select name="monetary_payment" id="" required>
                        <option value="">Select Payment Method</option>
                        <?php 
                        
                          $pay = $db->query("SELECT * FROM `tblpayments` WHERE event_id = '$event_id' AND org_id = '$org_id'");

                          if ($pay->num_rows > 0) {
                            while ($payr = mysqli_fetch_assoc($pay)) {
                        ?>
                        <option value="<?php echo $payr['payment_id']; ?>"><?php echo ucfirst($payr['method_type']); ?></option>
                        <?php
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="field">
                  <label for="" class="label is-small">Your Phone Number (GCash, Maya) / Email (Paypal)</label>
                  <div class="control">
                    <input type="text" class="input is-small" name="monetary_details" required>
                  </div>
                </div>

                <div class="field">
                  <label for="" class="label is-small">Amount to Donate</label>
                  <div class="control">
                    <input type="number" class="input is-small" placeholder="0.00" name="monetary_amount" required>
                  </div>
                </div>

                <button class="button is-info is-small" type="button" onclick="openModal()">Donate</button>

                <div class="modal" id="modal-1">
                  <div class="modal-background"></div>
                  <div class="modal-card">
                    <header class="modal-card-head">
                      <p class="modal-card-title">Monetary</p>
                      <button class="delete" aria-label="close" type="button" onclick="closeModal()"></button>
                    </header>
                    <section class="modal-card-body">
                      <p>Are you sure about this transaction details?</p>
                      <p><strong>Account Details: </strong></p>
                      <p id="details-display"></p>
                      <p><strong>Amount to Donate: </strong></p>
                      <p id="amount-display"></p>
                    </section>
                    <footer class="modal-card-foot">
                      <button class="button is-success" type="submit" name="monetary">Donate!</button>
                    </footer>
                  </div>
                </div>

              </form>

              <script>
                function openModal() {
                  const modal = document.getElementById(`modal-1`);
                  modal.classList.add("is-active");
                }

                function closeModal() {
                  const modal = document.getElementById(`modal-1`);
                  modal.classList.remove("is-active");
                }

                // Retrieve the input fields and the <p> elements
                const monetaryAmountInput = document.querySelector('input[name="monetary_amount"]');
                const monetaryDetailsInput = document.querySelector('input[name="monetary_details"]');
                const amountParagraph = document.getElementById('amount-display');
                const detailsParagraph = document.getElementById('details-display');

                // Add event listeners to the input fields
                monetaryAmountInput.addEventListener('input', updateAmount);
                monetaryDetailsInput.addEventListener('input', updateDetails);

                // Event listener callback functions
                function updateAmount() {
                  const monetaryAmountValue = monetaryAmountInput.value;
                  amountParagraph.textContent = monetaryAmountValue;
                }

                function updateDetails() {
                  const monetaryDetailsValue = monetaryDetailsInput.value;
                  detailsParagraph.textContent = monetaryDetailsValue;
                }

              </script>
            </div>

          </div>

          <div id="inkind-tab" class="content is-hidden">
            <div class="box">
              <h1 class="subtitle is-small">In-kind Donation</h1>
              <form action="../../lib/php/donor_donate.php?oid=<?php echo $org_id; ?>&eid=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">

                <div class="field is-horizontal">
                  <div class="field-body">

                    <div class="field">
                      <label for="" class="label is-small">Item Name</label>
                      <div class="control">
                        <input type="text" class="input is-small" name="inkind_name" required>
                      </div>
                    </div>

                    <div class="field">
                      <label for="" class="label is-small">Category</label>
                      <div class="control">
                        <div class="select is-small">
                          <select name="inkind_category" required>
                            <option value="">Select Category</option>
                            <option value="edible">Edible</option>
                            <option value="non-edible">Non-Edible</option>
                            <option value="others">Others</option>
                          </select>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="field">
                  <label for="" class="label is-small">Item Description</label>
                  <div class="control">
                    <textarea name="inkind_description" id="" cols="20" rows="5" class="textarea is-small" required></textarea>
                  </div>
                </div>

                <div class="field">
                  <div class="field-body">
                    <div class="field">
                      <label for="" class="label is-small">Quantity</label>
                      <div class="control">
                        <input type="number" class="input is-small" name="inkind_quantity" required>
                      </div>
                    </div>
                    <div class="field">
                      <!-- Pickup Location, Pickup Date -->
                      <label class="label is-small">Deliver Date</label>
                      <div class="control">
                        <input class="input is-small" type="date" name="inkind_date" min="<?php echo date("Y-m-d"); ?>" required>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Contact Person, Contact Number, Contact Email -->
                <div class="field">
                  <label class="label is-small">Images</label>
                  <div class="control">
                    <input class="input is-small" type="file" name="inkind_images[]" multiple accept=".png, .jpg, .jpeg">
                  </div>
                </div>

                <button class="button is-link is-small" type="submit" name="inkind">Donate</button>
              </form>
            </div>
          </div>

          <div id="both-tab" class="content is-hidden">
            <div class="box">
              <h1 class="subtitle is-small">Monetary & Inkind Donation</h1>
              <form action="../../lib/php/donor_donate.php?oid=<?php echo $org_id; ?>&eid=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">

                <div class="field">
                  <label for="" class="label is-small">Payment Method</label>
                  <div class="control">
                    <div class="select is-small">
                      <select name="mi_method" required>
                        <option value="">Select Payment Method</option>
                        <?php 
                        
                          $pay = $db->query("SELECT * FROM `tblpayments` WHERE event_id = '$event_id' AND org_id = '$org_id'");

                          if ($pay->num_rows > 0) {
                            while ($payr = mysqli_fetch_assoc($pay)) {
                        ?>
                        <option value="<?php echo $payr['payment_id']; ?>"><?php echo ucfirst($payr['method_type']); ?></option>
                        <?php
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="field">
                  <label for="" class="label is-small">Phone Number (GCash, Maya) / Email (Paypal)</label>
                  <div class="control">
                    <input type="text" class="input is-small" name="mi_details" required>
                  </div>
                </div>

                <div class="field">
                  <label for="" class="label is-small">Amount to Donate</label>
                  <div class="control">
                    <input type="number" class="input is-small" placeholder="0.00" name="mi_amount" required>
                  </div>
                </div>

                <hr>

                <div class="field is-horizontal">
                  <div class="field-body">

                    <div class="field">
                      <label for="" class="label is-small">Item Name</label>
                      <div class="control">
                        <input type="text" class="input is-small" name="mi_name" required>
                      </div>
                    </div>

                    <div class="field">
                      <label for="" class="label is-small">Category</label>
                      <div class="control">
                        <div class="select is-small">
                          <select name="mi_category" required>
                            <option value="">Select Category</option>
                            <option value="edible">Edible</option>
                            <option value="non-edible">Non-Edible</option>
                            <option value="others">Others</option>
                          </select>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="field">
                  <label for="" class="label is-small">Item Description</label>
                  <div class="control">
                    <textarea name="mi_desc" id="" cols="20" rows="5" class="textarea is-small" required></textarea>
                  </div>
                </div>

                <div class="field">
                  <div class="field-body">
                    <div class="field">
                      <label for="" class="label is-small">Quantity</label>
                      <div class="control">
                        <input type="number" class="input is-small" name="mi_quantity" required>
                      </div>
                    </div>
                    <div class="field">
                      <!-- Pickup Location, Pickup Date -->
                      <label class="label is-small">Deliver Date</label>
                      <div class="control">
                        <input class="input is-small" type="date" name="mi_date" required>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Contact Person, Contact Number, Contact Email -->
                <div class="field">
                  <label class="label is-small">Image</label>
                  <div class="control">
                    <input class="input is-small" type="file" name="mi_images[]" multiple accept=".png, .jpg, .jpeg">
                  </div>
                </div>

                <button class="button is-link is-small" type="button" onclick="openModall()">Donate</button>

                <div class="modal" id="modal-2">
                  <div class="modal-background"></div>
                  <div class="modal-card">
                    <header class="modal-card-head">
                      <p class="modal-card-title">Monetary</p>
                      <button class="delete" aria-label="close" type="button" onclick="closeModall()"></button>
                    </header>
                    <section class="modal-card-body">
                      <p>Are you sure about this transaction details for Monetary?</p>
                      <p><strong>Account Details: </strong></p>
                      <p id="mi_details-display"></p>
                      <p><strong>Amount to Donate: </strong></p>
                      <p id="mi_amount-display"></p>
                    </section>
                    <footer class="modal-card-foot">
                      <button class="button is-success" type="submit" name="both">Donate!</button>
                    </footer>
                  </div>
                </div>

              </form>

              <script>
                function openModall() {
                  const modal = document.getElementById(`modal-2`);
                  modal.classList.add("is-active");
                }

                function closeModall() {
                  const modal = document.getElementById(`modal-2`);
                  modal.classList.remove("is-active");
                }

                // Retrieve the input fields and the <p> elements
                const mi_monetaryAmountInput = document.querySelector('input[name="mi_amount"]');
                const mi_monetaryDetailsInput = document.querySelector('input[name="mi_details"]');
                const mi_amountParagraph = document.getElementById('mi_amount-display');
                const mi_detailsParagraph = document.getElementById('mi_details-display');

                // Add event listeners to the input fields
                mi_monetaryAmountInput.addEventListener('input', updateAmountt);
                mi_monetaryDetailsInput.addEventListener('input', updateDetailst);

                // Event listener callback functions
                function updateAmountt() {
                  const monetaryAmountValue = mi_monetaryAmountInput.value;
                  mi_amountParagraph.textContent = monetaryAmountValue;
                }

                function updateDetailst() {
                  const monetaryDetailsValue = mi_monetaryDetailsInput.value;
                  mi_detailsParagraph.textContent = monetaryDetailsValue;
                }

              </script>

            </div>
          </div>

          <div id="timeline-tab" class="content is-hidden">
            <div class="box">
              <h1 class="title"><?php echo $event_title; ?></h1>
              <p>
                <strong>Description: </strong> <br>
                <?php echo $event_desc; ?> <br> <br>
              </p>
            <?php
              if ($event_start_date != NULL) {
            ?>
              <p><strong>Start Date: </strong><?php echo $event_start_date; ?></p>
            <?php 
              }
              if ($event_end_date != NULL) {
            ?>
              <p><strong>End Date: </strong><?php echo $event_end_date; ?></p>
            <?php 
              }
              if ($target_inkind != 0) {
                $percentI = ($current_inkind / $target_inkind) * 100;
            ?>
              <label for="">Progress Donated Inkind: <strong><?php echo $percentI . "% (" . $current_inkind . " / " . $target_inkind . ")"; ?></strong></label>
              <progress class="progress is-info" value="<?php echo intval($percentI); ?>" max="100"></progress>
            <?php 
              }
              if ($target_funds != 0) {
                $percentM = ($current_funds / $target_funds) * 100;
            ?>
              <label for="">Progress Donated Monetary: <strong><?php echo $percentM . "% (₱" . $current_funds . " / ₱" . $target_funds . ")"; ?></strong></label>
              <progress class="progress is-success" value="<?php echo intval($percentM); ?>" max="100"></progress>
            <?php 
              }
            ?>
            </div>
            <hr>
            <div class="columns is-multiline">
              <?php
                $image = $db->query("SELECT image_data, image_type FROM `tblimages` WHERE table_id = '$event_id' AND category = 'event_image'");

                if ($image->num_rows > 0) {
                  while ($image_row = mysqli_fetch_assoc($image)) {
                    $imageData = $image_row['image_data'];
                    $imageType = $image_row['image_type'];
                    echo '<div class="column is-half">';
                    echo '<figure class="image is-square">';
                    echo '<img src="data:image;base64,' . $imageData . '" alt="Event Image">';
                    echo '</figure>';
                    echo '</div><br>';
                  }
                }
              ?>
            </div>
          </div>

          <div id="location-tab" class="content">
            <div class="box">
              <div id="map"></div>
            </div>

            <div id="popup" class="ol-popup">
              <div id="popup-content"></div>
            </div>

            <?php
              $address = $donor_address;

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
                          var distance = feature.get("distance");

                          var popupContent = document.getElementById("popup-content");
                          popupContent.innerHTML = "<strong>" + charityName + "</strong><br>" + charityAddress + "<br>Distance: " + distance + " km";

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

                      var yourMarker = new ol.Feature({
                          geometry: new ol.geom.Point(ol.proj.fromLonLat([longitude, latitude])),
                          charityName: "Your Address",
                          charityAddress: "' . $address . '",
                          distance: 0
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
                  </script>';

                  $geocode_url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($org_address);

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

                      $distance = calculateDistance($latitude, $longitude, $charity_latitude, $charity_longitude);

                      echo '<script>
                          var charityMarker = new ol.Feature({
                              geometry: new ol.geom.Point(ol.proj.fromLonLat([' . $charity_longitude . ', ' . $charity_latitude . '])),
                              charityName: "' . $org_name . '",
                              charityAddress: "' . $org_address . '",
                              distance: ' . $distance . '
                          });
                          markerLayer.getSource().addFeature(charityMarker);
                      </script>';

                  }
              }

            function calculateDistance($lat1, $lon1, $lat2, $lon2) {
              $R = 6371; // Radius of the Earth in kilometers
              $dLat = deg2rad($lat2 - $lat1); // Convert degrees to radians
              $dLon = deg2rad($lon2 - $lon1); // Convert degrees to radians
              $a =
                  sin($dLat / 2) * sin($dLat / 2) +
                  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                  sin($dLon / 2) * sin($dLon / 2);
              $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
              $distance = $R * $c;

              return round($distance, 2);
            }
          ?>
            <div class="box">
              <p>Distance from your address to <strong><?php echo $org_name; ?></strong>: <?php echo $distance; ?> km</p>
            </div>
          </div>

          <div id="aboutus-tab" class="content is-hidden">
            <div class="box">
              <h1 class="title">About Us</h1>
              <p><strong>Charity Name: </strong> <?php echo $org_name; ?></p>
              <p>
                <strong>Charity Description: </strong> <br>
                <?php echo $org_desc; ?>
              </p>
            </div>
          </div>
        </div>

      </div>

  </section>

  <script>
		document.addEventListener('DOMContentLoaded', function () {
			var tabs = document.querySelectorAll('.menu-list a');

			for (var i = 0; i < tabs.length; i++) {
				tabs[i].addEventListener('click', function (event) {
					event.preventDefault();

					var target = event.target.getAttribute('href').replace('#', '');

					var contents = document.querySelectorAll('.content');
					for (var j = 0; j < contents.length; j++) {
						contents[j].classList.add('is-hidden');
					}

					document.getElementById(target).classList.remove('is-hidden');

					var links = document.querySelectorAll('.menu-list a');
					for (var k = 0; k < links.length; k++) {
						links[k].classList.remove('is-active');
					}

					event.target.classList.add('is-active');
				});
			}
		});
	</script>

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