<?php if (!isset($_SESSION)) session_start(); 

if (isset($_GET['cid'], $_GET['ctype'], $_GET['cname'])) {

  $account_type = $_GET['ctype'];
  $new_account_type = ($account_type == "c") ? "charity" : (($account_type == "d") ? "donor" : "admin");

  $_SESSION['name'] = $_GET['cname'];
  $_SESSION['user'] = $new_account_type;
  $_SESSION['id'] = $_GET['cid'];
  $_SESSION['status'] = "Login Success";
  $_SESSION['status_text'] = "Successfully Logged-In";
  $_SESSION['status_code'] = "success";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CharitEase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="lib/imgs/charitease_icon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;700&display=swap">

    <style>
        #bg {
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }

        body {
          font-family: 'Red Hat Display', sans-serif;
        }
    </style>
    <script>
        window.onload = function() {
            const bgImages = [
                'lib/imgs/image1.webp',
                'lib/imgs/image2.webp',
                'lib/imgs/image3.webp',
                'lib/imgs/image4.webp',
                'lib/imgs/image5.webp',
                'lib/imgs/image6.webp'
            ];
            const randomBgImage = bgImages[Math.floor(Math.random() * bgImages.length)];
            document.getElementById('bg').style.backgroundImage = `url(${randomBgImage})`;
        };
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>

<body>
    <!-- HEADER -->
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
        <div class="navbar-start">
            <a href="" class="navbar-item">
            <img src="lib/imgs/charitease_icon.png" alt="Logo">
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
            <a href="index.php" class="navbar-item"><span class="material-symbols-outlined">home</span>Home</a>
            <?php
            if (isset($_SESSION['user'])) {
                if ($_SESSION['user'] == 'donor') {
            ?>
                <a href="dnr/donate.php" class="navbar-item"><span class="material-symbols-outlined">real_estate_agent</span>List</a>
                <a href="dnr/maps.php" class="navbar-item"><span class="material-symbols-outlined">home_pin</span>Map</a>
                <a href="#" class="navbar-item"><span class="material-symbols-outlined">chat</span>Chat</a>
                <a href="dnr/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
            <?php 
                } else if ($_SESSION['user'] == 'charity') {
            ?>
            <!-- ORGANIZATION BUTTON -->
                <a href="org/donors.php" class="navbar-item"><span class="material-symbols-outlined">chat</span>Chat</a>
                <a href="org/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
            <?php 
                } else if ($_SESSION['user'] == 'admin') {
            ?>
            <!-- ADMIN BUTTONS -->
            <a href="adm/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
            <?php 
                }
            ?>
              <div class="navbar-item has-dropdown is-hoverable">
                <a href="" class="navbar-link"><span class="material-symbols-outlined">account_circle</span><?= $_SESSION['name']; ?></a>
                <div class="navbar-dropdown">
                  <a href="reg/settings.php" class="navbar-item"><span class="material-symbols-outlined">manage_accounts</span>Settings</a>
                  <a href="reg/logout.php" class="navbar-item"><span class="material-symbols-outlined">logout</span>Logout</a>
                </div>
              </div>
            <?php 
              } else {
            ?>
                <a href="reg/register.php" class="navbar-item"><span class="material-symbols-outlined">how_to_reg</span>Register</a>
                <a href="reg/login.php" class="navbar-item"><span class="material-symbols-outlined">login</span>Login</a>
            <?php } ?>
            <span class="navbar-item"></span>
            <span class="navbar-item"></span>
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

  <section class="hero is-medium has-text-centered" id="bg">
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div data-aos="zoom-in-up" class="column is-8">
            <h1 class="title is-1 mb-6 has-text-white">
              Charity <span id="typewriter"></span>
            </h1>
            <h2 class="subtitle has-text-white">
                A small donation can make a huge difference.
            </h2>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/TypewriterJS/2.13.1/core.min.js"></script>
  <script>
    new Typewriter('#typewriter', {
      strings: ['is the virtue of the heart', 'begins at home', 'sees the need', 'is act of kindness'],
      autoStart: true,
      loop: true,
    });
  </script>
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init({
      once: true
    });
  </script>

  <section class="section">
    <div class="container">
      <h1 class="title">
        CharitEase
      </h1>
      <p class="subtitle">
        <strong>CharitEase</strong> was built by young people which is the reason why every person who needs help has hope.
      </p>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="section">
    <div class="container">
      <div class="pb-5 is-flex is-flex-wrap-wrap is-justify-content-between is-align-items-center">
        <div class="mr-auto mb-1">
          <a href="#" class="is-inline-block">
            <img src="lib/imgs/charitease_icon.png" alt="" class="image is-64x64">
          </a>
        </div>
        <div>
          <ul class="is-flex is-flex-wrap-wrap is-align-items-center is-justify-content-center">
            <li class="mr-4"><a href="com/about.php" class="button is-white">About</a></li>
            <li class="mr-4"><a href="com/company.php" class="button is-white">Company</a></li>
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

  <?php include 'lib/alert.php'; ?>

</body>
</html>