<?php if (!isset($_SESSION)) session_start(); ?>
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

  <!-- CONTENT HERE! -->
  <section class="section has-background-info">
    <div class="container">
      <h1 class="title has-text-centered has-text-white">Charitable Organizations</h1>
      <hr>
      <div class="columns is-multiline">
        <?php 
          // foreach card as one
          // Connect to database
          include '../lib/database.php';

		      $db = new Database('charitease');
		      $conn = $db->connect();
          $donor_id = $_SESSION['id'];
  
          // Select all organizations from table
          $sql = "SELECT * FROM tblorgs WHERE is_approved = '1'";
          $result = mysqli_query($conn, $sql);
  
          // Loop through organizations and create a card for each one
          while ($row = $result->fetch_assoc()) {
            $org_id = $row['org_id'];
            $org_name = $row['org_name'];
            $org_person_name = $row['org_person_name'];
            $org_phone = $row['org_phone'];
            $org_address = $row['org_address'];
            $org_description = $row['org_description'];
            $is_approved = $row['is_approved'];
            $date_founded = $row['date_founded'];
            $date_approved = $row['date_approved'];

            $notifQuery = "SELECT COUNT(*) AS notifCount FROM `tblconvo` WHERE is_read = 0 AND initiate_by = 'charity' AND org_id = '$org_id' AND donor_id = '$donor_id'";
            $notifStmt = mysqli_query($conn, $notifQuery);
            $notifRow = mysqli_fetch_assoc($notifStmt);
            $notifCount = $notifRow['notifCount'];

            $org_type = $row['org_type'];
            $color_tag = match($org_type) {
              "environment" => "is-success",
              "health" => "is-danger",
              "religious" => "is-warning",
              "education" => "is-info"
            };

            $type_tag = match($org_type) {
              "environment" => "Environment Charity",
              "health" => "Health Charity",
              "religious" => "Religious Charity",
              "education" => "Education Charity"
            };
        ?>

        <div class="column is-one-third">
          <div class="card">
            <div class="card-content">
              <div class="media">
                <div class="media-left">
                  <figure class="image is-48x48">
                    <?php
                    $get_pic = $db->query("SELECT * FROM `tblimages` WHERE table_id = '$org_id' AND category = 'org_icon' AND permit_type = 'icon'");
                      if ($get_pic->num_rows > 0) {
                        $gett = $get_pic->fetch_assoc();
                        $imageData = $gett['image_data'];
                    ?>
                      <img src="data:image;base64,<?php echo $imageData ?>" alt="Event Image">
                    <?php 
                      } else {
                    ?>
                      <img src="https://bulma.io/images/placeholders/96x96.png" alt="<?php echo $org_name . ' logo';?>">
                    <?php 
                      }
                    ?>
                  </figure>
                </div>
                <div class="media-content">
                  <span class="tag <?php echo $color_tag; ?>"><?php echo $type_tag; ?></span> 
                  <p class="title is-5"><?php echo $org_name; ?></p>
                  <p class="subtitle is-6"><?php echo $org_address; ?></p>
                </div>
              </div>
              <div class="content">
                <div class="buttons">
                  <a href="timeline.php?oid=<?php echo $org_id; ?>" class="button is-small is-link">View Timeline</a>
                  <a href="messenger.php?oid=<?php echo $org_id; ?>" class="button is-small is-link">
                    <?php if ($notifCount > 0) { ?>
                      Direct Message&nbsp;<span class="tag is-danger"><?php echo $notifCount; ?></span>
                    <?php } else { ?>
                      Direct Message
                    <?php } ?>
                  </a>
                  <a href="rating.php?oid=<?php echo $org_id; ?>" class="button is-small is-link">View Reviews</a>
                </div>
                
              </div>
            </div>
          </div>
        </div>

        <?php
          // end of page
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
