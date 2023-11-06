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
		include '../lib/database.php';

		$db = new Database();
		$conn = $db->connect();

		$user = $_SESSION['user'];
		$id = $_SESSION['id'];

		$getDonorText = "SELECT * FROM `tblclients` WHERE client_id = '$id'";
		$resultDonor = $db->query($getDonorText);

		if ($resultDonor->num_rows > 0) {
			$donor = $resultDonor->fetch_assoc();
		}

		$contact_name = (is_null($donor["client_contact_name"]) ? "" : $donor["client_contact_name"]);

		$getUserText = "SELECT * FROM `tblusers` WHERE user_id = '$id' AND account_type = 'd'";
		$resultUser = $db->query($getUserText);

		if ($resultUser->num_rows > 0) {
			$user = $resultUser->fetch_assoc();
		}

	?>
  <!-- CONTENT HERE! -->
  <section class="hero is-info">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Donor Dashboard</h1>
				<h2 class="subtitle">Welcome back, <strong><?php echo $donor['client_name']; ?></strong></h2>
			</div>
		</div>
	</section>
  
	<section class="section">
		<div class="container">
			<div class="columns">
				<div class="column is-one-fifth">
					<div class="box">
						<aside class="menu">
							<p class="menu-label">
								Transactions
							</p>
							<ul class="menu-list">
								<li><a class="is-active" href="#dashboard-tab">Dashboard</a></li>
							</ul>
							<ul class="menu-list">
								<li><a href="#monetary-tab">Monetary Donation</a></li>
								<li><a href="#inkind-tab">Inkind Donation</a></li>
							</ul>
						</aside>
					</div>
					
				</div>
				<div class="column">
					<div id="dashboard-tab" class="content">
						<div class="box">
							<h2 class="title has-text-centered">Donor Dashboard</h2>
							<div class="columns">

								<div class="column">
              						<div class="card has-background-primary has-text-white">
                						<div class="card-header">
                  							<div class="card-header-title has-text-white">
                    							Your Total Amount In-kind Donations
                  							</div>
                						</div>
										<div class="card-content">
											<?php 
												$inkind_donation = 0;

												$inkind = $db->query("SELECT SUM(donation_amount) AS total_amount FROM `tbldonations` WHERE donor_id = '$id' AND donation_type = 'i'");
												if ($inkind) {
													$ik = $inkind->fetch_assoc();
													$inkind_donation = (int) $ik['total_amount'];
												}
											?>
											<p class="is-size-3"><?= $inkind_donation; ?></p>
										</div>
              						</div>
            					</div>

								<div class="column">
              						<div class="card has-background-info has-text-white">
                						<div class="card-header">
                  							<div class="card-header-title has-text-white">
                    							Your Total Amount Money Donations
                  							</div>
                						</div>
										<div class="card-content">
											<?php 
												$monetary_donation = 0;

												$monetary = $db->query("SELECT SUM(donation_amount) AS total_amount FROM `tbldonations` WHERE donor_id = '$id' AND donation_type = 'm'");
												if ($monetary) {
													$m = mysqli_fetch_assoc($monetary);
													$monetary_donation = number_format($m['total_amount']);
												}
											?>
											<p class="is-size-3"><?= "₱" . $monetary_donation; ?></p>
										</div>
              						</div>
            					</div>

							</div>

							<div class="columns">

								<div class="column">
									<div class="card has-background-danger has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    							Your Total Donated Charities
                  							</div>
										</div>
										<div class="card-content">
											<?php
												$donated_charities = 0;

												$donated = $db->query("SELECT COUNT(DISTINCT org_id) AS donated_org_count FROM `tbldonations` WHERE donor_id = '$id'");
												if ($donated) {
													$dtd = mysqli_fetch_assoc($donated);
													$donated_charities = $dtd['donated_org_count'];
												}

											?>
											<p class="is-size-3"><strong class="has-text-white"><?= $donated_charities; ?></strong> charitable organizations</p>
										</div>
									</div>
								</div>

							</div>

						</div>
					</div>

					
					<div id="monetary-tab" class="content is-hidden">
						<?php 
							$msearch = isset($_GET['msearch']) ? $_GET['msearch'] : '';
							$msort = isset($_GET['msort']) ? $_GET['msort'] : 'mnew';
							
							$mquery = "SELECT * FROM `tbldonations` WHERE donor_id = '$id' AND donation_type = 'm'";
							
							if (!empty($msearch)) {
								$mquery .= " AND donation_id LIKE '%$msearch%'";
							}
							
							$currentMonth = date('m');
							$currentYear = date('Y');

							if ($msort == 'mnew') {
								$mquery .= " ORDER BY donation_id DESC";
							} else if ($msort == 'mvalue') {
								$mquery .= " ORDER BY donation_amount DESC";
							} else if ($msort == 'mmonth') {
								$mquery .= " AND MONTH(donation_date) = '$currentMonth' AND YEAR(donation_date) = '$currentYear' ORDER BY donation_date DESC";
							} else {
								$mquery .= " ORDER BY donation_id ASC";
							}

							$array_monetary = [];
							$fund = $db->query($mquery);
							if ($fund) {
								while ($funds = $fund->fetch_assoc()) {
									$array_monetary[] = $funds;
								}
							}
						?>
						<div class="box">
							<h1>Monetary History</h1>
							<form method="GET" action="">
								<div class="field is-pulled-left has-addons">
									<div class="control is-expanded">
										<input class="input" type="text" name="msearch" placeholder="Search..." value="<?= isset($_GET['msearch']) ? htmlspecialchars($_GET['msearch']) : ''; ?>">
									</div>
									<div class="control">
										<button class="button" type="submit">Search</button>
									</div>
								</div>
								<div class="field is-pulled-right has-addons">
									<?php 
										$sortc = isset($_GET['msort']) ? ($_GET['msort'] == "mid" ? "ID" : (($_GET['msort'] == "mnew") ? "New" : (($_GET['msort'] == 'mmonth') ? "Month" : "Monetary"))) : 'New';
									?>
									<div class="control">
										<span class="button is-static label">Sort by: <?php echo $sortc; ?></span>
									</div>
									<div class="control">
										<div class="select">
										<select name="msort">
											<option value="mid">Sort by ID</option>
											<option value="mnew">Sort by New</option>
											<option value="mvalue">Sort by Monetary</option>
											<option value="mmonth">Sort by Month</option>
										</select>
										</div>
									</div>
								</div>
							</form>
							<table class="table is-striped is-fullwidth">
								<thead>
									<tr>
										<th>ID</th>
										<th>Date</th>
										<th>Amount</th>
										<th>Charity</th>
										<th>Status</th>
										<th>Details</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach ($array_monetary as $data_funds) {
											$donation_id = $data_funds['donation_id'];
											$donation_date = $data_funds['donation_date'];
											$donation_amount = $data_funds['donation_amount'];
											$org_id = $data_funds['org_id'];
											$status = $data_funds['donation_status'];

											//$charity = $db->query("SELECT org_name FROM `tblorgs` WHERE org_id = '$org_id'");
											$charity = $db->query("SELECT client_name FROM `tblclients` WHERE client_id = '$org_id'");
											if ($charity) {
												$char_assoc = $charity->fetch_assoc();
												$org_name = $char_assoc['client_name'];
											}

											$formattedDate = date('F j, Y', strtotime($donation_date));
									?>
									<tr>
										<td><?= $donation_id; ?></td>
										<td><?= $formattedDate; ?></td>
										<td><?= "₱" . number_format($donation_amount); ?></td>
										<td><?= $org_name; ?></td>
										<td><?= $status; ?></td>
										<td><button class="button is-small is-info" onclick="monetaryModal('<?= $donation_id; ?>', 0)">View</button></td>
									</tr>
									<?php
										}
									?>
								</tbody>
							</table>
						</div>

						<?php 
							foreach ($array_monetary as $data_funds) {
								$donation_id = $data_funds['donation_id'];
								$donation_org_id = $data_funds['org_id'];
								$donation_amount = $data_funds['donation_amount'];
								$donation_date = $data_funds['donation_date'];
								$event_id = $data_funds['event_id'];

								//$charity = $db->query("SELECT * FROM `tblorgs` WHERE org_id = '$donation_org_id'");
								$charity = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$donation_org_id'");

								if ($charity->num_rows > 0) {
									$char_assoc = $charity->fetch_assoc();
									$org_name = $char_assoc['client_name'];
									$org_person_name = $char_assoc['client_contact_name'];
									$org_address = $char_assoc['client_address'];
								}

								$timeline = $db->query("SELECT * FROM `tblevents` WHERE org_id = '$donation_org_id' AND event_id = '$event_id' AND is_approved = '1'");
								//$timeline = $db->query("SELECT * FROM `tblorgtimeline` WHERE org_id = '$donation_org_id' AND event_id = '$event_id'");
								if ($timeline->num_rows > 0) {
									$timeline_assoc = $timeline->fetch_assoc();
									$event_title = $timeline_assoc['event_title'];
									$event_desc = $timeline_assoc['event_description'];
								}
						?>
							<div class="modal" id="modal-monetary-<?php echo $donation_id; ?>">
								<div class="modal-background"></div>
								<div class="modal-card">
									<header class="modal-card-head">
										<p class="modal-card-title">Donation ID: <?= $donation_id; ?></p>
										<button class="delete" aria-label="close" onclick="monetaryModal('<?= $donation_id; ?>', 1)"></button>
									</header>
									<section class="modal-card-body">
										<h1 class="h4">Your Monetary Donation Details</h1>
										<p>
											<!-- Payment Method ID -->
											<strong> Donated Amount: </strong> <?= "₱" . number_format($donation_amount); ?> <br>
											<strong> Date Donated: </strong> <?= $donation_date; ?> <br>
										</p>
										<h1 class="h4">Donated to Charitable Organization</h1>
										<p>
											<strong> Charity: </strong> <?= $org_name; ?> <br>
											<strong> Contact Person Name: </strong> <?= $org_person_name; ?> <br>
											<strong> Address: </strong> <?= $org_address; ?> <br>
										</p>
										<h1 class="h4">Charity Event</h1>
										<p>
											<strong> Name: </strong> <?= $event_title; ?> <br>
											<strong> Description: </strong> <br>
											<?= $event_desc; ?>
										</p>
									</section>
									<footer class="modal-card-foot">
									</footer>
								</div>
							</div>
						<?php 
							} 
						?>

						<script>
							function monetaryModal(modalId, status) {
								const modal = document.getElementById(`modal-monetary-${modalId}`);
								if (status == 0) {
									modal.classList.add("is-active");
								} else {
									modal.classList.remove("is-active");
								}
            				}
						</script>
					</div>

					<div id="inkind-tab" class="content is-hidden">
						<?php 
							$isearch = isset($_GET['isearch']) ? $_GET['isearch'] : '';
							$isort = isset($_GET['isort']) ? $_GET['isort'] : 'iid';
							
							$iquery = "SELECT * FROM `tbldonations` WHERE donor_id = '$id' AND donation_type = 'i'";
							
							if (!empty($isearch)) {
								$iquery .= " AND donation_id LIKE '%$isearch%'";
							}
							
							if ($isort == 'inew') {
								$iquery .= " ORDER BY donation_id DESC";
							} else if ($msort == 'mvalue') {
								$iquery .= " ORDER BY donation_name DESC";
							} else {
								$iquery .= " ORDER BY donation_id ASC";
							}

							$array_inkind = [];
							$inkind = $db->query($iquery);
							if ($inkind) {
								while ($inkinds = $inkind->fetch_assoc()) {
									$array_inkind[] = $inkinds;
								}
							}
						?>
						<div class="box">
							<h1>Inkind History</h1>
							<form method="GET" action="">
								<div class="field is-pulled-left has-addons">
								<div class="control is-expanded">
									<input class="input" type="text" name="isearch" placeholder="Search..." value="<?= isset($_GET['isearch']) ? htmlspecialchars($_GET['isearch']) : ''; ?>">
								</div>
								<div class="control">
									<button class="button" type="submit">Search</button>
								</div>
								</div>
								<div class="field is-pulled-right has-addons">
								<?php 
									$sortc = isset($_GET['isort']) ? ($_GET['isort'] == "iid" ? "ID" : (($_GET['isort'] == "inew") ? "New" : "Monetary")) : 'New';
								?>
								<div class="control">
									<span class="button is-static label">Sort by: <?= $sortc; ?></span>
								</div>
								<div class="control">
									<div class="select">
									<select name="isort">
										<option value="iid">Sort by ID</option>
										<option value="inew">Sort by New</option>
										<option value="ivalue">Sort by Item</option>
									</select>
									</div>
								</div>
								</div>
							</form>
							<table class="table is-striped is-fullwidth">
								<thead>
									<tr>
										<th>ID</th>
										<th>Date</th>
										<th>Item</th>
										<th>Charity</th>
										<th>Status</th>
										<th>Details</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach ($array_inkind as $data_inkind) {
											$donation_id = $data_inkind['donation_id'];
											$donation_name = $data_inkind['donation_name'];
											$donation_date = $data_inkind['donation_date'];
											$donation_amount = $data_inkind['donation_amount'];
											$org_id = $data_inkind['org_id'];
											$status = $data_inkind['donation_status'];

											$charity = $db->query("SELECT client_name FROM `tblclients` WHERE client_id = '$org_id'");
											//$charity = $db->query("SELECT org_name FROM `tblorgs` WHERE org_id = '$org_id'");
											if ($charity->num_rows > 0) {
												$char_assoc = $charity->fetch_assoc();
												$org_name = $char_assoc['client_name'];
											}

											$formattedDate = date('F j, Y', strtotime($donation_date));
									?>
									<tr>
										<td><?= $donation_id; ?></td>
										<td><?= $formattedDate; ?></td>
										<td><?= $donation_name  . ' (x' . number_format($donation_amount) . ')' ; ?></td>
										<td><?= $org_name; ?></td>
										<td><?= $status; ?></td>
										<td><button class="button is-small is-info" onclick="inkindModal('<?= $donation_id; ?>', 0)">View</button></td>
									</tr>
									<?php
										}
									?>
								</tbody>
							</table>
						</div>

						<?php 
							foreach ($array_inkind as $data_inkind) {
								$donation_id = $data_inkind['donation_id'];
								$donation_name = $data_inkind['donation_name'];
								//$donation_desc = $data_inkind['donation_description'];
								//$donation_cate = $data_inkind['donation_category'];
								$donation_amount = $data_inkind['donation_amount'];
								$donation_date = $data_inkind['donation_date'];
								$org_id = $data_inkind['org_id'];
								$status = $data_inkind['donation_status'];
								$event_id = $data_inkind['event_id'];

								//$charity = $db->query("SELECT * FROM `tblorgs` WHERE org_id = '$org_id'");
								$charity = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$org_id'");
								if ($charity) {
									$char_assoc = $charity->fetch_assoc();
									$org_name = $char_assoc['client_name'];
									$org_person_name = $char_assoc['client_contact_name'];
									$org_address = $char_assoc['client_address'];
								}

								$timeline = $db->query("SELECT * FROM `tblevents` WHERE org_id = '$org_id' AND event_id = '$event_id' AND is_approved = '1'");
								//$timeline = $db->query("SELECT * FROM `tblorgtimeline` WHERE org_id = '$org_id' AND event_id = '$event_id'");
								if ($timeline) {
									$timeline_assoc = $timeline->fetch_assoc();
									$event_title = $timeline_assoc['event_title'];
									$event_desc = $timeline_assoc['event_description'];
								}
						?>
							<div class="modal" id="modal-inkind-<?php echo $donation_id; ?>">
								<div class="modal-background"></div>
								<div class="modal-card">
									<header class="modal-card-head">
										<p class="modal-card-title">Donation ID: <?php echo $donation_id; ?></p>
										<button class="delete" aria-label="close" onclick="inkindModal('<?= $donation_id; ?>', 1)"></button>
									</header>
									<section class="modal-card-body">
										<h1 class="h4">Your Inkind Donation Details</h1>
										<p>
											<strong> Item Name: </strong> <?= $donation_name; ?> <br>
											<strong> Donated Quantity: </strong> <?= number_format($donation_amount); ?> <br>
											<strong> Donation Date: </strong> <?= $donation_date; ?> <br>
											<strong> Status: </strong> <?= $status; ?>
										</p>
										<h1 class="h4">Donated to Charitable Organization</h1>
										<p>
											<strong> Charity: </strong> <?= $org_name; ?> <br>
											<strong> Contact Person Name: </strong> <?= $org_person_name; ?> <br>
											<strong> Address: </strong> <?= $org_address; ?> <br>
										</p>
										<h1 class="h4">Charity Event</h1>
										<p>
											<strong> Name: </strong> <?= $event_title; ?> <br>
											<strong> Description: </strong> <br>
											<?= $event_desc; ?>
										</p>
										<!--
										<h1 class="h4">Image of Item</h1>
										<hr>
										<div class="columns is-multiline">
										<?php
											/*
											$imageG = "SELECT image_data FROM `tblimages` WHERE table_id = '$donation_id' AND category = 'donation_image' AND permit_type = 'inkind'";
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
											}*/
										?>
										</div>-->
									</section>
									<footer class="modal-card-foot">
									</footer>
								</div>
							</div>
						<?php 
							} 
						?>

						<script>
							function inkindModal(modalId, status) {
								const modal = document.getElementById(`modal-inkind-${modalId}`);
								if (status == 0) {
									modal.classList.add("is-active");
								} else {
									modal.classList.remove("is-active");
								}
            				}
						</script>
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
