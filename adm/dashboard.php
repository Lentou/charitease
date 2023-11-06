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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;700&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

  <style>
    body {
      font-family: 'Red Hat Display', sans-serif;
    }
  </style>
</head>
<body>
  <?php
    if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'admin')) {
      location("../reg/login.php");
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
    $id = $_SESSION['id'];

    $admin_user = $db->query("SELECT * FROM `tblusers` WHERE user_id = '$id' AND account_type = 'a'")->fetch_assoc();
    $admin_client = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$id'")->fetch_assoc();
    ?>
    <section class="hero is-danger">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">Administrator Dashboard</h1>
                <h2 class="subtitle">Welcome back, <strong><?= $admin_client["client_name"]; ?></strong></h2>
            </div>
        </div>
    </section>

    <section class="section">
    <div class="columns">

      <div class="column is-one-fifth">
        <div class="box">
          <aside class="menu">
            <p class="menu-label">Validation</p>
            <ul class="menu-list">
              <li><a href="#donor-tab" class="is-active"><span class="material-symbols-outlined">how_to_reg</span>Donor Register</a></li>
              <li><a href="#charity-tab"><span class="material-symbols-outlined">app_registration</span>Charity Register</a></li>
              <li><a href="#timeline-tab"><span class="material-symbols-outlined">view_timeline</span>Charity Timeline</a></li>
            </ul>
            <p class="menu-label">Accounts</p>
            <ul class="menu-list">
              <li><a href="#org-list-tab"><span class="material-symbols-outlined">list</span>Charity List</a></li>
              <li><a href="#donor-list-tab"><span class="material-symbols-outlined">redeem</span>Donors List</a></li>
              <li><a href="#list-donations"><span class="material-symbols-outlined">volunteer_activism</span>Donations List</a></li>
            </ul>
          </aside>
        </div>
      </div>

      <div class="column">

        <div id="donor-tab" class="content">
            <?php
                $dq = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'd' AND u.is_verified = '1' AND c.is_approved = '0'";
            ?>
            <div class="box">
                <h1><span class="material-symbols-outlined">how_to_reg</span>Donor Registration Validation</h1>

                <form action="" method="get">
                    <div class="field is-pulled-left has-addons">
                        <div class="control is-expanded">
                            <input type="text" class="input" name="dsearch" placeholder="Search..">
                        </div>
                        <div class="control">
                            <button class="button" type="submit">Search</button>
                        </div>
                    </div>
                    <div class="field is-pulled-right has-addons">
                        <?php
                            $dsort = isset($_GET['dsort']) ? ($_GET['dsort'] == "new-donor" ? "New" : (($_GET['dsort'] == "id-donor") ? "ID" : "Name")) : 'New';
                        ?>
                        <div class="control">
                            <span class="button is-static label">Sort by: <?= $dsort; ?></span>
                        </div>
                        <div class="control">
                            <div class="select">
                                <select name="dsort">
                                    <option value="id-donor">Sort by ID</option>
                                    <option value="name-donor">Sort by Name</option>
                                    <option value="new-donor">Sort by New</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Donor ID</th>
                            <th>Donor Name</th>
                            <th>Donor Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                        $dsearch = isset($_GET['dsearch']) ? $_GET['dsearch'] : '';
                        $sort = isset($_GET['dsort']) ? $_GET['dsort'] : 'new-donor';

                        if (!empty($search)) {
                            $dq .= " AND (c.client_id LIKE '%$dsearch%' OR c.client_name LIKE '%$dsearch%')";
                        }

                        if ($sort === 'id-donor') {
                            $dq .= " ORDER BY c.client_id";
                        } elseif ($sort === 'name-donor') {
                            $dq .= " ORDER BY c.client_name";
                        } elseif ($sort === 'new-donor') {
                            $dq .= " ORDER BY c.client_id DESC";
                        }

                        $dr = $db->query($dq);

                        $searchResults = [];
                        $otherResults = [];

                        if ($dr->num_rows > 0) {
                            while ($donor = $dr->fetch_assoc()) {
                            
                            $matchesSearch = empty($dsearch) || 
                                            stripos($donor['client_id'], $dsearch) !== false || 
                                            stripos($donor['client_name'], $dsearch) !== false;
                        
                            if ($matchesSearch) {
                                $searchResults[] = $donor;
                            } else {
                                $otherResults[] = $donor;
                            }
                        }
    
                        ?>              
                        <?php foreach ($searchResults as $donor) { 
                          $client_user_type = ($donor['client_user_type'] == 'i') ? "Individual" : "Organization";  
                        ?>

                        <tr>
                            <td><?= $donor['client_id']; ?></td>
                            <td><?= $donor['client_name']; ?></td>
                            <td><?= $client_user_type; ?></td>
                            <td><button class="button is-small is-info" onclick="openModalForm('modal-donor-<?= $donor['client_id']; ?>')">View Details</button></th>
                        </tr>
                        <?php } 
                        foreach ($otherResults as $donor) { 
                          $client_user_type = ($donor['client_user_type'] == 'i') ? "Individual" : "Organization";    
                        ?>
                        <tr>
                            <td><?= $donor['client_id']; ?></td>
                            <td><?= $donor['client_name']; ?></td>
                            <td><?= $client_user_type; ?></td>
                            <td><button class="button is-small is-info" onclick="openModalForm('modal-donor-<?= $donor['client_id']; ?>')">View Details</button></th>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>

            <?php
                $dr = $db->query($dq);

                if ($dr && $dr->num_rows > 0) {
                    while ($donor = $dr->fetch_assoc()) {
                        $dnr = $donor;
                        $donor_id = $dnr['client_id'];
                        $donor_name = $dnr['client_name'];
                        $donor_contact_name = $dnr['client_contact_name'];
                        $donor_address = $dnr['client_address'];
                        $donor_type = $dnr['client_user_type'];
                        $org_type = $dnr['client_org_type'];
                        $date_founded = $dnr['date_founded'];
                        $donor_phone = $dnr['client_phone'];

                        $userq = $db->query("SELECT * FROM `tblusers` WHERE user_id = '$donor_id'");
                        if ($userq && $userq->num_rows > 0) {
                            $user = $userq->fetch_assoc();
                            $email = $user['email'];
                            $account_type = $user['account_type'];
                        }
            ?>
            <script type="text/javascript" src="../lib/modal.js"></script>
            <div class="modal" id="modal-donor-<?= $donor_id; ?>">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title"><strong>Donor ID: </strong> <?= $donor_id; ?></p>
                        <button class="delete" aria-label="close" onclick="closeModalForm('modal-donor-<?= $donor_id; ?>')"></button>
                    </header>
                    <section class="modal-card-body">
                        <p>
                            <strong>Email: </strong> <?= $email; ?> <br>
                            <strong>Donor Name: </strong> <?= $donor_name; ?> <br>
                            <strong>Donor Type: </strong> <?= $donor_type; ?> <br>
                            <strong>Address: </strong> <?= $donor_address; ?> <br>
                            <strong>Contact Phone: </strong> <?= $donor_phone; ?> <br>
                        </p>
                        <?php if ($donor_type == "o") { ?>
                        <p>
                            <strong>Contact Person Name: </strong> <?= $donor_contact_name; ?> <br>
                            <strong>Organization Type: </strong> <?= $org_type; ?> <br>
                            <strong>Date Founded: </strong> <?= $date_founded; ?>
                        </p>
                        <?php } ?>
                        <?php $label = ($donor_type == "o") ? "Registration Permits" : "Valid Ids"; ?>
                        <p><?= $label; ?></p>
                        <hr>
                        <div class="columns is-multiline">
                        <?php
                            $imageR = $db->query("SELECT image_data FROM `tblimages` WHERE category = 'valid_ids' AND client_id = '$donor_id'");
                            
                            if ($imageR && $imageR->num_rows > 0) {
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
                    </section>
                    <footer class="modal-card-foot">
                        <form action="action/a.verify_registration.php" method="POST">
                            <input type="hidden" name="client_id" value="<?= $donor_id; ?>">
                            <input type="hidden" name="account_type" value="<?= $account_type; ?>">
                            <button class="button is-success is-small" type="submit" name="client_yes">Approve</button>
                        </form>
                        
                        <button class="ml-2 button is-danger is-small" onclick="showOtherForm('modal-donor-<?= $donor_id; ?>', 'modal-delete-donor-<?= $donor_id; ?>')">Deny</button>
                    </footer>
                </div>
            </div>
            <div class="modal" id="modal-delete-donor-<?= $donor_id; ?>">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title"><strong>Donor ID: </strong> <?= $donor_id; ?></p>
                        <button class="delete" aria-label="close" onclick="closeModalForm('modal-delete-donor-<?= $donor_id; ?>')"></button>
                    </header>
                    <form action="action/a.verify_registration.php" method="POST">
                        <section class="modal-card-body">
                            <p>Reason to denied the responsed of the validation?</p>
                            <label for="" class="label">Reason:</label>
                            <div class="control">
                                <input class="input" type="text" name="reason" placeholder="Reason to deny the validation?">
                            </div>
                        </section>
                        <footer class="modal-card-foot">
                            <input type="hidden" name="client_id" value="<?= $donor_id; ?>">
                            <input type="hidden" name="account_type" value="<?= $account_type; ?>">
                            <button class="button is-danger is-small" type="submit" name="client_no">Submit</button>
                        </footer>
                    </form>
                </div>
            </div>
            <?php
            }
          }
          ?>
        </div>

        <div id="charity-tab" class="content is-hidden">
            <?php
                $cq = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'c' AND u.is_verified = '1' AND c.is_approved = '0'";
            ?>
            <div class="box">
                <h1><span class="material-symbols-outlined">app_registration</span>Charity Registration Validation</h1>

                <form action="" method="get">
                    <div class="field is-pulled-left has-addons">
                        <div class="control is-expanded">
                            <input type="text" class="input" name="csearch" placeholder="Search..">
                        </div>
                        <div class="control">
                            <button class="button" type="submit">Search</button>
                        </div>
                    </div>
                    <div class="field is-pulled-right has-addons">
                        <?php
                            $csort = isset($_GET['csort']) ? ($_GET['csort'] == "new-charity" ? "New" : (($_GET['csort'] == "id-charity") ? "ID" : "Name")) : 'New';
                        ?>
                        <div class="control">
                            <span class="button is-static label">Sort by: <?= $csort; ?></span>
                        </div>
                        <div class="control">
                            <div class="select">
                                <select name="dsort">
                                    <option value="id-donor">Sort by ID</option>
                                    <option value="name-donor">Sort by Name</option>
                                    <option value="new-donor">Sort by New</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Charity ID</th>
                            <th>Charity Name</th>
                            <th>Charity Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                        $csearch = isset($_GET['csearch']) ? $_GET['csearch'] : '';
                        $sort = isset($_GET['csort']) ? $_GET['csort'] : 'new-charity';

                        if (!empty($csearch)) {
                            $cq .= " AND (c.client_id LIKE '%$search%' OR c.client_name LIKE '%$csearch%')";
                        }

                        if ($sort === 'id-charity') {
                            $cq .= " ORDER BY c.client_id";
                        } elseif ($sort === 'name-charity') {
                            $cq .= " ORDER BY c.client_name";
                        } elseif ($sort === 'new-charity') {
                            $cq .= " ORDER BY c.client_id DESC";
                        }

                        $cr = $db->query($cq);

                        $searchResults = [];
                        $otherResults = [];

                        if ($cr->num_rows > 0) {
                            while ($charity = $cr->fetch_assoc()) {
                            
                            $matchesSearch = empty($csearch) || 
                                            stripos($charity['client_id'], $csearch) !== false || 
                                            stripos($charity['client_name'], $csearch) !== false;
                        
                            if ($matchesSearch) {
                                $searchResults[] = $charity;
                            } else {
                                $otherResults[] = $charity;
                            }
                        }
    
                        ?>              
                        <?php foreach ($searchResults as $charity) { 
                            $client_org_type = match($charity['client_org_type']) {
                                'en' => "Environmental",
                                'he' => "Health",
                                're' => "Religious",
                                'ed' => "Education",
                                default => "Environmental"
                            };
                        ?>

                        <tr>
                            <td><?= $charity['client_id']; ?></td>
                            <td><?= $charity['client_name']; ?></td>
                            <td><?= $client_org_type; ?></td>
                            <td><button class="button is-small is-info" onclick="openModalForm('modal-charity-<?= $charity['client_id']; ?>')">View Details</button></th>
                        </tr>
                        <?php } 
                        foreach ($otherResults as $donor) { 
                            $client_org_type = match($charity['client_org_type']) {
                                'en' => "Environmental",
                                'he' => "Health",
                                're' => "Religious",
                                'ed' => "Education",
                                default => "Environmental"
                            };
                        ?>
                        <tr>
                            <td><?= $donor['client_id']; ?></td>
                            <td><?= $donor['client_name']; ?></td>
                            <td><?= $client_org_type; ?></td>
                            <td><button class="button is-small is-info" onclick="openModalForm('modal-charity-<?= $charity['client_id']; ?>')">View Details</button></th>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>

            <?php
                $cr = $db->query($cq);

                if ($cr && $cr->num_rows > 0) {
                    while ($charity = $cr->fetch_assoc()) {
                        $chr = $charity;
                        $charity_id = $chr['client_id'];
                        $charity_name = $chr['client_name'];
                        $charity_contact_name = $chr['client_contact_name'];
                        $charity_address = $chr['client_address'];
                        $date_founded = $chr['date_founded'];
                        $org_type = $chr['client_org_type'];
                        $charity_phone = $chr['client_phone'];

                        $userq = $db->query("SELECT * FROM `tblusers` WHERE user_id = '$charity_id'");
                        if ($userq && $userq->num_rows > 0) {
                            $user = $userq->fetch_assoc();
                            $email = $user['email'];
                            $account_type = $user['account_type'];
                        }
            ?>
            <script type="text/javascript" src="../lib/modal.js"></script>
            <div class="modal" id="modal-charity-<?= $charity_id; ?>">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title"><strong>Charity ID: </strong> <?= $charity_id; ?></p>
                        <button class="delete" aria-label="close" onclick="closeModalForm('modal-charity-<?= $charity_id; ?>')"></button>
                    </header>
                    <section class="modal-card-body">
                        <p>
                            <strong>Email: </strong> <?= $email; ?> <br>
                            <strong>Charity Name: </strong> <?= $charity_name; ?> <br>
                            <strong>Address: </strong> <?= $charity_address; ?> <br>
                            <strong>Contact Phone: </strong> <?= $charity_phone; ?> <br>
                            <strong>Contact Person Name: </strong> <?= $charity_contact_name; ?> <br>
                            <strong>Organization Type: </strong> <?= $org_type; ?> <br>
                            <strong>Date Founded: </strong> <?= $date_founded; ?>
                        </p>
                        <p>Registration Permits</p>
                        <hr>
                        <div class="columns is-multiline">
                        <?php
                            $imageR = $db->query("SELECT image_data FROM `tblimages` WHERE category = 'permit' AND client_id = '$charity_id'");
                            
                            if ($imageR && $imageR->num_rows > 0) {
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
                    </section>
                    <footer class="modal-card-foot">
                        <form action="action/a.verify_registration.php" method="POST">
                            <input type="hidden" name="client_id" value="<?= $charity_id; ?>">
                            <input type="hidden" name="account_type" value="<?= $account_type; ?>">
                            <button class="button is-success is-small" type="submit" name="client_yes">Approve</button>
                        </form>
                        <button class="ml-2 button is-danger is-small" onclick="showOtherForm('modal-charity-<?= $charity_id; ?>', 'modal-delete-charity-<?= $charity_id; ?>')">Deny</button>
                    </footer>
                </div>
            </div>
            <div class="modal" id="modal-delete-charity-<?= $charity_id; ?>">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title"><strong>Charity ID: </strong> <?= $charity_id; ?></p>
                        <button class="delete" aria-label="close" onclick="closeModalForm('modal-delete-charity-<?= $charity_id; ?>')"></button>
                    </header>
                    <form action="action/a.verify_registration.php" method="POST">
                        <section class="modal-card-body">
                            <p>Reason to denied the responsed of the validation?</p>
                            <label for="" class="label">Reason:</label>
                            <div class="control">
                                <input class="input" type="text" name="reason" placeholder="Reason to deny the validation?" value="">
                            </div>
                        </section>
                        <footer class="modal-card-foot">
                            <input type="hidden" name="client_id" value="<?= $charity_id; ?>">
                            <input type="hidden" name="account_type" value="<?= $account_type; ?>">
                            <button class="button is-danger is-small" type="submit" name="client_no">Submit</button>
                        </footer>
                    </form>
                </div>
            </div>
            <?php
            }
          }
          ?>
        </div>

        <div id="timeline-tab" class="content is-hidden">
            <?php 
                $listOfQueues = [];
                $queueQuery = "SELECT * FROM `tblevents` WHERE event_status = 'pending' AND is_approved = '0'";
                $queueResult = $db->query($queueQuery);

                if ($queueResult && $queueResult->num_rows > 0) {
                    while ($queue = $queueResult->fetch_assoc()) {
                        $listOfQueues[] = $queue;
                    }
                }
            ?>
            <div class="box">
                <h1><span class="material-symbols-outlined">view_timeline</span>Charity Timelines</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Queue ID</th>
                            <th>Charity Name</th>
                            <th>Post Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($listOfQueues as $event) {
                                $queueId = $event['event_id'];
                                $queueTitle = $event['event_title'];
                                $queueDescription = $event['event_description'];
                                $queueType = $event['event_type'];
                                $org_id = $event['org_id'];

                                $type = $queueType == 'a' ? "Announcement" : "Event";

                                $orgsQuery = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'c' AND c.is_approved = '1' AND c.client_id = '$org_id'";
                                $orgsResult = $db->query($orgsQuery);

                                if ($orgsResult && $orgsResult->num_rows > 0) {
                                    while ($organization = $orgsResult->fetch_assoc()) {
                                        $org_name = $organization["client_name"];
                                    }
                                }
                        ?>
                        <tr>
                            <td><?= $queueId; ?></td>
                            <td><?= $org_name; ?></td>
                            <td><?= $type; ?></td>
                            <td><button class="button is-info is-small" onclick="openModalForm('modal-queue-<?= $queueId; ?>')">View Details</button></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>    
            </div>
            <?php 
                foreach ($listOfQueues as $event) {
                    $queueId = $event['event_id'];
                    $org_id = $event['org_id'];
                    $queueTitle = $event['event_title'];
                    $queueDesc = $event['event_description'];
                    $queueType = $event['event_type'] == 'a' ? "Announcement" : "Event";
                    $queueStartDate = $event['event_start_date'];
                    $queueEndDate = $event['event_end_date'];
                    $queueStatus = $event['event_status'];

                    $collections = $db->query("SELECT * FROM `tblcollections` WHERE event_id = '$queueId'");

                    $currentInkind = 0;
                    $currentFunds = 0;
                    $targetInkind = 0;
                    $targetFunds = 0;

                    if ($collections) {
                        while ($collect = $collections->fetch_assoc()) {
                            $clt = $collect;
                            $currentInkind = $clt['current_inkind'];
                            $currentFunds = $clt['current_funds'];

                            $targetInkind = $clt['target_inkind'];
                            $targetFunds = $clt['target_funds'];
                        }
                    }
            ?>
            <script type="text/javascript" src="../lib/modal.js"></script>
            <div class="modal" id="modal-queue-<?= $queueId; ?>">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title"><strong>Queue ID: </strong><?= $queueId; ?></p>
                        <button class="delete" onclick="closeModalForm('modal-queue-<?= $queueId; ?>')" aria-label="close"></button>
                    </header>
                    <section class="modal-card-body">
                        <p>
                            <strong>Title: </strong><?= $queueTitle; ?> <br>
                            <strong>Description: </strong> <br> <?= $queueDesc; ?> <br>
                            <?php 
                                if ($event['event_type'] == 'e') {
                                    if ($queueStartDate != NULL) {
                            ?>
                            <strong>Start Date: </strong><?= $queueStartDate; ?> <br>
                            <?php 
                                }
                                if ($queueEndDate != NULL) {
                            ?>
                            <strong>End Date: </strong><?= $queueEndDate; ?> <br>
                            <?php
                                }
                                if ($targetInkind != 0) {
                            ?>
                            <strong>Target Inkind Amount: </strong> <?= $targetInkind; ?> <br>
                            <?php
                                }
                                if ($targetFunds != 0) {
                            ?>
                            <strong>Target Monetary Amount: </strong> <?= $targetFunds; ?> <br>
                            <?php } }?>
                        </p>
                        <hr>
                        <div class="columns is-multiline">
                        <?php
                            $imageR = $db->query("SELECT image_data FROM `tblimages` WHERE category = 'event_image' AND event_id = '$queueId'");
                            
                            if ($imageR && $imageR->num_rows > 0) {
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
                    </section>
                    <footer class="modal-card-foot">
                        <form action="action/a.event.php" method="POST">
                            <input type="hidden" name="event_id" value="<?= $queueId; ?>">
                            <input type="hidden" name="client_id" value="<?= $org_id; ?>">
                            <button class="button is-success is-small" type="submit" name="event_accept">Accept</button>
                        </form>
                        <button class="ml-2 button is-danger is-small" onclick="showOtherForm('modal-queue-<?= $queueId; ?>', 'modal-queue-delete-<?= $queueId; ?>')">Deny</button>
                    </footer>
                </div>
            </div>
            <div class="modal" id="modal-queue-delete-<?= $queueId; ?>">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title"><strong>Charity ID: </strong> <?= $charity_id; ?></p>
                        <button class="delete" aria-label="close" onclick="closeModalForm('modal-queue-delete-<?= $charity_id; ?>')"></button>
                    </header>
                    <form action="action/a.event.php" method="POST">
                        <section class="modal-card-body">
                            <p>Reason to denied the pending event of charity?</p>
                            <label for="" class="label">Reason:</label>
                            <div class="control">
                                <input class="input" type="text" name="preason" placeholder="Reason to deny the pending event?" value="">
                            </div>
                        </section>
                        <footer class="modal-card-foot">
                            <input type="hidden" name="client_id" value="<?= $org_id; ?>">
                            <input type="hidden" name="event_id" value="<?= $queueId; ?>">
                            <button class="button is-danger is-small" type="submit" name="event_deny">Submit</button>
                        </footer>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>

        <div id="org-list-tab" class="content is-hidden">
            <?php 
                $array_orgs = [];
                $orgs = $db->query("SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'c'");
            
                if ($orgs) {
                    while ($org = $orgs->fetch_assoc()) {
                        $array_orgs[] = $org;
                    }
                }
            ?>
            <div class="box">
                <h1><span class="material-symbols-outlined">list</span>List of Charitable Organizations</h1>
                <table class="table is-striped is-fullwidth">
                    <thead>
                        <tr>
                            <th>Charity ID</th>
                            <th>Charity Name</th>
                            <th>Charity Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($array_orgs as $data_orgs) {
                                $org_id = $data_orgs['client_id'];
                                $org_name = $data_orgs['client_name'];
                                $client_org_type = match($data_orgs['client_org_type']) {
                                    'en' => "Environmental",
                                    'he' => "Health",
                                    're' => "Religious",
                                    'ed' => "Education",
                                    default => "Environmental"
                                };
                                $status = $data_orgs['is_approved'] == 1 ? "Approved" : "Pending";
                        ?>
                        <tr>
                            <td><?= $org_id; ?></td>
                            <td><?= $org_name; ?></td>
                            <td><?= $client_org_type; ?></td>
                            <td><?= $status; ?></td>
                            <td><button class="button is-small is-info" onclick="openModalForm('modal-list-org-<?= $org_id; ?>')">View</button></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
                foreach ($array_orgs as $data_orgs) {
                    $org_id = $data_orgs['client_id'];
                    $org_name = $data_orgs['client_name'];
                    $org_type = $data_orgs['client_org_type'];
                    $status = $data_orgs['is_approved'] == 1 ? "Approved" : "Pending";

                    $org_person_name = $data_orgs['client_contact_name'];
                    $org_phone = $data_orgs['client_phone'];
                    $org_address = $data_orgs['client_address'];
                    $org_desc = $data_orgs['client_bio'];
                    $date_founded = $data_orgs['date_founded'];
			?>
            <script type="text/javascript" src="../lib/modal.js"></script>
            <div class="modal" id="modal-list-org-<?= $org_id; ?>">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
						<p class="modal-card-title">Charity ID: <?= $org_id; ?></p>
						<button class="delete" aria-label="close" onclick="closeModalForm('modal-list-org-<?= $org_id; ?>')"></button>
					</header>
                    <section class="modal-card-body">
                        <h1 class="h4">Charity Organization Details</h1>
                        <p>
                            <strong>Charity ID: </strong> <?= $org_id; ?> <br>
                            <strong>Charity Name: </strong> <?= $org_name; ?> <br>
                            <strong>Charity Type: </strong> <?= $org_type; ?> <br>
                            <strong>Status: </strong> <?= $status; ?> <br>
                        </p>
                        <p>
                            <strong>Contact Name: </strong> <?= $org_person_name; ?> <br>
                            <strong>Phone Number: </strong> <?= $org_phone; ?> <br>
                            <strong>Charity Description: </strong> <br> <?= $org_desc; ?>
                        </p>
                        <p>
                            <strong>Founding Date: </strong> <?= $date_founded; ?> <br>
                        </p>
                    </section>
                    <footer class="modal-card-foot"></footer>
                </div>
            </div>
            <?php } ?>
        </div>

        <div id="donor-list-tab" class="content is-hidden">
            <?php 
                $array_donors = [];
                $donors = $db->query("SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'd'");
            
                if ($donors) {
                    while ($donor = $donors->fetch_assoc()) {
                        $array_donors[] = $donor;
                    }
                }
            ?>
            <div class="box">
                <h1><span class="material-symbols-outlined">redeem</span>List of Donors</h1>
                <table class="table is-striped is-fullwidth">
                    <thead>
                        <tr>
                            <th>Donor ID</th>
                            <th>Donor Name</th>
                            <th>Donor Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($array_donors as $data_donors) {
                                $donor_id = $data_donors['client_id'];
                                $donor_name = $data_donors['client_name'];
                                $client_user_type = match($data_donors['client_user_type']) {
                                    'o' => "Organization",
                                    'i' => "Individual",
                                    default => "Individual"
                                };
                                $status = $data_donors['is_approved'] == 1 ? "Approved" : "Pending";
                        ?>
                        <tr>
                            <td><?= $donor_id; ?></td>
                            <td><?= $donor_name; ?></td>
                            <td><?= $client_user_type; ?></td>
                            <td><?= $status; ?></td>
                            <td><button class="button is-small is-info" onclick="openModalForm('modal-list-donor-<?= $donor_id; ?>')">View</button></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
                foreach ($array_donors as $data_donors) {
                    $donor_id = $data_donors['client_id'];
                    $donor_name = $data_donors['client_name'];
                    $donor_type = $data_donors['client_user_type'];
                    $status = $data_donors['is_approved'] == 1 ? "Approved" : "Pending";

                    $donor_person_name = $data_donors['client_contact_name'];
                    $donor_phone = $data_donors['client_phone'];
                    $donor_address = $data_donors['client_address'];
                    $donor_desc = $data_donors['client_bio'];
			?>
            <script type="text/javascript" src="../lib/modal.js"></script>
            <div class="modal" id="modal-list-donor-<?= $donor_id; ?>">
                <div class="modal-background"></div>
                <div class="modal-card">
                    <header class="modal-card-head">
						<p class="modal-card-title">Donor ID: <?= $donor_id; ?></p>
						<button class="delete" aria-label="close" onclick="closeModalForm('modal-list-donor-<?= $donor_id; ?>')"></button>
					</header>
                    <section class="modal-card-body">
                        <h1 class="h4">Donor Details</h1>
                        <p>
                            <strong>Donor ID: </strong> <?= $donor_id; ?> <br>
                            <strong>Donor Name: </strong> <?= $donor_name; ?> <br>
                            <strong>Donor Type: </strong> <?= $donor_type; ?> <br>
                            <strong>Status: </strong> <?= $status; ?> <br>
                        </p>
                        <p>
                            <strong>Contact Name: </strong> <?= $donor_person_name; ?> <br>
                            <strong>Phone Number: </strong> <?= $donor_phone; ?> <br>
                            <strong>Donor Bio: </strong> <br> <?= $donor_desc; ?>
                        </p>
                        <p>
                            <strong>Founding Date: </strong> <?= $date_founded; ?> <br>
                        </p>
                    </section>
                    <footer class="modal-card-foot"></footer>
                </div>
            </div>
            <?php } ?>
        </div>

        <div id="list-donations" class="content is-hidden">
            <?php
                $array_orgs = [];
                $orgs = $db->query("SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'c' AND is_approved = '1'");

                if ($orgs) {
                    while ($org_assoc = $orgs->fetch_assoc()) {
                        $array_orgs[] = $org_assoc;
                    }
                }
            ?>
            <div class="box">
                <h1><span class="material-symbols-outlined">volunteer_activism</span>Total Donations for Charitable Organizations</h1>
                <table class="table is-striped is-fullwidth">
                    <thead>
                        <tr>
                            <th>Charity ID</th>
                            <th>Charity Name</th>
                            <th>Total Inkind Donations</th>
                            <th>Total Monetary Donations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($array_orgs as $data_orgs) {
                                $org_id = $data_orgs['client_id'];
                                $org_name = $data_orgs['client_name'];

                                $ik = $db->query("SELECT SUM(donation_amount) AS total_inkind FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'i'");
                                $mm = $db->query("SELECT SUM(donation_amount) AS total_funds FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'm'");

                                if ($ik) {
                                    $ii = $ik->fetch_assoc();
                                    $total_inkind = $ii['total_inkind'];
                                }

                                if ($mm) {
                                    $mi = $mm->fetch_assoc();
                                    $total_funds = $mi['total_funds'];
                                }
                            
                        ?>
                        <tr>
                            <td><?= $org_id; ?></td>
                            <td><?= $org_name; ?></td>
                            <td><?= number_format($total_inkind); ?></td>
                            <td><?= "₱" . number_format($total_funds); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
                    console.log('script is running');
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
</html>