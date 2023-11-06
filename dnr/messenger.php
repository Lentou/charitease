<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="icon" href="../lib/imgs/charitease_icon.png">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;700&display=swap">

  <style>
    body {
      font-family: 'Red Hat Display', sans-serif;
    }
  </style>

<style>
    .conversation {
      max-height: 400px;
      overflow-y: auto;
    }

    .conversation-bubble {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      margin-bottom: 10px;
      max-height: 100px;
      overflow-y: auto;
    }

    .conversation-bubble p {
      margin: 5px;
      padding: 10px;
      border-radius: 20px;
    }

    .conversation-bubble .timestamp {
      font-size: 0.8rem;
      color: #999;
      margin-top: 5px;
    }

    .incoming p {
      background-color: #f5f5f5;
      color: #333;
      align-self: flex-start;
    }

    .outgoing p {
      background-color: #007bff;
      color: #fff;
      align-self: flex-end;
    }

    .conversation-form {
      margin-top: 20px;
    }

    .icon {
      margin-right: 5px;
    }
  </style>
</head>

<body>
  <?php
    session_start();
    if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'donor')) {
      header('Location: ../reg/login.php');
      exit;
    }

    if (!isset($_GET['oid'])) {
      header('Location: ../index.php');
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

  <?php 

    include '../lib/database.php';

    $db = new Database('charitease');
    $conn = $db->connect();

    $org_id = $_GET['oid'];

    $id = $_SESSION['id'];
    $user = $_SESSION['user'];

    $query = "SELECT * FROM `tblconvo` WHERE donor_id = '$id' AND org_id = '$org_id' ORDER BY timestamp ASC";
    $result = mysqli_query($conn, $query);

    $messages = [];

    while ($row = mysqli_fetch_assoc($result)) {
      $messages[] = $row;
    }

    foreach ($messages as $message) {
      if ($message['is_read'] == 0) {
        $messageId = $message['convo_id'];
        $updateQuery = "UPDATE `tblconvo` SET is_read = 1 WHERE convo_id = '$messageId' AND donor_id = '$id' AND org_id = '$org_id' AND initiate_by = 'charity'";
        $updateStmt = mysqli_query($conn, $updateQuery);
      }
    }
  
    $getOrgText = "SELECT * FROM `tblorgs` WHERE org_id = $org_id";
		$resultOrg = mysqli_query($conn, $getOrgText);

		if (mysqli_num_rows($resultOrg) > 0) {
			$org = mysqli_fetch_assoc($resultOrg);
		}

    $getDonorText = "SELECT * FROM `tbldonors` WHERE donor_id = $id";
		$resultDonor = mysqli_query($conn, $getDonorText);

		if (mysqli_num_rows($resultDonor) > 0) {
			$donor = mysqli_fetch_assoc($resultDonor);
		}

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

      if (isset($_POST['msgbox'], $_POST['send'])) {
        $message = $_POST['msgbox'];

        if (empty($message) || $message == "" || $message == " ") {
          header("Location: messenger.php?oid=$org_id");
          die();
        }

        $insertQuery = "INSERT INTO `tblconvo` (donor_id, org_id, initiate_by, message, timestamp, is_read) VALUES ('$id', '$org_id', 'donor', '$message', NOW(), '0')";
        mysqli_query($conn, $insertQuery);
        header("Location: messenger.php?oid=$org_id");
        die();
      }
      
    }
  ?>

  <section class="hero is-info is-fullheight">
    <div class="hero-body">
      <div class="container">
      <a href="donate.php" class="button is-pulled-left is-link is-small">Back</a>
      <h1 class="title has-text-white has-text-centered">Direct Message</h1>
        <div class="columns is-mobile">
          
          <div class="column  is-10 is-offset-1">
            <div class="box">
            <div class="media">
          <div class="media-left">
            <figure class="image is-64x64">
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
                  <img src="https://bulma.io/images/placeholders/64x64.png" alt="<?php echo $org['org_name'] . ' logo';?>">
                <?php 
                  }
                ?>
            </figure>
          </div>
          <div class="media-content">
            <p class="title is-4 has-text-black"><?php echo $org['org_name']; ?></p>
            <p class="subtitle is-6 has-text-black">Contact Person: <?php echo $org['org_person_name']; ?></p>
            <hr>
          </div>
        </div>
        
            <div class="conversation">
              <?php 
                foreach ($messages as $message) {
                  $bubbleClass = ($message['initiate_by'] == 'charity') ? 'incoming charity-a' : 'outgoing charity-a';
                  $personName = ($message['initiate_by'] == 'charity') ? 'Charity' : 'You';
              ?>
              <div class="conversation-bubble <?php echo $bubbleClass; ?>">
                <p>
                  <i class="fas fa-comment icon"></i>
                  <?php echo $message['message']; ?> <br>
                  <span class="timestamp"><?php echo "[" . $personName . "] " .  $message['timestamp']; ?></span>
                </p>
                
              </div>
              <?php } ?>
            </div>

            <form class="conversation-form" method="POST" autocomplete="off">
              <div class="field is-grouped">
                <div class="control is-expanded">
                  <input class="input" type="text" placeholder="Type a message..." name="msgbox">
                </div>
                <div class="control">
                  <button class="button is-primary" name="send" type="submit">Send</button>
                </div>
              </div>
            </form>

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
