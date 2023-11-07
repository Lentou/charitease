<?php if (!isset($_SESSION)) session_start(); 

include '../lib/config.php';
include '../lib/database.php';

?>
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
  <?php
    if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'donor')) {
      header('Location: ../reg/login.php');
      exit;
    }
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

  <?php 

    $db = new Database();
    $conn = $db->connect();

    $user = $_SESSION['user'];
    $donor_id = $_SESSION['id'];
    $org_id = $_GET['oid'];

    $charity_posts = [];
    $charity_events = [];

    $announce = $db->query("SELECT * FROM `tblevents` WHERE org_id = '$org_id' AND event_type = 'a' AND is_approved = '1'");
    $events = $db->query("SELECT * FROM `tblevents` WHERE org_id = '$org_id' AND event_type = 'e' ORDER BY event_start_date ASC");
    //$announce = $db->query("SELECT * FROM `tblorgtimeline` WHERE org_id = '$org_id' AND event_type = 'blog'");
    //$events = $db->query("SELECT * FROM `tblorgtimeline` WHERE org_id = '$org_id' AND event_type = 'event' ORDER BY event_start_date ASC");

    //$orgs = $db->query("SELECT * FROM `tblorgs` WHERE org_id = '$org_id'");
    $orgs = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$org_id'");
    if ($orgs) {
      $org = $orgs->fetch_assoc();
    }

    if ($announce) {
      while ($announcement = $announce->fetch_assoc()) {
        $charity_posts[] = $announcement;
      }
    }

    if ($events) {
      while ($event = $events->fetch_assoc()) {
        $charity_events[] = $event;
      }
    }
    
  ?>

  <section class="hero is-info">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Charity Post Timeline</h1>
				<h2 class="subtitle"><strong>Charity Name: </strong><?= $org['client_name']; ?></h2>
        <a href="donate.php" class="button is-pulled-left is-link is-small">Back</a>
			</div>
		</div>
	</section>

  <section class="section">
    <div class="tabs">
      <ul>
        <li id="tab1" class="is-active"><a>Announcements</a></li>
        <li id="tab2"><a>Charity Events</a></li>
        <li id="tab3"><a>Permits</a></li>
        <li id="tab4"><a>About Us</a></li>
      </ul>
    </div>
    <div id="content">

      <div id="content-tab1" class="tab-content">
        <h1 class="h1">Charity Announcements of <strong><?= $org['client_name']; ?></strong></h1> <br>
        <div class="columns is-multiline">
        <?php
          foreach ($charity_posts as $posts) {
            $event_id = $posts['event_id'];
            $event_title = $posts['event_title'];
            $event_desc = $posts['event_description'];
            $event_type = $posts['event_type'];
            $status = $posts['event_status'];

            $et = $event_type == "a" ? "Announcement" : "Charity Event";
            $tag = $event_type == "a" ? "is-link" : "is-info";
        ?>
        <div class="column is-half">
          <div class="box">
            <h1 class="title"><?= $event_title; ?></h1>
            <p><strong>Post Type: </strong><span class="tag <?= $tag; ?>"><?= $et; ?></span></p>
            <p><strong>Description: </strong> <br> <?= $event_desc; ?></p> <br>
            <div class="columns is-multiline">
              <?php
                $imageG = "SELECT image_data FROM `tblimages` WHERE event_id = '$event_id' AND client_id = '$org_id' AND category = 'event_image'";
                $imageR = mysqli_query($conn, $imageG);

                if ($imageR->num_rows > 0) {
                  while ($imageRow = $imageR->fetch_assoc()) {
                    $imageData = $imageRow['image_data'];

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
        </div>
        <?php 
          }
        ?>
        </div>
      </div>

      <div id="content-tab2" class="tab-content is-hidden">
        <h1 class="h1">Charity Events of <strong><?= $org['client_name']; ?></strong></h1> <br>
        <div class="columns is-multiline">
        <?php
          foreach ($charity_events as $events) {
            $event_id = $events['event_id'];
            $event_title = $events['event_title'];
            $event_desc = $events['event_description'];
            $event_type = $events['event_type'];
            $event_start_date = $events['event_start_date'];
            $event_end_date = $events['event_end_date'];

            $current_inkind = 0;
            $target_inkind = 0;
            $current_funds = 0;
            $target_funds = 0;

            $collection = $db->query("SELECT * FROM `tblcollections` WHERE event_id = '$event_id'");
            if ($collection) {
              $collect = $collection->fetch_assoc();
              $current_inkind = $collect['current_inkind'];
              $target_inkind = $collect['target_inkind'];
              $current_funds = $collect['current_funds'];
              $target_funds = $collect['target_funds'];
            }
            
            $timestamp = $events['post_date'];
            $status = $events['event_status'];

            $et = $event_type == "a" ? "Announcement" : "Charity Event";
            $tag = $event_type == "a" ? "is-link" : "is-info";

            $start = new DateTime($event_start_date);
            $end = new DateTime($event_end_date);
            $currentDate = new DateTime();
            $st = '';
            $tag_st = '';

            $startFormatted = $start->format('F j, Y');
            $endFormatted = $end->format('F j, Y');
            $interval = $start->diff($end);
            $daysCount = $interval->days + 1;

            if ($start > $currentDate) {
                $st = 'Planned';
                $tag_st = 'is-warning';
            } elseif ($start <= $currentDate && $end >= $currentDate) {
                $st = 'Ongoing';
                $tag_st = 'is-success';
            } elseif ($start < $currentDate && $end < $currentDate) {
                $st = 'Ended';
                $tag_st = 'is-danger';
            }

        ?>
        <div class="column is-half">
          <div class="box">
            <h1 class="title"><?php echo $event_title; ?></h1>
            <p><strong>Post Type: </strong><span class="tag <?= $tag; ?>"><?= $et; ?></span></p>
            <p><strong>Description: </strong> <br> <?= $event_desc; ?></p> <br>
            <p><strong>Status: </strong><span class="tag <?= $tag_st; ?>"><?= $st; ?></span></p> <br>
            <?php 
              if ($event_type == "e") {
                if ($event_start_date != NULL) {
            ?>
              <p><strong>Start Date: </strong><?= $startFormatted; ?></p>
            <?php 
                }
                if ($event_end_date != NULL) {
            ?>
              <p><strong>End Date: </strong><?= $endFormatted; ?></p>
            <?php 
                }
                if ($event_start_date != NULL && $event_end_date != NULL) {
            ?>
            <p><strong>Duration: </strong><?= $daysCount; ?> day(s)</p>
            <?php 
                }
                if ($target_inkind != 0 && $st == 'Ongoing' || $target_inkind != 0 && $st == 'Ended') {
                  $percentI = ($current_inkind / $target_inkind) * 100;
            ?>
              <label for="">Progress Donated Inkind: <strong><?= $percentI . "% (" . $current_inkind . " / " . $target_inkind . ")"; ?></strong></label>
              <progress class="progress is-info" value="<?= intval($percentI); ?>" max="100"></progress>
            <?php 
                }
                if ($target_funds != 0 && $st == 'Ongoing' || $target_funds != 0 && $st == 'Ended') {
                  $percentM = ($current_funds / $target_funds) * 100;
            ?>
              <label for="">Progress Donated Monetary: <strong><?= $percentM . "% (₱" . $current_funds . " / ₱" . $target_funds . ")"; ?></strong></label>
              <progress class="progress is-success" value="<?= intval($percentM); ?>" max="100"></progress>
            <?php 
                }
              }
            ?>
            <div class="columns is-multiline">
              <?php
                $imageG = "SELECT image_data FROM `tblimages` WHERE event_id = '$event_id' AND client_id = '$org_id' AND category = 'event_image'";
                $imageR = $db->query($imageG);

                if ($imageR->num_rows > 0) {
                  while ($imageRow = $imageR->fetch_assoc()) {
                    $imageData = $imageRow['image_data'];

                    echo '<div class="column is-half">';
                    echo '<figure class="image is-square">';
                    echo '<img src="data:image;base64,' . $imageData . '" alt="Event Image">';
                    echo '</figure>';
                    echo '</div><br>';
                  }
                }
              ?>
            </div>
            <?php if (($target_funds != 0 || $target_inkind != 0) && $st == 'Ongoing') { ?>
              <a href="contribute.php?oid=<?= $org_id; ?>&eid=<?= $event_id; ?>" class="button is-info">Donate Now!</a>
            <?php } else echo "<br>"; ?>
          </div>
        </div>
        <?php 
          }
        ?>
        </div>
      </div>

      <div id="content-tab3" class="tab-content is-hidden">
        <h1 class="h1">Charity Registered Permits of <strong><?= $org['client_name']; ?></strong></h1> <br>
        <div class="columns is-multiline">
        <?php
          $permits = $db->query("SELECT * FROM `tblimages` WHERE client_id = '$org_id' AND category = 'permit'");

          if ($permits->num_rows > 0) {
            while ($permit = $permits->fetch_assoc()) {
              $imageData = $permit['image_data'];
              
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

      <div id="content-tab4" class="tab-content is-hidden">
        <h1 class="h1"><strong>About Us</strong></h1>
        <div class="block">
          <p><?php echo $org['client_bio']; ?></p>
        </div>
      </div>
    </div>
  </section>

  <script>
    const tabs = document.querySelectorAll('.tabs li');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(tab => tab.classList.remove('is-active'));

        tab.classList.add('is-active');

        contents.forEach(content => content.classList.add('is-hidden'));

        const tabId = tab.getAttribute('id');
        const contentId = `content-${tabId}`;
        const content = document.getElementById(contentId);
        content.classList.remove('is-hidden');
      });
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

</body>
</html>
