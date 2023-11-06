<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../lib/imgs/charitease_icon.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;700&display=swap">

  <style>
    body {
      font-family: 'Red Hat Display', sans-serif;
    }
  </style>
</head>

<body>
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

  <section class="section has-background-info is-fullheight" id="bg">
    <div class="hero-body">
      <div class="container">
        <div class="columns  is-vcentered">
          <div data-aos="fade-left" class="column
          is-10-mobile is-offset-1-mobile
          is-10-tablet is-offset-1-tablet
          is-4-desktop is-offset-1-desktop
          is-4-widescreen is-offset-1-widescreen
          is-4-fullhd is-offset-1-fullhd">
            <figure class="image is-square">
              <img src="https://png.pngtree.com/png-vector/20221226/ourmid/pngtree-donation-box-and-charity-concept-png-image_6538298.png">
            </figure>
          </div>
          <div data-aos="fade-down" class="column
          is-10-mobile is-offset-1-mobile
          is-10-tablet is-offset-1-tablet
          is-5-desktop is-offset-1-desktop
          is-5-widescreen is-offset-1-widescreen
          is-5-fullhd is-offset-1-fullhd">
            <h1 class="titled title is-1 mb-6 has-text-white">
              CharitEase
            </h1>
            <h2 class="subtitled subtitle has-text-white">
            The CharitEase: A Digital Fundraising System For Charitable Organizations With 2D Mapping 
            is a web-based that will manage and handle the in and out of 
            the donation transaction for fundraising in region 3. It will assist those looking 
            for organizations to donate money or items in order to support others.
            </h2>            
          </div>
        </div>
        <div class="section-heading">
          <h3 class="title is-2 has-text-white">Features</h3>
          <h4 class="subtitle is-5 has-text-white is-italic">- "Features of CharitEase"</h4> <br>
        </div>
        <div class="container">
          <div class="columns">
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Account Registration</h4>
                  The system will required account registration for both 
                  donors and charity organizations.
                </div>
              </div>
            </div>
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Different Categorizations</h4>
                  The system will include different categorizations of 
                  charitable organizations.
                </div>
              </div>
            </div>
          </div>

          <div class="columns">
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Preferred Charitable Organizations</h4>
                  The donors will have the ability to choose their preferred 
                  charitable organizations.
                </div>
              </div>
            </div>
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Legitimate Charity</h4>
                  The charitable organization should present a permit, so the donors 
                  can assure if the charitable organization is a legitimate charity.
                </div>
              </div>
            </div>
          </div>

          <div class="columns">
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Direct Messaging</h4>
                  The system will allow the users and the charitable organization 
                  to communicate through direct messaging.
                </div>
              </div>
            </div>
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Review and Rating</h4>
                  Donors can also rate, write a review according to their 
                  experience with the charity organization.
                </div>
              </div>
            </div>
          </div>

          <div class="columns">
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Timeline and Background</h4>
                  Charitable organization can create their own timeline where 
                  they can describe their way of distributing the 
                  allocated funds and the background of their organization. 
                </div>
              </div>
            </div>
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">2D Mapping</h4>
                  The system will provide a 2D map to locate the 
                  locations of charitable organization.
                </div>
              </div>
            </div>
          </div>

          <div class="columns">
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Donation Method</h4>
                  The donors can donate in-kind or monetarily, If the user 
                  is donating in-kind they can use the messaging feature 
                  to cooperate with the desired charity and, 
                  if donating monetarily, the system will have an online transaction.
                </div>
              </div>
            </div>
            <div class="column">
              <div class="box">
                <div class="content">
                  <h4 class="title is-6">Charity Events</h4>
                  The system will allow the charitable organization 
                  to announce charity events.
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

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
  
</body>
</html>