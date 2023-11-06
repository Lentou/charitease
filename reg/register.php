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
    </style>

    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>-->

    <style>
        #bg {
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }
    </style>
    <script>
        window.onload = function() {
            const bgImages = [
                '../lib/imgs/image1.jpg',
                '../lib/imgs/image2.png',
                '../lib/imgs/image3.png',
                '../lib/imgs/image4.png',
                '../lib/imgs/image5.jpg',
                '../lib/imgs/image6.jpg'
            ];
            const randomBgImage = bgImages[Math.floor(Math.random() * bgImages.length)];
            document.getElementById('bg').style.backgroundImage = `url(${randomBgImage})`;
        };
    </script>
</head>
<body>
    <?php
        if (isset($_SESSION['user'])) {
            header('Location: ../index.php');
            exit;
        }
    ?>

    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <div class="navbar-start">
                <a href="" class="navbar-item">
                <img src="../lib/imgs/charitease_icon.png" alt="Logo">
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

    <section id="bg" class="section">
        <div class="container">
            <form action="action/a.register.php" method="post" enctype="multipart/form-data">
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="box">

                            <div class="field">
                                <div class="columns">
                                    <div class="column">
                                        <label for="" class="label">Account Type</label>
                                        <div class="control">
                                            <label for="" class="radio">
                                                <input type="radio" name="account_type" value="c" required> Charity
                                            </label>
                                            <label for="" class="radio">
                                                <input type="radio" name="account_type" value="d" required> Donor
                                            </label>
                                        </div>
                                    </div>
                                    <div class="column is-hidden" id="column_user_type">
                                        <label for="" class="label">User Type</label>
                                        <div class="control">
                                            <label for="" class="radio">
                                                <input type="radio" name="client_user_type" value="i"> Individual
                                            </label>
                                            <label for="" class="radio">
                                                <input type="radio" name="client_user_type" value="o"> Organization
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="field">
                                <div class="columns">
                                    <div class="column">
                                        <label for="" class="label">Name</label>
                                        <div class="control">
                                            <input class="input" type="text" name="client_name" placeholder="Name of Account" required>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <label class="label">Contact Person Name</label>
                                        <div class="control">
                                            <input class="input" type="text" name="client_contact_name" placeholder="Person Name" required>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="field">
                                <div class="columns">
                                    <div class="column">
                                        <label for="" class="label">Birthday</label>
                                        <div class="control">
                                            <input type="date" class="input" placeholder="birthday" name="bday" max="<?php echo date("Y-m-d"); ?>" required>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <label for="" class="label">Gender</label>
                                        <div class="control">
                                            <label for="" class="radio">
                                                <input type="radio" name="gender" value="m" required> Male
                                            </label>
                                            <label for="" class="radio">
                                                <input type="radio" name="gender" value="f" required> Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <div class="columns">
                                    <div class="column">
                                        <label class="label">Email Address</label>
                                        <div class="control">
                                            <input class="input" type="email" name="email" placeholder="Email Address" required>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <label class="label">Contact Number</label>
                                        <div class="control">
                                            <input class="input" type="text" name="client_phone" placeholder="Contact Number" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label for="donorPermits" class="label" id="permit_label">Registration Permits</label>
                                <div class="control">
                                    <input type="file" class="input" placeholder="Registration Permits or Valid IDs" name="client_permit[]" multiple accept=".png, .jpg, .jpeg" required>
                                </div>
                            </div>

                            <div class="field is-hidden" id="column_org">
                                <div class="columns row-one">
                                    <div class="column">
                                        <label for="" class="label">Founding Date</label>
                                        <div class="control">
                                            <input type="date" class="input" name="date_founded" placeholder="Founding Date" max="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                    </div>
                                    <div class="column">
                                        <label for="" class="label">Charitable Organization Type</label>
                                        <div class="control">
                                            <div class="select">
                                                <select name="cliet_org_type" id="">
                                                    <option value="en">Environmental Charity</option>
                                                    <option value="he">Health Charity</option>
                                                    <option value="re">Religious Charity</option>
                                                    <option value="ed">Education Charity</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="column is-half">
                        <div class="box">

                            <div class="field">
                                <label class="label">Address</label>
                                <div class="control">
                                    <input class="input" type="text" name="client_address" placeholder="Address" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Mark Address</label>
                                <input type="hidden" name="client_lat" id="lat" value="">
                                <input type="hidden" name="client_lng" id="lng" value="">
                                <div id="map" style="height: 200px;"></div>
                            </div>

                            <div class="field">
                                <div class="columns">
                                    <div class="column">
                                        <label for="" class="label">Password</label>
                                        <div class="control">
                                        <input type="password" class="input" name="password" placeholder="Password" required>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <label for="" class="label">Confirm Password</label>
                                        <div class="control">
                                        <input type="password" class="input" name="confirm_password" placeholder="Confirm Password" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <button class="button is-info" type="submit">Register</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>

    <script>
        const accountTypeRadios = document.querySelectorAll('input[name="account_type"]');
        const columnUserType = document.getElementById('column_user_type');
        const columnOrg = document.getElementById('column_org');
        const userTypeRadios = document.getElementsByName('client_user_type');
        const labelPermit = document.getElementById('permit_label');

        accountTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'c') {
                    labelPermit.innerHTML = 'Registration Permits';
                    columnUserType.classList.add('is-hidden');
                    if (columnOrg.classList.contains('is-hidden')) {
                        columnOrg.classList.remove('is-hidden');
                    }
                } else {
                    columnUserType.classList.remove('is-hidden');
                    var selectedValue = '';
                    for (let i = 0; i < userTypeRadios.length; i++) {
                        if (userTypeRadios[i].checked) {
                            selectedValue = userTypeRadios[i].value;
                            break;
                        }
                    }
                    if (selectedValue === 'i') {
                        columnOrg.classList.add('is-hidden');
                        labelPermit.innerHTML = 'Valid Ids';
                    }
                }
            });
        });

        userTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'i') {
                    labelPermit.innerHTML = 'Valid Ids';
                    columnOrg.classList.add('is-hidden');
                } else {
                    labelPermit.innerHTML = 'Registration Permits';
                    columnOrg.classList.remove('is-hidden');
                }
            });
        });
    </script>

    <script>
        var map = L.map('map').setView([14.6760, 120.5365], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var marker = null; // Store the current marker, initially set to null

        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Remove the existing marker if it exists
            if (marker) {
                map.removeLayer(marker);
            }

            // Create a new colored circle marker and add it to the map
            marker = L.circle([lat, lng], {
                radius: 5, // Adjust the radius as needed
                fillColor: 'green', // Color of the circle
                fillOpacity: 0.7, // Opacity of the circle
                stroke: false, // No border
            }).addTo(map);

            // Add a popup to the marker
            marker.bindPopup("Coordinates: <br>latitude: " + lat + "<br>longitude: " + lng).openPopup();

            document.getElementById("lat").value = lat;
            document.getElementById("lng").value = lng;
        });
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