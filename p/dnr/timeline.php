<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../../lib/imgs/charitease_icon.png">
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

  <?php 
  
    include '../../lib/database.php';

    $db = new Database();
    $conn = $db->connect();

    $user = $_SESSION['user'];
    $donor_id = $_SESSION['id'];
    $org_id = $_GET['oid'];

    $tlQuery = "SELECT * FROM `tblorgtimeline` WHERE org_id = '$org_id'";
    $tlR = mysqli_query($conn, $tlQuery);

    $orgQ = "SELECT * FROM `tblorgs` WHERE org_id = '$org_id'";
    $orgR = mysqli_query($conn, $orgQ);

    if ($orgR->num_rows > 0) {
      $org = mysqli_fetch_assoc($orgR);
    }
    
  ?>

  <section class="hero is-info">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Charity Post Timeline</h1>
				<h2 class="subtitle"><strong>Charity Name: </strong><?php echo $org['org_name']; ?></h2>
			</div>
		</div>
	</section>

  <section class="section">
    <div class="container">
      <h1 class="title has-text-centered">Charity Post Timelines</h1>
      <div class="columns is-multiline">
        <?php 
          if ($tlR->num_rows > 0) {
            while ($timeline = mysqli_fetch_assoc($tlR)) {
              $event_id = $timeline['event_id'];
              $event_title = $timeline['event_title'];
              $event_type = $timeline['event_type'];
              $event_desc = $timeline['event_description'];
              $event_start_date = $timeline['event_start_date'];
              $event_end_date = $timeline['event_end_date'];
              $current_inkind = $timeline['current_inkind'];
              $target_inkind = $timeline['target_inkind'];
              $current_funds = $timeline['current_funds'];
              $target_funds = $timeline['target_funds'];
              $timestamp = $timeline['timestamp'];
              $status = $timeline['status'];

              $et = $event_type == "blog" ? "Announcement" : "Charity Event";
              $tag = $event_type == "blog" ? "is-link" : "is-info";
        ?>
        <div class="column is-half">
          <div class="box">
            <h1 class="title"><?php echo $event_title; ?></h1>
            <p><strong>Post Type: </strong><span class="tag <?php echo $tag; ?>"><?php echo $et; ?></span></p>
            <p><strong>Description: </strong> <br> <?php echo $event_desc; ?></p> <br>

            <?php 
              if ($event_type == "event") {
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
              }
            ?>
            <div class="columns is-multiline">
              <?php
                $imageG = "SELECT image_data, image_type FROM `tblimages` WHERE table_id = '$event_id' AND category = 'event_image'";
                $imageR = mysqli_query($conn, $imageG);

                if (mysqli_num_rows($imageR) > 0) {
                  while ($imageRow = mysqli_fetch_assoc($imageR)) {
                    $imageData = $imageRow['image_data'];
                    $imageType = $imageRow['image_type'];

                    echo '<div class="column is-half">';
                    echo '<figure class="image is-square">';
                    echo '<img src="data:image;base64,' . $imageData . '" alt="Event Image">';
                    echo '</figure>';
                    echo '</div><br>';
                  }
                }
              ?>
            </div>
            <?php if ($event_type == "event") { ?>
              <a href="donation.php?oid=<?php echo $org_id; ?>&tid=<?php echo $event_id; ?>" class="button is-info">Donate Now!</a>
            <?php } ?>
          </div>
        </div>
        <?php 
            }
          } else {
        ?>
          <p class="title has-text-centered">Stay tuned for timeline updates</p>
        <?php 
          }
        ?>
      </div>
    </div>
  </section>


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

</body>
</html>
