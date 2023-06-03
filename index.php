<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="lib/imgs/charitease_icon.png">

  <style>
  #bg {
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: 100% 100%;
  }
</style>
  <script>
    window.onload = function() {
      // Paste the code snippet here
      const bgImages = [
        'lib/imgs/image1.jpg',
        'lib/imgs/image2.png',
        'lib/imgs/image3.png',
        'lib/imgs/image4.png',
        'lib/imgs/image5.jpg',
        'lib/imgs/image6.jpg'
      ];
      const randomBgImage = bgImages[Math.floor(Math.random() * bgImages.length)];
      document.getElementById('bg').style.backgroundImage = `url(${randomBgImage})`;
    };
  </script>

    <!-- Include Sweet Alert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">

    <!-- Include Sweet Alert JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>

<body>
  <!-- HEADER -->
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <div class="navbar-start">
        <a href="" class="navbar-item">
          <img src="lib/imgs/charitease_icon.png" alt="Logo">
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
        <a href="index.php" class="navbar-item">Home</a>
        <div class="navbar-item has-dropdown is-hoverable">
          <a href="" class="navbar-link">CharitEase</a>
          <div class="navbar-dropdown">
            <a href="p/com/features.php" class="navbar-item">Features</a>
            <a href="p/com/company.php" class="navbar-item">Company</a>
            <a href="p/com/about.php" class="navbar-item">About</a>
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
                  <a href="p/dnr/donate.php" class="navbar-item">Donate</a>
                  <a href="p/dnr/dashboard.php" class="navbar-item">Dashboard</a>
                </div>
              </div>
        <?php 
            } else if ($_SESSION['user'] == 'charity') {
        ?>
          <!-- ORGANIZATION BUTTON -->
              <div class="navbar-item has-dropdown is-hoverable">
                <a href="" class="navbar-link">Charity</a>
                <div class="navbar-dropdown">
                  <a href="p/org/donors.php" class="navbar-item">Donors</a>
                  <a href="p/org/dashboard.php" class="navbar-item">Dashboard</a>
                </div>
              </div>
        <?php 
            } else if ($_SESSION['user'] == 'admin') {
        ?>
          <!-- ADMIN BUTTONS -->
          <a href="p/adm/dashboard.php" class="navbar-item">Dashboard</a>
        <?php 
            }
        ?>
            <a href="p/reg/logout.php" class="navbar-item">Logout</a>
        <?php 
          } else {
        ?>
            <a href="p/reg/register.php" class="navbar-item">Register</a>
            <a href="p/reg/login.php" class="navbar-item">Login</a>
        <?php } ?>
        <span class="navbar-item"></span>
        <span class="navbar-item"></span>
      </div>
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
            <?php
              if (isset($_SESSION['user'])) {
                if ($_SESSION['user'] == 'donor') {
            ?>
              <a href="p/dnr/maps.php" class="button is-info">Check Nearest Charitable Organization</a>
            <?php 
                }
              }
            ?>
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
            <li class="mr-4"><a href="p/com/about.php" class="button is-white">About</a></li>
            <li class="mr-4"><a href="p/com/company.php" class="button is-white">Company</a></li>
            <li class="mr-4"><a href="p/com/features.php" class="button is-white">Features</a></li>
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

  <?php include 'lib/alert.php'; ?>

</body>
</html>