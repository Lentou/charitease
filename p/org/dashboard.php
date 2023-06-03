<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../../lib/imgs/charitease_icon.png">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"
      integrity="sha256-BtbhCIbtfeVWGsqxk1vOHEYXS6qcvQvLMZqjtpWUEx8="
      crossorigin="anonymous"
    />

    
</head>

<body>
  <?php
    session_start();
    if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'charity')) {
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
            if ($_SESSION['user'] == 'org') {
        ?>
          <!-- org BUTTON -->
              <div class="navbar-item has-dropdown is-hoverable">
                <a href="#" class="navbar-link">org</a>
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
  <!-- todo tomorrow 
    
    general tabs:
    tab for about page /
    tab for profile page /
    tab for account page (email, pass) /
    timeline tabs:
    tab for permit 
    tab for set goal for monetary and inkind /
    tab for post timeline event or blog (need validation for admin)/
    tab for list of posts in timeline/
    donation tabs:
    tab for total amount of donation and inkind
    tab for table of monetary history
    tab for table of inkind history
    
  -->
  <?php 
    include '../../lib/database.php';

    $db = new Database();
		$conn = $db->connect();

    $user = $_SESSION['user'];
    $id = $_SESSION['id'];

    $getOrgText = "SELECT * FROM `tblorgs` WHERE org_id = $id";
		$resultOrg = mysqli_query($conn, $getOrgText);

		if (mysqli_num_rows($resultOrg) > 0) {
			$org = mysqli_fetch_assoc($resultOrg);
		}

    $getUserText = "SELECT * FROM `tblusers` WHERE user_id = $id";
		$resultUser = mysqli_query($conn, $getUserText);

		if (mysqli_num_rows($resultUser) > 0) {
			$user = mysqli_fetch_assoc($resultUser);
		}

  ?>

  <section class="hero is-link">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Charity Organization Dashboard</h1>
				<h2 class="subtitle">Welcome back, <strong><?php echo $org['org_name']; ?></strong></h2>
			</div>
		</div>
	</section>

  <section class="section">

    <div class="columns">

      <div class="column is-one-fifth">
        <div class="box">
          <aside class="menu">
            <p class="menu-label">General</p>
            <ul class="menu-list">
              <li><a href="#about-tab" class="is-active">About</a></li>
              <li><a href="#profile-tab">Profile</a></li>
              <li><a href="#account-tab">Account</a></li>
            </ul>
            <p class="menu-label">Timeline</p>
            <ul class="menu-list">
              <li><a href="#post-tab">Post Timeline</a></li>
              <li><a href="#payment-tab">Payment Method</a></li>
              <li><a href="#listpost-tab">List Timeline</a></li>
            </ul>
            <p class="menu-label">Donation</p>
            <ul class="menu-list">
              <!--<li><a href="#totalamount-tab">Total Amount</a></li>-->
              <li><a href="#monetaryhistory-tab">Monetary History</a></li>
              <li><a href="#inkindhistory-tab">Inkind History</a></li>
            </ul>
          </aside>
        </div>
      </div>

      <div class="column">

        <div id="about-tab" class="content">
          <div class="box">
            <h2 class="subtitle">About <strong><?php echo $org['org_name']; ?></strong></h2>
            <hr>
            <p><?php echo $org['org_description']; ?></p>
          </div>
          <div class="box">
            <h2 class="title">Edit About</h2>
            <form action="../../lib/php/org_dashboard.php" method="POST">
              <div class="field">
                <label for="" class="label">Charity Organization Description</label>
                <div class="control">
                  <textarea class="textarea" placeholder="type your description here" name="orgDesc"></textarea>
                </div>
              </div>
              <button class="button is-info" type="submit" name="editDescSubmit">Submit</button>
            </form>
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
										<strong>Name: </strong> <?php echo $org['org_name']; ?> <br>
										<strong>Contact Name: </strong> <?php echo $org['org_person_name']; ?> <br>
										<strong>Address: </strong> <?php echo $org['org_address']; ?> <br>
										<strong>Contact Number: </strong> <?php echo $org['org_phone']; ?> <br>
                    <strong>Date Founded: </strong> <?php echo $org['date_founded']; ?>
									</p>
								</div>
							</div>

						</div>
						<div class="box">
							<h2 class="title has-text-centered">Profile Settings</h2>
							<form action="../../lib/php/org_dashboard.php" method="POST">
								
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
												<input type="text" class="input" value="<?php echo $org['org_name']; ?>" name="orgName">
											</div>
										</div>
										<div class="column">
											<label for="" class="label">Contact Name</label>
											<div class="control">
												<input type="text" class="input" value="<?php echo $org['org_person_name']; ?>" name="orgContactName">
											</div>
										</div>
									</div>
								</div>

								<div class="field">
									<label for="" class="label">Address</label>
									<div class="control">
										<input type="text" class="input" value="<?php echo $org['org_address']; ?>" name="orgAddress">
									</div>
								</div>

								<div class="field">
									<div class="columns">
                    <div class="column">
                      <label for="" class="label">Date Founded</label>
                      <div class="control">
                        <input type="date" class="input" value="<?php echo $org['date_founded']; ?>" name="orgFoundingDate">
                      </div>
                    </div>
										<div class="column">
											<label for="" class="label">Contact Number</label>
											<div class="control">
												<input type="text" class="input" value="<?php echo $org['org_phone'];?>" name="orgPhone">
											</div>
										</div>
									</div>
								</div>

								<button class="button is-info" type="submit" name="editProfileSubmit">Submit</button>
							</form>
						</div>
					</div>

        <div id="account-tab" class="content is-hidden">
          <div class="box">
							<h2 class="title">Change Email</h2>
							<form action="../../lib/php/org_dashboard.php" method="POST">
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
							<form action="../../lib/php/org_dashboard.php" method="POST">

								<div class="field">
									<label for="" class="label">Current Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Type your current password" name="orgOldPass">
									</div>
								</div>

								<div class="field">
									<label for="" class="label">New Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Type your new password" name="orgNewPass">
									</div>
								</div>

								<div class="field">
									<label for="" class="label">Confirm New Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Confirm your new password" name="orgConfirmPass">
									</div>
								</div>

								<button class="button is-info" type="submit" name="editPassSubmit">Submit</button>
							</form>
						</div>
        </div>

        <div id="post-tab" class="content is-hidden">
          <div class="box">
            <h2 class="title has-text-centered">Post Timeline Event/Blog with Donation Goal</h2>
            <p><strong>* - Optional</strong> You can post in this section about your events/blogs, You can set the Start date and End Date for the Event Date and so on.</p>
            <form action="../../lib/php/org_dashboard.php" method="POST" enctype="multipart/form-data">
            
              <div class="field">
                <div class="control">
                  <div class="select">
                    <select name="timelineType" id="timelineTypeSelect" onchange="toggleFields()">
                      <option>Select Post Type</option>
                      <option value="blog">Announcement</option>
                      <option value="event">Charity Event</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="field">
                <label for="" class="label">Title</label>
                <div class="control">
                  <input type="text" class="input" name="timelineTitle" placeholder="Type the title of the post">
                </div>
              </div>

              <div class="field">
                <label for="" class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" placeholder="Type your description here" name="timelineDesc"></textarea>
                </div>
              </div>

              <div class="field">
                <label for="" class="label">* Upload Images</label>
                <div class="control">
                  <input type="file" class="input" name="timelineImages[]" multiple>
                </div>
              </div>

              <div class="field is-hidden" id="eventDateFields">
                <div class="columns">
                  <div class="column">
                    <label for="" class="label">* Event Start Date</label>
                    <div class="control">
                      <input type="date" class="input" name="timelineStartDate">
                    </div>
                  </div>
                  <div class="column">
                    <label for="" class="label">* Event End Date</label>
                    <div class="control">
                      <input type="date" class="input" name="timelineEndDate">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field is-hidden" id="checkBoxFields">
                <label class="checkbox">
                  <input type="checkbox" onclick="toggleTargetGoal()">
                    Enable Target Donation Goal?
                  </label>
              </div>


              <div class="columns is-hidden" id="targetFields">
                <div class="column">
                  <div class="field">
                    <label for="">Set Monetary and Inkind</label>
                    <div class="field">
                      <label class="label">* Target Value Monetary</label>
                      <div class="control">
                        <input type="number" class="input" name="targetMonetary" value="">
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">* Target Value Inkind</label>
                    <div class="control">
                      <input type="number" class="input" name="targetInkind" value="">
                    </div>
                  </div>
                </div>
                <div class="column">
                  <div class="field">
                    <label for="">Payment Accounts</label>
                    <div class="field">
                        <label class="label">* GCash Account</label>
                        <div class="control">
                          <div class="select">
                            <select name="gcash" id="">
                              <option value="">Select GCash Account</option>
                              <?php 
                                $gcashQuery = "SELECT * FROM `tblpayments` WHERE org_id = '$id' AND method_type = 'gcash'";
                                $gcashResult = mysqli_query($conn, $gcashQuery);

                                if ($gcashResult->num_rows > 0) {
                                  while ($gcash = mysqli_fetch_assoc($gcashResult)) {
                                    $row = json_decode($gcash["account_details"], true);

                              ?>
                                  <option value="<?php echo $gcash["payment_id"]; ?>"><?php echo $row['account_name'] . " : " . $row['account_value']; ?></option>
                              <?php
                                  }
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                    </div>
                    <div class="field">
                      <label class="label">* Paypal Account</label>
                      <div class="control">
                        <div class="select">
                          <select name="paypal" id="">
                            <option value="">Select Paypal Account</option>
                              <?php 
                                $gcashQuery = "SELECT * FROM `tblpayments` WHERE org_id = '$id' AND method_type = 'paypal'";
                                $gcashResult = mysqli_query($conn, $gcashQuery);

                                if ($gcashResult->num_rows > 0) {
                                  while ($gcash = mysqli_fetch_assoc($gcashResult)) {
                                    $row = json_decode($gcash["account_details"], true);
                              ?>
                                  <option value="<?php echo $gcash["payment_id"]; ?>"><?php echo $row['account_name'] . " : " . $row['account_value']; ?></option>
                              <?php
                                  }
                                }
                              ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">* Maya Account</label>
                      <div class="control">
                        <div class="select">
                          <select name="maya" id="">
                            <option value="">Select Maya Account</option>
                              <?php 
                                $gcashQuery = "SELECT * FROM `tblpayments` WHERE org_id = '$id' AND method_type = 'maya'";
                                $gcashResult = mysqli_query($conn, $gcashQuery);

                                if ($gcashResult->num_rows > 0) {
                                  while ($gcash = mysqli_fetch_assoc($gcashResult)) {
                                    $row = json_decode($gcash["account_details"], true);
                              ?>
                                  <option value="<?php echo $gcash["payment_id"]; ?>"><?php echo $row['account_name'] . " : " . $row['account_value']; ?></option>
                              <?php
                                  }
                                }
                              ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>             
                </div>
              </div>

              <button class="button is-info" type="submit" name="postTimelineSubmit">Submit</button>
            </form>
          </div>
          <script>
            function toggleFields() {
              var selectElement = document.getElementById("timelineTypeSelect");
              var eventDateFields = document.getElementById("eventDateFields");
              var checkBoxFields = document.getElementById("checkBoxFields");

              if (selectElement.value === "event") {
                eventDateFields.classList.remove("is-hidden");
                checkBoxFields.classList.remove("is-hidden");
              } else {
                eventDateFields.classList.add("is-hidden");
                checkBoxFields.classList.add("is-hidden");
              }
            }

            function toggleTargetGoal() {
              var checkBox = document.getElementById("checkBoxFields");
              var targetFields = document.getElementById("targetFields");
              var selectElement = document.getElementById("timelineTypeSelect");

              if (checkBox.querySelector("input[type='checkbox']").checked) {
                  targetFields.classList.remove("is-hidden");
              } else {
                  targetFiels.classList.add("is-hidden");
              }
            }
          </script>
        </div>

        <div id="listpost-tab" class="content is-hidden">
          <h1 class="subtitle has-text-centered">List of Post Timelines</h1>
          <?php
          $listOfEvents = [];

          $eventQuery = "SELECT * FROM `tblorgtimeline` WHERE org_id = '$id'";
          $eventResult = mysqli_query($conn, $eventQuery);

          if ($eventResult && $eventResult->num_rows > 0) {
            while ($post = $eventResult->fetch_assoc()) {
              $listOfEvents[] = $post;
            }
          }
          ?>

          <table>
            <thead>
              <tr>
                <th>Event ID</th>
                <th>Event Title</th>
                <th>Post Type</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($listOfEvents as $event) {
                $eventId = $event['event_id'];
                $eventTitle = $event['event_title'];
                $eventType = $event['event_type'];
                $status = $event['status'];

                $type = $eventType == "blog" ? "Announcement" : "Charity Event";

                ?>
                <tr>
                  <td><?php echo $eventId; ?></td>
                  <td><?php echo $eventTitle; ?></td>
                  <td><?php echo $type; ?></td>
                  <td><?php echo $status; ?></td>
                  <td>
                    <?php if ($status != "pending") { ?>
                      <button class="button is-info" onclick="openModal('<?php echo $eventId; ?>')">View Details</button>
                    <?php } else { ?>
                      <p>On Process</p>
                    <?php } ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

          <?php foreach ($listOfEvents as $event) {
            $event_id = $event['event_id'];
            $event_title = $event['event_title'];
            $event_desc = $event['event_description'];
            $event_type = $event['event_type'];
            $event_start_date = $event['event_start_date'];
            $event_end_date = $event['event_end_date'];

            $current_inkind = $event['current_inkind'];
            $current_funds = $event['current_funds'];

            $target_inkind = $event['target_inkind'];
            $target_funds = $event['target_funds'];

            $eventStatus = $event['status'];
            $type = $event['event_type'] == "blog" ? "Announcement" : "Charity Event";

            
          ?>
            <div class="modal" id="modal-<?php echo $event_id; ?>">
              <form id="form-<?php echo $event_id; ?>">
              <div class="modal-background"></div>
              <div class="modal-card">
                <header class="modal-card-head">
                  <p class="modal-card-title"><strong>Post ID: </strong><?php echo $event_id; ?></p>
                  <button class="delete" onclick="closeModal('<?php echo $event_id; ?>'); return false;" aria-label="close"></button>
                </header>
                <section class="modal-card-body">

                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <input type="hidden" name="edit_org_id" value="<?php echo $id; ?>">
                      <input type="hidden" name="edit_event_id" value="<?php echo $event_id; ?>">
                      <label class="label is-small">Post Type</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <input type="hidden" name="editEventType" value="<?php echo $event_type; ?>">
                        <p class="is-small"><?php echo $type; ?></p>
                      </div>
                    </div>
                  </div>

                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label is-small">Title</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <input name="editEventTitle" class="input is-small" type="text" placeholder="title" value="<?php echo $event_title; ?>">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label is-small">Description</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <textarea name="editEventDesc" id="" cols="20" rows="5" class="textarea is-small"><?php echo $event_desc; ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>

                  <?php 
                    if ($event_type == "event") {
                      if ($event_start_date != NULL) {
                  ?>
                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label is-small">Start Date</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <input name="editEventStartDate" class="input is-small" type="date" placeholder="title" value="<?php echo $event_start_date; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php 
                      }
                      if ($event_end_date != NULL) {
                  ?>
                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label is-small">End Date</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <input name="editEventEndDate" class="input is-small" type="date" placeholder="title" value="<?php echo $event_end_date; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php 
                      }
                      if ($target_inkind != 0) {
                  ?>
                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label is-small">Target Amount of In-kind</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <input type="hidden" name="editCurrentInkind" value="<?php echo $current_inkind; ?>">
                          <input name="editTargetInkind" class="input is-small" type="number" placeholder="title" value="<?php echo $target_inkind; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php 
                      }
                      if ($target_funds != 0) {
                  ?>
                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label is-small">Target Amount of Monetary</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <input type="hidden" name="editCurrentFunds" value="<?php echo $current_funds; ?>">
                          <input name="editTargetFunds" class="input is-small" type="number" placeholder="title" value="<?php echo $target_funds; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php 
                      }
                    }
                  ?>
                  <hr>
                  <?php 
                    $imageG = "SELECT image_data, image_type FROM `tblimages` WHERE table_id = '$event_id' AND category = 'event_image'";
                    $imageR = mysqli_query($conn, $imageG);
                    
                    if (mysqli_num_rows($imageR) > 0) {
                  ?>
                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label is-small">Reupload Images</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <input name="editImages[]" class="input is-small" type="file" multiple>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
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
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success is-small" name="saveEditTimeline" onclick="onSaveModal('<?php echo $event_id; ?>'); return false;">Save</button>
                    <button class="button is-danger is-small" name="deleteEditTimeline" onclick="onDeleteModal('<?php echo $event_id; ?>'); return false;">Delete</button>
                </footer>
                </form>
              </div>
            </div>
          <?php } ?>

          <script>
            function openModal(modalId) {
              const modal = document.getElementById(`modal-${modalId}`);
              modal.classList.add("is-active");
            }

            function closeModal(modalId) {
              const modal = document.getElementById(`modal-${modalId}`);
              modal.classList.remove("is-active");
            }

            function onSaveModal(event_id) {
              const form = document.getElementById(`form-${event_id}`);
              const modal = document.getElementById(`modal-${event_id}`);
              modal.classList.remove("is-active");
              
              Swal.fire({
                title: 'Confirmation',
                text: 'Are you sure you want to save this edited timeline post?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel',
              }).then((result) => {
                if (result.isConfirmed) {
                  const formData = new FormData(form);
                  formData.append('tline_status', 'edit');

                  axios.post('../../lib/php/org_dashboard.php', formData)
                    .then((response) => {
                      const { success, message } = response.data;
                      if (success) {
                        Swal.fire('Success', 'Timeline post edited successfully. Wait for the confirmation of the admin', 'success')
                        .then(() => {
                          window.location.href = '../../p/org/dashboard.php';
                        });
                      } else {
                        Swal.fire('Error', message, 'error');
                      }
                    })
                    .catch((error) => { 
                      Swal.fire('Error', 'An error occurred while deleting the timeline post.', 'error');
                    });
                }
              });
            }

            function onDeleteModal(event_id) {
              const form = document.getElementById(`form-${event_id}`);
              const modal = document.getElementById(`modal-${event_id}`);
              modal.classList.remove("is-active");
              
              Swal.fire({
                title: 'Confirmation',
                text: 'Are you sure you want to delete this timeline post?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
              }).then((result) => {
                if (result.isConfirmed) {
                  const formData = new FormData(form);
                  formData.append('tline_status', 'delete');

                  axios.post('../../lib/php/org_dashboard.php', formData)
                    .then((response) => {
                      const { success, message } = response.data;
                      if (success) {
                        Swal.fire('Success', 'Timeline post deleted successfully.', 'success')
                        .then(() => {
                          window.location.href = '../../p/org/dashboard.php';
                        });
                      } else {
                        Swal.fire('Error', message, 'error');
                      }
                    })
                    .catch((error) => { 
                      Swal.fire('Error', 'An error occurred while deleting the timeline post.', 'error');
                    });
                }
              });
            }
          </script>
        </div>

        <div id="payment-tab" class="content is-hidden">
          <div class="box">
            <h1 class="title has-text-centered">Add Payment Method for Donation</h1>
            <form action="../../lib/php/org_dashboard.php" method="POST">
              <div class="field">
                <label for="" class="label">Payment Method</label>
                <div class="control">
                  <div class="select">
                    <select name="addPayment" id="">
                      <option>Select Payment Method</option>
                      <option value="gcash">GCash</option>
                      <option value="maya">Maya</option>
                      <option value="paypal">PayPal</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="field">
                <label for="" class="label">Name</label>
                <div class="control">
                  <input type="text" class="input" name="addName">
                </div>
              </div>

              <div class="field">
                <label for="" class="label">Email (Paypal) or Phone Number (GCash, Maya) </label>
                <div class="control">
                  <input type="text" class="input" name="addDetails">
                </div>
              </div>

              <button class="button is-info" type="submit" name="addPaymentSubmit">Add Payment</button>
            </form>
          </div>
          <div class="box">
            <h1 class="title has-text-centered">Delete Payment Method for Donation</h1>
            <form method="POST" id="deletePaymentForm">

              <div class="field">
                <label for="" class="label">Payment Method Account List</label>
                <div class="control">
                  <div class="select">
                    <select name="delPaymentMethod" id="paymentSelect">
                      <option>Select Payment Account</option>
                      <?php 
                        $payQuery = "SELECT * FROM `tblpayments` WHERE org_id = '$id'";
                        $payResult = mysqli_query($conn, $payQuery);

                        if ($payResult->num_rows > 0) {
                          while ($payacc = mysqli_fetch_assoc($payResult)) {
                              $accrow = json_decode($payacc["account_details"], true);

                          ?>
                        <option value="<?php echo $payacc["payment_id"]; ?>">
                          <?php echo "[" . $payacc["payment_id"] . "] (" . $payacc["method_type"] . ") " . $accrow['account_name'] . " : " . $accrow['account_value']; ?>
                        </option>
                      <?php
                          }
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <button class="button is-info" type="submit" name="delPaymentSubmit">Delete Payment</button>
            </form>
          </div>
          <script>
            document.addEventListener('DOMContentLoaded', () => {
              const form = document.getElementById('deletePaymentForm');
              const select = document.getElementById('paymentSelect');

              form.addEventListener('submit', (e) => {
                e.preventDefault();
                const selectedOption = select.value;

                if (selectedOption === 'Select Payment Account') {
                  Swal.fire('Error', 'Please select a payment account.', 'error');
                  return;
                }

                Swal.fire({
                  title: 'Confirmation',
                  text: 'Are you sure you want to delete this payment account?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!',
                  cancelButtonText: 'Cancel',
                }).then((result) => {
                  if (result.isConfirmed) {
                    // Create a new FormData object
                    const formData = new FormData();
                    formData.append('delPaymentMethod', selectedOption);

                    // Perform the deletion request using Axios
                    axios.post('../../lib/php/org_dashboard.php', formData)
                      .then((response) => {
                        // Handle the response if needed
                        Swal.fire('Success', 'Payment account deleted successfully.', 'success');
                        const { success, message } = response.data;
                        if (success) {
                          Swal.fire('Success', 'Payment account deleted successfully.', 'success');
                        } else {
                          Swal.fire('Error', message, 'error');
                        }
                      })
                      .catch((error) => {
                        // Handle any errors
                        Swal.fire('Error', 'An error occurred while deleting the payment account.', 'error');
                      });
                  }
                });
              });
            });
          </script>
        </div>

        <div id="monetaryhistory-tab" class="content is-hidden">
        </div>

        <div id="inkindhistory-tab" class="content is-hidden">
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
