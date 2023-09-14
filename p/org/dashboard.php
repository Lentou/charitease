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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
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
    $org_id = $id;

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
            <p class="menu-label">Others</p>
            <ul class="menu-list">
              <!--<li><a href="#totalamount-tab">Total Amount</a></li>-->
              <li><a href="#rating-tab">Rate and Review</a></li>
              <li><a href="#statistics-tab">Statistics</a></li>
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
                    <?php 
                      $get_pic = $db->query("SELECT * FROM `tblimages` WHERE table_id = '$org_id' AND category = 'org_icon' AND permit_type = 'icon'");
                      if ($get_pic->num_rows > 0) {
                        $gett = $get_pic->fetch_assoc();
                        $imageData = $gett['image_data'];
                    ?>
                      <img class="is-rounded" src="data:image;base64,<?php echo $imageData ?>" alt="Event Image">
                    <?php 
                      } else {
                    ?>
                      <img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png">
                    <?php 
                      }
                    ?>
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
							<form action="../../lib/php/org_dashboard.php" method="POST" enctype="multipart/form-data">
								
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
            <p><strong>* - Required</strong> You can post in this section about your events/blogs, You can set the Start date and End Date for the Event Date and so on.</p>
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
                <label for="" class="label">* Title</label>
                <div class="control">
                  <input type="text" class="input" name="timelineTitle" placeholder="Type the title of the post">
                </div>
              </div>

              <div class="field">
                <label for="" class="label">* Description</label>
                <div class="control">
                  <textarea class="textarea" placeholder="Type your description here" name="timelineDesc"></textarea>
                </div>
              </div>

              <div class="field">
                <label for="" class="label">Upload Images</label>
                <div class="control">
                  <input type="file" class="input" name="timelineImages[]" multiple accept=".png, .jpg, .jpeg">
                </div>
              </div>

              <div class="field is-hidden" id="eventDateFields">
                <div class="columns">
                  <div class="column">
                    <label for="" class="label">* Event Start Date</label>
                    <div class="control">
                      <input type="date" class="input" name="timelineStartDate" min="<?php echo date("Y-m-d"); ?>">
                    </div>
                  </div>
                  <div class="column">
                    <label for="" class="label">* Event End Date</label>
                    <div class="control">
                      <input type="date" class="input" name="timelineEndDate" min="<?php echo date("Y-m-d"); ?>">
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
                      <label class="label">Target Value Monetary</label>
                      <div class="control">
                        <input type="number" class="input" name="targetMonetary" value="">
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">Target Value Inkind</label>
                    <div class="control">
                      <input type="number" class="input" name="targetInkind" value="">
                    </div>
                  </div>
                </div>
                <div class="column">
                  <div class="field">
                    <label for="">Payment Accounts</label>
                    <div class="field">
                        <label class="label">GCash Account</label>
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
                      <label class="label">Paypal Account</label>
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
                      <label class="label">Maya Account</label>
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

                $event_start_date = $event['event_start_date'];
                $event_end_date = $event['event_end_date'];

                $et = $eventType == "blog" ? "Announcement" : "Charity Event";
                $tag = $eventType == "blog" ? "is-link" : "is-info";

                $st = 'Announced';
                $tag_st = 'is-info';

                if ($event_start_date != NULL && $event_end_date != NULL && $eventType == "event") {
                  $start = new DateTime($event_start_date);
                  $end = new DateTime($event_end_date);
                  $currentDate = new DateTime();
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
                }

                ?>
                <tr>
                  <td><?php echo $eventId; ?></td>
                  <td><?php echo $eventTitle; ?></td>
                  <td><?php echo $et; ?></td>
                  <td><span class="tag <?php echo $tag_st; ?>"><?php echo $st; ?></span></td>
                  <td>
                    <?php if ($status != "pending") { ?>
                      <button class="button is-info is-small" onclick="openModal('<?php echo $eventId; ?>')">View Details</button>
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

            $st = 'Announced';

            if ($event_start_date != NULL && $event_end_date != NULL && $event_type == "event") {
              $start = new DateTime($event_start_date);
              $end = new DateTime($event_end_date);
              $currentDate = new DateTime();
              if ($start > $currentDate) {
                  $st = 'Planned';
              } elseif ($start <= $currentDate && $end >= $currentDate) {
                  $st = 'Ongoing';
              } elseif ($start < $currentDate && $end < $currentDate) {
                  $st = 'Ended';
              }
            }
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
                          <?php if ($st != 'Ended') { ?>
                            <input name="editEventTitle" class="input is-small" type="text" placeholder="title" value="<?php echo $event_title; ?>">
                          <?php } else { ?>
                            <input name="editEventTitle" class="input is-small" type="text" placeholder="title" value="<?php echo $event_title; ?>" readonly>
                          <?php } ?>
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
                        <?php if ($st != 'Ended') { ?>
                          <textarea name="editEventDesc" id="" cols="20" rows="5" class="textarea is-small"><?php echo $event_desc; ?></textarea>
                        <?php } else { ?>
                          <textarea name="editEventDesc" id="" cols="20" rows="5" class="textarea is-small" readonly><?php echo $event_desc; ?></textarea>
                        <?php } ?>
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
                          <?php if ($st != 'Ended') { ?>
                            <input name="editEventStartDate" class="input is-small" type="date" placeholder="title" value="<?php echo $event_start_date; ?>">
                          <?php } else { ?>
                            <input name="editEventStartDate" class="input is-small" type="date" placeholder="title" value="<?php echo $event_start_date; ?>" readonly>
                          <?php } ?>
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
                          <?php if ($st != 'Ended') { ?>
                            <input name="editEventEndDate" class="input is-small" type="date" placeholder="title" value="<?php echo $event_end_date; ?>">
                          <?php } else { ?>
                            <input name="editEventEndDate" class="input is-small" type="date" placeholder="title" value="<?php echo $event_end_date; ?>" readonly>
                          <?php } ?>
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
                          <?php if ($st != 'Ended') { ?>
                            <input name="editTargetInkind" class="input is-small" type="number" placeholder="title" value="<?php echo $target_inkind; ?>">
                          <?php } else { ?>
                            <input name="editTargetInkind" class="input is-small" type="number" placeholder="title" value="<?php echo $target_inkind; ?>" readonly>
                          <?php } ?>
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
                          <?php if ($st != 'Ended') { ?>
                            <input name="editTargetFunds" class="input is-small" type="number" placeholder="title" value="<?php echo $target_funds; ?>">
                          <?php } else { ?>
                            <input name="editTargetFunds" class="input is-small" type="number" placeholder="title" value="<?php echo $target_funds; ?>" readonly>
                          <?php } ?>
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
                  <?php if ($st != 'Ended') { ?>
                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <label class="label is-small">Reupload Images</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                          <input name="editImages[]" class="input is-small" type="file" multiple accept=".png, .jpg, .jpeg">
                        </div>
                      </div>
                    </div>
                  </div>
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
                </section>
                <footer class="modal-card-foot">
                  <?php if ($st != 'Ended') { ?>
                    <button class="button is-success is-small" name="saveEditTimeline" onclick="onSaveModal('<?php echo $event_id; ?>'); return false;">Save</button>
                    <button class="button is-danger is-small" name="deleteEditTimeline" onclick="onDeleteModal('<?php echo $event_id; ?>'); return false;">Delete</button>
                  <?php } ?>
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
                      console.log(response.data);
                      if (success) {
                        Swal.fire('Success', 'Timeline post edited successfully. Wait for the confirmation of the admin', 'success')
                        .then(() => {
                          window.location.href = '../../p/org/dashboard.php';
                        });
                      } else {
                        Swal.fire('Error', message, 'error')
                        .then(() => {
                          window.location.href = '../../p/org/dashboard.php';
                        });
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

        <div id="rating-tab" class="content is-hidden">
          <?php 
            $ratedover = $db->query("SELECT ROUND(AVG(rating), 1) AS overall FROM `tbldonorrating` WHERE org_id = $org_id");
            if ($ratedover) {
              $overall = mysqli_fetch_assoc($ratedover);
              $overall_rating = $overall['overall'];

              $starIcon = '<i class="fa fa-star"></i>';
              $emptyStarIcon = '<i class="fa fa-star-o"></i>';

              $starsHtml = '';
              for ($i = 1; $i <= 5; $i++) {
                  if ($i <= $overall_rating) {
                      $starsHtml .= $starIcon;
                  } else {
                      $starsHtml .= $emptyStarIcon;
                  }
              }

            }
          ?>
          <h1 class="title has-text-centered">Rate and Reviews <span class="tag is-info is-large"><?php echo $overall_rating . " " . $starsHtml; ?></span> </h1>
          
          
          <div class="columns is-multiline">
          <?php 
            $rating = $db->query("SELECT * FROM `tbldonorrating` WHERE org_id = $org_id");
            if ($rating->num_rows > 0) {
              while ($rates = mysqli_fetch_assoc($rating)) {
                $nameid = $rates['donor_id'];

                $selectName = $db->query("SELECT donor_name FROM `tbldonors` WHERE donor_id = $nameid");

                if ($selectName) {
                  $donorss = mysqli_fetch_assoc($selectName);
                  $donor_name = $donorss['donor_name'];
                }

                $stars = $rates['rating'];
                $review = $rates['review'];
                $count = '';
  
                for ($i = 1; $i <= $stars; $i++) {
                  $count .= '<i class="fa fa-star"></i>';
                }
    
                for ($i = $stars + 1; $i <= 5; $i++) {
                  $count .= '<i class="fa fa-star-o"></i>';
                }
          ?>
            <div class="column is-one-third">
              <article class="message">
                <div class="message-header">
                  <p><?php echo $donor_name . " " . $stars . " / 5 " . $count; ?></p>
                </div>
                <div class="message-body">
                  <p><?php echo $review; ?></p>
                </div>
              </article>
            </div>
          <?php 
              }
            }
          ?>
          </div>

        </div>

        <div id="monetaryhistory-tab" class="content is-hidden">
          <?php 
            $array_monetary = [];
            $fund = $db->query("SELECT * FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'monetary'");
            if ($fund) {
              while ($funds = $fund->fetch_assoc()) {
                $array_monetary[] = $funds;
              }
            }
          ?>

          <h1 class="title has-text-centered">Monetary Transaction History</h1>
          <div class="box">
            <table class="table is-striped is-fullwidth">
              <thead>
                <tr>
                  <th>Donation ID</th>
                  <th>Charity Event</th>
                  <th>Donor</th>
                  <th>Amount</th>
                  <th>Date Donated</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach ($array_monetary as $data_funds) {
                    $donation_id = $data_funds['donation_id'];
                    $donation_date = $data_funds['donation_date'];
                    $donation_amount = $data_funds['donation_amount'];
                    $status = $data_funds['status'];

                    $donor_id = $data_funds['donor_id'];
                    $event_id = $data_funds['event_id'];

                    $events = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id'");
                    if ($events) {
                      $event_assoc = $events->fetch_assoc();
                      $event_title = $event_assoc['event_title'];
                    }

                    $donors = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
                    if ($donors) {
                      $donor_assoc = $donors->fetch_assoc();
                      $donor_name = $donor_assoc['donor_name'];
                    }
                ?>
                <tr>
                  <td><?php echo $donation_id; ?></td>
                  <td><?php echo $event_title; ?></td>
                  <td><?php echo $donor_name; ?></td>
                  <td><?php echo "₱" . number_format($donation_amount); ?></td>
                  <td><?php echo $donation_date; ?></td>
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
								$donation_amount = $data_funds['donation_amount'];
								$donation_date = $data_funds['donation_date'];
								$event_id = $data_funds['event_id'];

                $donor_id = $data_funds['donor_id'];
                $event_id = $data_funds['event_id'];

                $events = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id'");
                if ($events) {
                  $event_assoc = $events->fetch_assoc();
                  $event_title = $event_assoc['event_title'];
                }

                $donors = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
                if ($donors) {
                  $donor_assoc = $donors->fetch_assoc();
                  $donor_name = $donor_assoc['donor_name'];
                  $donor_address = $donor_assoc['donor_address'];
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
                    <h1 class="h4">Donor Monetary Donation Details</h1>
										<p>
											<!-- Payment Method ID -->
                      <strong> Donor Name: </strong> <?php echo $donor_name; ?> <br>
											<strong> Donated Amount: </strong> <?php echo "₱" . number_format($donation_amount); ?> <br>
											<strong> Date Donated: </strong> <?php echo $donation_date; ?> <br>
                      <strong> Address: </strong> <?php echo $donor_address; ?> 
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

        <div id="inkindhistory-tab" class="content is-hidden">
          <?php 
            $array_inkind = [];
            $inkind = $db->query("SELECT * FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'inkind'");
            if ($inkind) {
              while ($inkinds = $inkind->fetch_assoc()) {
                $array_inkind[] = $inkinds;
              }
            }
          ?>
          <h1 class="title has-text-centered">Inkind Transaction History</h1>
          <div class="box">
            <table class="table is-striped is-fullwidth">
              <thead>
                <tr>
                  <th>Donation ID</th>
                  <th>Donor Name</th>
                  <th>Charity Event</th>
                  <th>Item Name</th>
                  <th>Quantity</th>
                  <th>Date Donated</th>
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
                    $donor_id = $data_inkind['donor_id'];
                    $event_id = $data_inkind['event_id'];
                    $status = $data_inkind['status'];

                    $events = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id'");
                    if ($events) {
                      $event_assoc = $events->fetch_assoc();
                      $event_title = $event_assoc['event_title'];
                    }
    
                    $donors = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
                    if ($donors) {
                      $donor_assoc = $donors->fetch_assoc();
                      $donor_name = $donor_assoc['donor_name'];
                    }
                ?>
                <tr>
                  <td><?php echo $donation_id; ?></td>
                  <td><?php echo $donor_name; ?></td>
                  <td><?php echo $event_title; ?></td>
                  <td><?php echo $donation_name; ?></td>
                  <td><?php echo number_format($donation_amount); ?></td>
                  <td><?php echo $donation_date; ?></td>
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
								$status = $data_inkind['status'];
								$event_id = $data_inkind['event_id'];

                $donors = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
                if ($donors) {
                  $donor_assoc = $donors->fetch_assoc();
                  $donor_name = $donor_assoc['donor_name'];
                  $donor_address = $donor_assoc['donor_address'];
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
										<h1 class="h4">Donor Inkind Donation Details</h1>
                    <p>
                      <strong> Donor Name: </strong> <?php echo $donor_name; ?> <br>
                      <strong> Address: </strong> <?php echo $donor_address; ?>
                    </p>
										<p>
											<strong> Item Name: </strong> <?php echo $donation_name; ?> <br>
											<strong> Description: </strong> <br> <?php echo $donation_desc; ?> <br>
											<strong> Category: </strong> <?php echo $donation_cate; ?> <br>
											<strong> Donated Quantity: </strong> <?php echo number_format($donation_amount); ?> <br>
											<strong> Donation Date: </strong> <?php echo $donation_date; ?> <br>
											<strong> Status: </strong> <?php echo $status; ?>
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

        <div id="statistics-tab" class="content is-hidden">
          <h1 class="title has-text-centered">Statistics</h1>
          <div class="box">
            <div class="columns">
              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Total Number of Donors
                  		</div>
										</div>
										<div class="card-content">
                      <?php
                        $total_donors = $db->query("SELECT COUNT(DISTINCT donor_id) AS total_donors FROM `tbldonations` WHERE org_id = '$org_id'");

                        if ($total_donors) {
                          $total_donor_assoc = $total_donors->fetch_assoc();
                          $total_donated = $total_donor_assoc['total_donors'];
                        }
                      ?>
                      <p class="is-size-5"><?php echo $total_donated; ?></p>
										</div>
								</div>
              </div>
              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Total Finished Charity Events
                  		</div>
										</div>
										<div class="card-content">
                      <?php
                        $total_finished = $db->query("SELECT COUNT(event_id) AS finished_events FROM `tblorgtimeline` WHERE org_id = '$org_id' AND status = 'ended'");

                        if ($total_finished) {
                          $total_finished_assoc = $total_finished->fetch_assoc();
                          $total_finish = $total_finished_assoc['finished_events'];
                        }
                      ?>
                      <p class="is-size-5"><?php echo $total_finish; ?></p>
										</div>
								</div>
              </div>
              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Total Ongoing Charity Events
                  		</div>
										</div>
										<div class="card-content">
                      <?php
                        $total_ongoingg = $db->query("SELECT COUNT(event_id) AS ongoing_events FROM `tblorgtimeline` WHERE org_id = '$org_id' AND status = 'ongoing' AND event_type = 'event'");

                        if ($total_ongoingg) {
                          $total_ongoing_assoc = $total_ongoingg->fetch_assoc();
                          $total_ongoing = $total_ongoing_assoc['ongoing_events'];
                        }
                      ?>
                      <p class="is-size-5"><?php echo $total_ongoing; ?></p>
										</div>
								</div>
              </div>
              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Total List of Posted Timelines
                  		</div>
										</div>
										<div class="card-content">
                      <?php
                        $total_posted = $db->query("SELECT COUNT(event_id) AS posted_timelines FROM `tblorgtimeline` WHERE org_id = '$org_id'");

                        if ($total_posted) {
                          $total_post_assoc = $total_posted->fetch_assoc();
                          $total_post = $total_post_assoc['posted_timelines'];
                        }
                      ?>
                      <p class="is-size-5"><?php echo $total_post; ?></p>
										</div>
								</div>
              </div>
            </div>
          </div>
          <div class="box">
            <div class="columns">

              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Total Amount of Monetary Donations
                  		</div>
										</div>
										<div class="card-content">
                      <?php 
                        $tf = $db->query("SELECT SUM(donation_amount) AS total_funds FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'monetary'");

                        if ($tf) {
                          $tfa = $tf->fetch_assoc();
                          $total_funds = $tfa['total_funds'];
                        }
                      ?>
                      <p class="is-size-5"><?php echo "₱" . number_format($total_funds); ?></p>
										</div>
								</div>
              </div>

              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Average Monetary Donation
                  		</div>
										</div>
										<div class="card-content">
											<?php 
                        $average_monetary = $db->query("SELECT AVG(donation_amount) AS average_funds FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'monetary'");

                        if ($average_monetary) {
                          $ave_fund = $average_monetary->fetch_assoc();
                          $average_funds = $ave_fund['average_funds'];
                        }

                      ?>
                      <p class="is-size-5"><?php echo "₱" . number_format($average_funds); ?></p>
										</div>
								</div>
              </div>

              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Total Monetary Transaction
                  		</div>
										</div>
										<div class="card-content">
                      <?php
                        $total_transact = $db->query("SELECT COUNT(donor_id) AS total_transaction FROM `tbldonations` WHERE org_id = '$org_id' and donation_type = 'monetary'");

                        if ($total_transact) {
                          $total_transact_assoc = $total_transact->fetch_assoc();
                          $total_monetary_transaction = $total_transact_assoc['total_transaction'];
                        }
                      ?>
                      <p class="is-size-5"><?php echo $total_monetary_transaction; ?></p>
										</div>
								</div>
              </div>

              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Monthly Monetary Donation
                  		</div>
										</div>
										<div class="card-content">
											<?php 
                      
                      $monthly_monetary = $db->query(
                        "SELECT " .
                        " DATE_FORMAT(donation_date, '%Y') AS donation_year," .
                        " DATE_FORMAT(donation_date, '%m') AS donation_month," .
                        " SUM(donation_amount) AS monthly_donation " .
                        "FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'monetary' " .
                        "GROUP BY DATE_FORMAT(donation_date, '%Y-%m')"
                      );

                      if ($monthly_monetary->num_rows > 0) {
                        $monthly_funds_assoc = $monthly_monetary->fetch_assoc();
                        $get_monthly_funds = $monthly_funds_assoc['monthly_donation'];
                      } else {
                        $get_monthly_funds = 0;
                      }
                  
                      ?>
                      <p class="is-size-5"><?php echo "₱" . number_format($get_monthly_funds); ?></p>
										</div>
								</div>
              </div>

            </div>
          </div>

          <div class="box">
            <div class="columns">

              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Total Amount of Inkind Donations
                  		</div>
										</div>
										<div class="card-content">
                      <?php 
                        $ti = $db->query("SELECT SUM(donation_amount) AS total_inkind FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'inkind'");

                        if ($ti) {
                          $tia = $ti->fetch_assoc();
                          $total_inkind = $tia['total_inkind'];
                        }
                      ?>
                      <p class="is-size-5"><?php echo number_format($total_inkind); ?></p>
										</div>
								</div>
              </div>

              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Average Inkind Donation
                  		</div>
										</div>
										<div class="card-content">
											<?php 
                        $average_inkind = $db->query("SELECT AVG(donation_amount) AS average_inkind FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'inkind'");

                        if ($average_inkind) {
                          $ave_inkind = $average_inkind->fetch_assoc();
                          $average_inkind = $ave_inkind['average_inkind'];
                        }

                      ?>
                      <p class="is-size-5"><?php echo number_format($average_inkind); ?></p>
										</div>
								</div>
              </div>

              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Total Inkind Transaction
                  		</div>
										</div>
										<div class="card-content">
                      <?php
                        $total_inkind = $db->query("SELECT COUNT(donor_id) AS total_transaction FROM `tbldonations` WHERE org_id = '$org_id' and donation_type = 'inkind'");

                        if ($total_inkind) {
                          $total_inkind_assoc = $total_inkind->fetch_assoc();
                          $total_inkind_transaction = $total_inkind_assoc['total_transaction'];
                        }
                      ?>
                      <p class="is-size-5"><?php echo $total_inkind_transaction; ?></p>
										</div>
								</div>
              </div>

              <div class="column">
                <div class="card has-background-info has-text-white">
										<div class="card-header">
											<div class="card-header-title has-text-white">
                    		Monthly Inkind Donation
                  		</div>
										</div>
										<div class="card-content">
											<?php 
                      
                      $monthly_inkind = $db->query(
                        "SELECT " .
                        " DATE_FORMAT(donation_date, '%Y') AS donation_year," .
                        " DATE_FORMAT(donation_date, '%m') AS donation_month," .
                        " SUM(donation_amount) AS monthly_donation " .
                        "FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'inkind' " .
                        "GROUP BY DATE_FORMAT(donation_date, '%Y-%m')"
                      );

                      if ($monthly_inkind->num_rows > 0) {
                        $monthly_inkind_assoc = $monthly_inkind->fetch_assoc();
                        $get_monthly_inkind = $monthly_inkind_assoc['monthly_donation'];
                      } else {
                        $get_monthly_inkind = 0;
                      }
                  
                      ?>
                      <p class="is-size-5"><?php echo number_format($get_monthly_inkind); ?></p>
										</div>
								</div>
              </div>

            </div>
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
