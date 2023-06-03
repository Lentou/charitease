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
							<!--
							<p class="menu-label">
								Donations
							</p>
							<ul class="menu-list">
								<li><a href="#donations-tab">All Donations</a></li>
								<li><a href="#monthly-tab">Monthly Donations</a></li>
							</ul>
							-->
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
											<p class="is-size-3">20</p>
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
											<p class="is-size-3">P10,200</p>
										</div>
              						</div>
            					</div>

							</div>

							<div class="columns">

								<div class="column">
									<div class="card">
										<div class="card-content">
											<p class="title">Recent Donations</p>

											<table class="table is-striped is-fullwidth">
												<thead>
													<tr>
														<th>Date</th>
														<th>Item</th>
														<th>Value</th>
														<th>Charity</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>May 1, 2023</td>
														<td>Monetary</td>
														<td>P10,200</td>
														<td>XYZCorporation Inc</td>
													</tr>
													<tr>
														<td>April 30, 2023</td>
														<td>In-kind Value</td>
														<td>Canned Goods (x10)</td>
														<td>Sagip Kapamilya Inc</td>
													</tr>
													<tr>
														<td>April 29, 2023</td>
														<td>In-kind Value</td>
														<td>Old T-Shirts (x10)</td>
														<td>ABCCompany LLC</td>
													</tr>
												</tbody>
											</table>

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
										<img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png">
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
							<form action="" method="POST">
								
								<div class="field">
									<div class="columns">
										<div class="column">
											<div class="field">
												<div class="control">
													<label for="" class="label">Upload Profile Picture</label>
													<input type="file" class="input">
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

					<!--
					<div id="donations-tab" class="content is-hidden">
						<h2>All Donations</h2>
						<p>This is the all donations tab.</p>
					</div>

					<div id="monthly-tab" class="content is-hidden">
						<h2>Monthly Donations</h2>
						<p>This is the monthly donations tab.</p>
					</div>
					-->

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

  <?php include '../../lib/alert.php'; ?>
</body>
</html>
