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
                  <a href="../../p/dnr/donate.php" class="navbar-item">Charities</a>
				  <a href="../../p/dnr/maps.php" class="navbar-item">Nearest Charities</a>
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
				  <a href="../../p/org/donors.php" class="navbar-item">Direct Message</a>
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
		$id = $_SESSION['id'];
		$getDonorText = "SELECT * FROM `tbldonors` WHERE donor_id = $id";
		$resultDonor = mysqli_query($conn, $getDonorText);

		if (mysqli_num_rows($resultDonor) > 0) {
			$donor = mysqli_fetch_assoc($resultDonor);
		}

		$contact_name = (is_null($donor["donor_contact_name"]) ? "" : $donor["donor_contact_name"]);

		$getUserText = "SELECT * FROM `tblusers` WHERE user_id = $id";
		$resultUser = mysqli_query($conn, $getUserText);

		if (mysqli_num_rows($resultUser) > 0) {
			$user = mysqli_fetch_assoc($resultUser);
		}


		// backend for tabs
		if ($_SERVER["REQUEST_METHOD"] === "POST") {

			if (isset($_POST["editSubmit"])) {

				// todo get the profile picture
				$donorName = $_POST["donorName"];
				$donorContactName = $_POST["donorContactName"];
				$donorAddress = $_POST["donorAddress"];
				$donorType = $_POST["donorType"];
				$donorPhone = $_POST["donorPhone"];

				if (isset($_FILES["profile_pic"])) {

					$files = $_FILES["profile_pic"];

					$tmpFilePath = $files["tmp_name"];
					$tableId = $id;
					$fileType = $files["type"];
					$permitType = "icon";
					$category = "donor_icon";
					$imageName = $files["name"];
					$imageData = base64_encode(file_get_contents(addslashes($tmpFilePath)));

					$get_pic = $db->query("SELECT * FROM `tblimages` WHERE table_id = '$id' AND category = 'donor_icon' AND permit_type = 'icon'");
					if ($get_pic->num_rows > 0) {
						$update_pic = $db->query("UPDATE `tblimages` SET image_name = '$imageName', image_type = '$fileType', image_data = '$imageData' WHERE table_id = '$id'");
					} else {
						$insertImage = $conn->prepare("INSERT INTO `tblimages` (table_id, permit_type, category, image_name, image_type, image_data) VALUES (?, ?, ?, ?, ?, ?)");
						$insertImage->bind_param("isssss", $tableId, $permitType, $category, $imageName, $fileType, $imageData);
						$insertImage->execute();
						$insertImage->close();
					}
					
				}

				if ($donorContactName !== "" || $donorContactName !== null) {
					$donorContactName = $donorName;
				}

				// donor_name, donor_contact_name, donor_address, donor_type, donor_phone, donor_icon
				$newProfileText = "UPDATE `tbldonors` SET 
					donor_name = '$donorName', 
					donor_contact_name = '$donorContactName',
					donor_address = '$donorAddress',
					donor_type = '$donorType',
					donor_phone = '$donorPhone' WHERE donor_id = '$id'";

				$newProfileResult = mysqli_query($conn, $newProfileText);

				if ($newProfileResult) {
					$_SESSION["status"] = "Edit Profile Success";
					$_SESSION["status_text"] = "Profile updated successfully!";
					$_SESSION["status_code"] = "success";
					header("Location: dashboard.php");
					die();
				} else {
					$_SESSION["status"] = "Edit Profile Failed";
					$_SESSION["status_text"] = "Error updating profile: " . mysqli_error($conn);
					$_SESSION["status_code"] = "success";
					header("Location: dashboard.php");
					die();
				}
			}

			if (isset($_POST["editEmailSubmit"])) {
				if (isset($_POST["userEmail"], $_POST["userPass"])) {

					$currentEmail = $_POST["userEmail"];
					$currentPass = $user["password"];
					$checkPass = $_POST["userPass"];

					if ($currentPass !== $checkPass) {
						$_SESSION["status"] = "Edit Email Failed";
						$_SESSION["status_text"] = "Wrong password!";
						$_SESSION["status_code"] = "error";
						header("Location: dashboard.php");
						die();
					}

					$newUserEmailText = "UPDATE `tblusers` SET email = '$currentEmail' WHERE user_id = $id";
					$newEmailResult = mysqli_query($conn, $newUserEmailText);

					if ($newEmailResult) {
						$_SESSION["status"] = "Edit Email Success";
						$_SESSION["status_text"] = "Email updated successfully!";
						$_SESSION["status_code"] = "success";
						header("Location: dashboard.php");
						die();
					} else {
						$_SESSION["status"] = "Edit Email Failed";
						$_SESSION["status_text"] = "Error updating email: " . mysqli_error($conn);
						$_SESSION["status_code"] = "success";
						header("Location: dashboard.php");
						die();
					}
				}
			}

			if (isset($_POST["editPassSubmit"])) {
				if (isset($_POST["donorOldPass"], $_POST["donorNewPass"], $_POST["donorConfirmPass"])) {
					
					$currentPass = $user["password"];
					$oldPass = $_POST["donorOldPass"];
					$newPass = $_POST["donorNewPass"];
					$confirmPass = $_POST["donorConfirmPass"];

					if ($currentPass !== $oldPass) {
						$_SESSION["status"] = "Edit Password Failed";
						$_SESSION["status_text"] = "Current password didnt match";
						$_SESSION["status_code"] = "error";
						header("Location: dashboard.php");
						die();
					}

					if ($newPass !== $confirmPass) {
						$_SESSION["status"] = "Edit Password Failed";
						$_SESSION["status_text"] = "New and Confirm Pass didnt match!";
						$_SESSION["status_code"] = "error";
						header("Location: dashboard.php");
						die();
					}
					
					$newUserPassText = "UPDATE `tblusers` SET password = '$newPass' WHERE user_id = $id";
					$newPassResult = mysqli_query($conn, $newUserPassText);

					if ($newPassResult) {
						$_SESSION["status"] = "Edit Password Success";
						$_SESSION["status_text"] = "Password updated successfully!";
						$_SESSION["status_code"] = "success";
						header("Location: dashboard.php");
						die();
					} else {
						$_SESSION["status"] = "Edit Password Failed";
						$_SESSION["status_text"] = "Error updating password: " . mysqli_error($conn);
						$_SESSION["status_code"] = "error";
						header("Location: dashboard.php");
						die();
					}
				}
			}

		}
	?>
  <!-- CONTENT HERE! -->
  <section class="hero is-info">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Donor Dashboard</h1>
				<h2 class="subtitle">Welcome back, <strong><?php echo $donor['donor_name']; ?></strong></h2>
			</div>
		</div>
	</section>
  
	<section class="section">
		<div class="container">
			<div class="columns">
				<div class="column is-one-third">
					<div class="box">
						<aside class="menu">
							<p class="menu-label">
								General
							</p>
							<ul class="menu-list">
								<li><a class="is-active" href="#dashboard-tab">Dashboard</a></li>
								<li><a href="#profile-tab">Profile</a></li>
								<li><a href="#account-tab">Account</a></li>
							</ul>
							<p class="menu-label">
								Transactions
							</p>
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

												$inkind = $db->query("SELECT SUM(donation_amount) AS total_amount FROM `tbldonations` WHERE donor_id = '$id' AND donation_type = 'inkind'");
												if ($inkind) {
													$ik = mysqli_fetch_assoc($inkind);
													$inkind_donation = (int) $ik['total_amount'];
												}
											?>
											<p class="is-size-3"><?php echo $inkind_donation; ?></p>
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

												$monetary = $db->query("SELECT SUM(donation_amount) AS total_amount FROM `tbldonations` WHERE donor_id = '$id' AND donation_type = 'monetary'");
												if ($monetary) {
													$m = mysqli_fetch_assoc($monetary);
													$monetary_donation = number_format($m['total_amount']);
												}
											?>
											<p class="is-size-3"><?php echo "₱" . $monetary_donation; ?></p>
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
											<p class="is-size-3"><strong class="has-text-white"><?php echo $donated_charities; ?></strong> charitable organizations</p>
										</div>
									</div>
								</div>

							</div>

						</div>
					</div>

					<div id="profile-tab" class="content is-hidden">
						<div class="box">
							<h2 class="title has-text-centered">Profile</h2>
							<hr>
							<div class="columns">
								<div class="column is-flex is-justify-content-center is-align-items-center">
									<figure class="image is-128x128">
										<?php
											$get_pic = $db->query("SELECT * FROM `tblimages` WHERE table_id = '$id' AND category = 'donor_icon' AND permit_type = 'icon'");
											if ($get_pic->num_rows > 0) {
												$gett = $get_pic->fetch_assoc();
												$imageData = $gett['image_data'];
										?>
											<img class="is-round" src="data:image;base64,<?php echo $imageData ?>" alt="Event Image">
										<?php 
											} else {
										?>
											<img class="is-round" src="https://bulma.io/images/placeholders/128x128.png" alt="<?php echo $donor['donor_name'] . ' logo';?>">
										<?php 
											}
										?>
									</figure>
								</div>

								<div class="column">
									<p>
										<strong>Name: </strong> <?php echo $donor['donor_name']; ?> <br>
										<strong>Contact Name: </strong> <?php echo $contact_name; ?> <br>
										<strong>Address: </strong> <?php echo $donor['donor_address']; ?> <br>
										<strong>Donor Type: </strong> <?php echo $donor['donor_type']; ?> <br>
										<strong>Contact Number: </strong> <?php echo $donor['donor_phone']; ?>
									</p>
								</div>
							</div>

						</div>
						<div class="box">
							<h2 class="title has-text-centered">Profile Settings</h2>
							<form action="" method="POST" enctype="multipart/form-data">
								
								<div class="field">
									<div class="columns">
										<div class="column">
											<div class="field">
												<div class="control">
													<label for="" class="label">Upload Profile Picture</label>
													<input type="file" class="input" name="profile_pic" accept=".png, .jpg, .jpeg">
												</div>
											</div>
										</div>
										<span class="column"></span>
									</div>
								</div>
								

								<div class="field">
									<div class="columns">
                      					<div class="column">
											<label for="" class="label">Name</label>
											<div class="control">
												<input type="text" class="input" value="<?php echo $donor['donor_name']; ?>" name="donorName">
											</div>
										</div>
										<div class="column">
											<label for="" class="label">Contact Name</label>
											<div class="control">
												<input type="text" class="input" value="<?php echo $contact_name; ?>" name="donorContactName">
											</div>
										</div>
									</div>
								</div>

								<div class="field">
									<label for="" class="label">Address</label>
									<div class="control">
										<input type="text" class="input" value="<?php echo $donor['donor_address']; ?>" name="donorAddress">
									</div>
								</div>

								<div class="field">
									<div class="columns">
										<div class="column">
											<label for="" class="label">Donor Type</label>
											<div class="control">
												<div class="select is-medium">
													<select name="donorType" id="select_donortype">
														<option value="Individual">Individual</option>
														<option value="Organization">Organization</option>
													</select>
												</div>
											</div>
										</div>
										<div class="column">
											<label for="" class="label">Contact Number</label>
											<div class="control">
												<input type="text" class="input" value="<?php echo $donor['donor_phone'];?>" name="donorPhone">
											</div>
										</div>
									</div>
								</div>

								<button class="button is-info" type="submit" name="editSubmit">Submit</button>
							</form>
						</div>
					</div>

					<div id="account-tab" class="content is-hidden">
						<div class="box">
							<h2 class="title">Change Email</h2>
							<form action="" method="POST">
								<div class="field">
									<label for="" class="label">Email</label>
									<div class="control">
										<input type="text" class="input" placeholder="Type your current email" name="userEmail" value="<?php echo $user['email']; ?>">
									</div>
								</div>
								<div class="field">
									<label for="" class="label">Current Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Type your current password" name="userPass">
									</div>
								</div>

								<button class="button is-info" type="submit" name="editEmailSubmit">Submit</button>
							</form>
						</div>
						<div class="box">
							<h2 class="title">Change Password</h2>
							<form action="" method="POST">

								<div class="field">
									<label for="" class="label">Current Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Type your current password" name="donorOldPass">
									</div>
								</div>

								<div class="field">
									<label for="" class="label">New Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Type your new password" name="donorNewPass">
									</div>
								</div>

								<div class="field">
									<label for="" class="label">Confirm New Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Confirm your new password" name="donorConfirmPass">
									</div>
								</div>

								<button class="button is-info" type="submit" name="editPassSubmit">Submit</button>
							</form>
						</div>
					</div>

					
					<div id="monetary-tab" class="content is-hidden">
						<?php 
							$array_monetary = [];
							$fund = $db->query("SELECT * FROM `tbldonations` WHERE donor_id = '$id' AND donation_type = 'monetary'");
							if ($fund) {
								while ($funds = $fund->fetch_assoc()) {
									$array_monetary[] = $funds;
								}
							}
						?>
						<div class="box">
							<table class="table is-striped is-fullwidth">
								<thead>
									<tr>
										<th>Donation ID</th>
										<th>Date</th>
										<th>Amount Value</th>
										<th>Charity</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach ($array_monetary as $data_funds) {
											$donation_id = $data_funds['donation_id'];
											$donation_date = $data_funds['donation_date'];
											$donation_amount = $data_funds['donation_amount'];
											$org_id = $data_funds['org_id'];
											$status = $data_funds['status'];

											$charity = $db->query("SELECT org_name FROM `tblorgs` WHERE org_id = '$org_id'");
											if ($charity) {
												$char_assoc = $charity->fetch_assoc();
												$org_name = $char_assoc['org_name'];
											}
									?>
									<tr>
										<td><?php echo $donation_id; ?></td>
										<td><?php echo $donation_date; ?></td>
										<td><?php echo "₱" . number_format($donation_amount); ?></td>
										<td><?php echo $org_name; ?></td>
										<td><?php echo $status; ?></td>
										<td><button class="button is-small is-info" onclick="monetaryModal('<?php echo $donation_id; ?>', 0)">View</button></td>
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

								$charity = $db->query("SELECT * FROM `tblorgs` WHERE org_id = '$donation_org_id'");
								if ($charity->num_rows > 0) {
									$char_assoc = $charity->fetch_assoc();
									$org_name = $char_assoc['org_name'];
									$org_person_name = $char_assoc['org_person_name'];
									$org_address = $char_assoc['org_address'];
								}

								$timeline = $db->query("SELECT * FROM `tblorgtimeline` WHERE org_id = '$donation_org_id' AND event_id = '$event_id'");
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
										<p class="modal-card-title">Donation ID: <?php echo $donation_id; ?></p>
										<button class="delete" aria-label="close" onclick="monetaryModal('<?php echo $donation_id; ?>', 1)"></button>
									</header>
									<section class="modal-card-body">
										<h1 class="h4">Your Monetary Donation Details</h1>
										<p>
											<!-- Payment Method ID -->
											<strong> Donated Amount: </strong> <?php echo "₱" . number_format($donation_amount); ?> <br>
											<strong> Date Donated: </strong> <?php echo $donation_date; ?> <br>
										</p>
										<h1 class="h4">Donated to Charitable Organization</h1>
										<p>
											<strong> Charity: </strong> <?php echo $org_name; ?> <br>
											<strong> Contact Person Name: </strong> <?php echo $org_person_name; ?> <br>
											<strong> Address: </strong> <?php echo $org_address; ?> <br>
										</p>
										<h1 class="h4">Charity Event</h1>
										<p>
											<strong> Name: </strong> <?php echo $event_title; ?> <br>
											<strong> Description: </strong> <br>
											<?php echo $event_desc; ?>
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
							$array_inkind = [];
							$inkind = $db->query("SELECT * FROM `tbldonations` WHERE donor_id = '$id' AND donation_type = 'inkind'");
							if ($inkind) {
								while ($inkinds = $inkind->fetch_assoc()) {
									$array_inkind[] = $inkinds;
								}
							}
						?>
						<div class="box">
							<table class="table is-striped is-fullwidth">
								<thead>
									<tr>
										<th>Donation ID</th>
										<th>Item Name</th>
										<th>Quantity</th>
										<th>Donation Date</th>
										<th>Charity</th>
										<th>Status</th>
										<th>Action</th>
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
											$status = $data_inkind['status'];

											$charity = $db->query("SELECT org_name FROM `tblorgs` WHERE org_id = '$org_id'");
											if ($charity->num_rows > 0) {
												$char_assoc = $charity->fetch_assoc();
												$org_name = $char_assoc['org_name'];
											}
									?>
									<tr>
										<td><?php echo $donation_id; ?></td>
										<td><?php echo $donation_name; ?></td>
										<td><?php echo number_format($donation_amount); ?></td>
										<td><?php echo $donation_date; ?></td>
										<td><?php echo $org_name; ?></td>
										<td><?php echo $status; ?></td>
										<td><button class="button is-small is-info" onclick="inkindModal('<?php echo $donation_id; ?>', 0)">View</button></td>
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
								$donation_desc = $data_inkind['donation_description'];
								$donation_cate = $data_inkind['donation_category'];
								$donation_amount = $data_inkind['donation_amount'];
								$donation_date = $data_inkind['donation_date'];
								$org_id = $data_inkind['org_id'];
								$status = $data_inkind['status'];
								$event_id = $data_inkind['event_id'];

								$charity = $db->query("SELECT * FROM `tblorgs` WHERE org_id = '$org_id'");
								if ($charity) {
									$char_assoc = $charity->fetch_assoc();
									$org_name = $char_assoc['org_name'];
									$org_person_name = $char_assoc['org_person_name'];
									$org_address = $char_assoc['org_address'];
								}

								$timeline = $db->query("SELECT * FROM `tblorgtimeline` WHERE org_id = '$org_id' AND event_id = '$event_id'");
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
										<button class="delete" aria-label="close" onclick="inkindModal('<?php echo $donation_id; ?>', 1)"></button>
									</header>
									<section class="modal-card-body">
										<h1 class="h4">Your Inkind Donation Details</h1>
										<p>
											<strong> Item Name: </strong> <?php echo $donation_name; ?> <br>
											<strong> Description: </strong> <br> <?php echo $donation_desc; ?> <br>
											<strong> Category: </strong> <?php echo $donation_cate; ?> <br>
											<strong> Donated Quantity: </strong> <?php echo number_format($donation_amount); ?> <br>
											<strong> Donation Date: </strong> <?php echo $donation_date; ?> <br>
											<strong> Status: </strong> <?php echo $status; ?>
										</p>
										<h1 class="h4">Donated to Charitable Organization</h1>
										<p>
											<strong> Charity: </strong> <?php echo $org_name; ?> <br>
											<strong> Contact Person Name: </strong> <?php echo $org_person_name; ?> <br>
											<strong> Address: </strong> <?php echo $org_address; ?> <br>
										</p>
										<h1 class="h4">Charity Event</h1>
										<p>
											<strong> Name: </strong> <?php echo $event_title; ?> <br>
											<strong> Description: </strong> <br>
											<?php echo $event_desc; ?>
										</p>
										<h1 class="h4">Image of Item</h1>
										<hr>
										<div class="columns is-multiline">
										<?php
											$imageG = "SELECT image_data, image_type FROM `tblimages` WHERE table_id = '$donation_id' AND category = 'donation_image' AND permit_type = 'inkind'";
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
            <img src="../../lib/imgs/charitease_icon.png" alt="" class="image is-64x64">
          </a>
        </div>
        <div>
          <ul class="is-flex is-flex-wrap-wrap is-align-items-center is-justify-content-center">
            <li class="mr-4"><a href="../../p/com/about.php" class="button is-white">About</a></li>
            <li class="mr-4"><a href="../../p/com/company.php" class="button is-white">Company</a></li>
            <li class="mr-4"><a href="../../p/com/features.php" class="button is-white">Features</a></li>
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

  <?php include '../../lib/alert.php'; ?>
</body>
</html>
