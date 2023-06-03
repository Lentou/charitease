<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../../lib/imgs/charitease_icon.png">

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
    if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'admin')) {
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
  <!-- todo tomorrow 
    
    general tabs:
    tab for profile page

    verify tab list:

    verify permits table tab
    post timeline verification table tab
    verify donation tab
    
    list tabs:

    tab for list of registered accounts
    tab for list of table of charities
    tab for list of table of donors

  -->

  <?php 
    include '../../lib/database.php';

    $db = new Database();
		$conn = $db->connect();

    $user = $_SESSION['user'];
    $id = $_SESSION['id'];

    $adminQuery = "SELECT * FROM `tbladmins` WHERE admin_id = '$id'";
    $adminResult = mysqli_query($conn, $adminQuery);

    if (mysqli_num_rows($adminResult) == 1) {
      $admin = mysqli_fetch_assoc($adminResult);
    }
  ?>
  <section class="hero is-danger">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Administrator Dashboard</h1>
				<h2 class="subtitle">Welcome back, <strong><?php echo $admin["admin_name"]; ?></strong></h2>
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
              <li><a href="#account-tab" class="is-active">Account</a></li>
              <li><a href="#password-tab">Password</a></li>
            </ul>
            <p class="menu-label">Validation</p>
            <ul class="menu-list">
              <li><a href="#donor-tab">Donor IDs</a></li>
              <li><a href="#charity-tab">Charity Permits</a></li>
              <li><a href="#timeline-tab">Charity Timeline</a></li>
              <li><a href="#donation-tab">Donor Donation</a></li>
            </ul>
            <p class="menu-label">Accounts</p>
            <ul class="menu-list">
              <li><a href="#org-list-tab">List of Charities</a></li>
              <li><a href="#donor-list-tab">List of Donors</a></li>
            </ul>
          </aside>
        </div>
      </div>

      <div class="column">
        <div id="account-tab" class="content">
          <div class="box">
            <h1 class="title has-text-centered">Admin Account Information</h1>
            <p>
              <strong>Name: </strong> <?php echo $admin["admin_name"]; ?><br>
              <strong>Email Address: </strong> <?php echo $admin["admin_email"]; ?>
            </p>
          </div>

          <div class="box">
            <h2 class="title">Edit Email and Name</h2>
            <form action="../../lib/php/adm_dashboard.php" method="POST">
              <div class="field">
                <label for="" class="label">Name</label>
                <div class="control">
                  <input type="text" class="input" placeholder="Edit your current name" name="adminName" value="<?php echo $admin["admin_name"]; ?>">
                </div>
              </div>
              <button class="button is-info" type="submit" name="editNameSubmit">Save Name</button>
            </form>
            <br>
            <form action="" method="POST">
              <div class="field">
                <label for="" class="label">Email</label>
                <div class="control">
                  <input type="text" class="input" placeholder="Type/Edit your current email" name="adminEmail" value="<?php echo $admin["admin_email"]; ?>">
                </div>
              </div>
              <div class="field">
                <label for="" class="label">Current Password</label>
                <div class="control">
                  <input type="password" class="input" placeholder="Type your current password" name="adminPass">
                </div>
              </div>

              <button class="button is-info" type="submit" name="editEmailSubmit">Save Email</button>
            </form>

          </div>
        </div>

        <div id="password-tab" class="content is-hidden">
          <div class="box">
            <h1 class="title has-text-centered">Edit Password</h1>
            <form action="../../lib/php/adm_dashboard.php" method="POST">
              <div class="field">
                <label for="" class="label">Current Password</label>
                <div class="control">
                  <input type="password" class="input" placeholder="Type your Current Password" name="currentPass">
                </div>
              </div>

              <div class="field">
                <label for="" class="label">New Password</label>
                <div class="control">
                  <input type="password" class="input" placeholder="Type your New Password" name="newPass">
                </div>
              </div>

              <div class="field">
                <label for="" class="label">Confirm Password</label>
                <div class="control">
                  <input type="password" class="input" placeholder="Type your Confirm Password" name="confirmPass">
                </div>
              </div>

              <button class="button is-info" type="submit" name="editPassSubmit">Save Password</button>
            </form>
          </div>
        </div>

        <!-- VALIDATION TABS -->
        <div id="timeline-tab" class="content is-hidden">
          <h1 class="subtitle has-text-centered">Validation Queue of Posting New/Edit Timeline</h1>
          <?php
          $listOfQueues = [];

          $queueQuery = "SELECT * FROM `tblorgqueuetimeline` WHERE queue_status = 'pending'";
          $queueResult = mysqli_query($conn, $queueQuery);

          if ($queueResult && $queueResult->num_rows > 0) {
            while ($queue = $queueResult->fetch_assoc()) {
              $listOfQueues[] = $queue;
            }
          }
          ?>

          <table>
            <thead>
              <tr>
                <th>Queue ID</th>
                <th>Charity Organization</th>
                <th>Post Type</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($listOfQueues as $event) {
                $queueId = $event['queue_id'];
                $queueTitle = $event['queue_title'];
                $queueDescription = $event['queue_description'];
                $queueType = $event['queue_type'];
                $org_id = $event['org_id'];

                $type = $queueType == "blog" ? "Announcement" : "Event";

                $orgsQuery = "SELECT * FROM `tblorgs` WHERE org_id = '$org_id'";
                $orgsResult = mysqli_query($conn, $orgsQuery);

                if ($orgsResult->num_rows > 0) {
                  $organization = mysqli_fetch_assoc($orgsResult);
                }
                ?>
                <tr>
                  <td><?php echo $queueId; ?></td>
                  <td><?php echo $organization["org_name"]; ?></td>
                  <td><?php echo $type; ?></td>
                  <td>
                    <button class="button is-info is-small" onclick="openModal('<?php echo $queueId; ?>')">View Details</button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

          <?php foreach ($listOfQueues as $event) {
            $queueId = $event['queue_id'];
            $queueTitle = $event['queue_title'];
            $queueDesc = $event['queue_description'];
            $queueType = $event['queue_type'] == "blog" ? "Announcement" : "Event";
            $queueStartDate = $event['queue_start_date'];
            $queueEndDate = $event['queue_end_date'];

            $currentInkind = $event['current_inkind'];
            $currentFunds = $event['current_funds'];

            $targetInkind = $event['target_inkind'];
            $targetFunds = $event['target_funds'];

            $queueStatus = $event['queue_status'];

            $queueEventType = $event['event_id'] == null ? "New" : "Edit";

            
          ?>
            <div class="modal" id="modal-<?php echo $queueId; ?>">
              <div class="modal-background"></div>
              <div class="modal-card">
                <header class="modal-card-head">
                  <p class="modal-card-title"><strong>Queue ID: </strong><?php echo $queueId; ?></p>
                  <button class="delete" onclick="closeModal('<?php echo $queueId; ?>')" aria-label="close"></button>
                </header>
                <section class="modal-card-body">
                  <p>
                    <strong>Post Type: </strong><?php echo $queueType; ?> [<?php echo $queueEventType; ?>] <br> <br>
                    <strong>Title: </strong><?php echo $queueTitle; ?> <br>
                    <strong>Description: </strong> <br> <?php echo $queueDesc; ?> <br>
                    <?php 
                      if ($event['queue_type'] == "event") {
                        if ($queueStartDate != NULL) {
                    ?>
                          <strong>Start Date: </strong> <?php echo $queueStartDate; ?> <br>
                    <?php 
                        }
                        if ($queueEndDate != NULL) {
                    ?>
                          <strong>End Date: </strong> <?php echo $queueEndDate; ?> <br>
                    <?php 
                        }
                        if ($targetInkind != 0) {
                    ?>
                          <strong>Target Inkind Amount: </strong> <?php echo $targetInkind; ?> <br>
                    <?php 
                        }
                        if ($targetFunds != 0) {
                    ?>
                          <strong>Target Monetary Amount: </strong> <?php echo $targetFunds; ?> <br>
                    <?php 
                      }
                    }
                    ?>
                  </p>
                  <?php
                    $imageG = "SELECT image_data, image_type FROM `tblimages` WHERE table_id = '$queueId' AND category = 'queue_image'";
                    $imageR = mysqli_query($conn, $imageG);

                    if (mysqli_num_rows($imageR) > 0) {
                      while ($imageRow = mysqli_fetch_assoc($imageR)) {
                        $imageData = $imageRow['image_data'];
                        $imageType = $imageRow['image_type'];

                        echo '<img src="data:image;base64,' . $imageData . '" alt="Event Image"><br>';
                      }
                    }
                  ?>
                </section>
                <footer class="modal-card-foot">
                  <form action="../../lib/php/adm_dashboard.php" method="POST">
                    <input type="hidden" name="queue_id" value="<?php echo $queueId; ?>">
                    <button class="button is-success is-small" type="submit" name="acceptTimeline">Accept</button>
                    <button class="button is-danger is-small" type="submit" name="denyTimeline">Deny</button>
                  </form>
                </footer>
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
          </script>
        </div>
       
        <div id="donor-tab" class="content is-hidden">
          <h1 class="title has-text-centered">Validation for Donor Registration</h1>
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
                $dq = "SELECT * FROM `tbldonors` WHERE is_approved = '0'";
                $dr = mysqli_query($conn, $dq);

                if ($dr->num_rows > 0) {
                  while ($donor = mysqli_fetch_assoc($dr)) {
              ?>
              <tr>
                <td><?php echo $donor['donor_id']; ?></td>
                <td><?php echo $donor['donor_name']; ?></td>
                <td><?php echo $donor['donor_type']; ?></td>
                <td><button class="button is-small is-info" onclick="openModalDonor('<?php echo $donor['donor_id']; ?>')">View Details</button></th>
              </tr>
              <?php 
                  }
                }
              ?>
            </tbody>
          </table>

          <?php 
          $dq = "SELECT * FROM `tbldonors` WHERE is_approved = '0'";
          $dr = mysqli_query($conn, $dq);

          if ($dr->num_rows > 0) {
            while ($donor = mysqli_fetch_assoc($dr)) {
              $dnr = $donor;
              $donor_id = $dnr['donor_id'];
              $donor_name = $dnr['donor_name'];
              $donor_contact_name = $dnr['donor_contact_name'];
              $donor_address = $dnr['donor_address'];
              $donor_type = $dnr['donor_type'];
              $org_type = $dnr['org_type'];
              $donor_phone = $dnr['donor_phone'];

              $userq = mysqli_query($conn, "SELECT * FROM `tblusers` WHERE user_id = '$donor_id'");
              if ($userq->num_rows > 0) {
                $user = mysqli_fetch_assoc($userq);
                $email = $user['email'];
              }
          ?>
          <div class="modal" id="modal-dr-<?php echo $donor_id; ?>">
            <div class="modal-background"></div>
            <div class="modal-card">
              <header class="modal-card-head">
                <p class="modal-card-title"><strong>Donor ID: </strong> <?php echo $donor_id; ?></p>
                <button class="delete" aria-label="close" onclick="closeModalDonor('<?php echo $donor_id; ?>')"></button>
              </header>
              <section class="modal-card-body">
                <p>
                  <strong>Email: </strong> <?php echo $email; ?> <br>
                  <strong>Donor Name: </strong> <?php echo $donor_name; ?> <br>
                  <strong>Donor Type: </strong> <?php echo $donor_type; ?> <br>
                  <strong>Address: </strong> <?php echo $donor_address; ?> <br>
                  <strong>Contact Phone: </strong> <?php echo $donor_phone; ?> <br>
                </p>
                <?php 
                  if ($donor_type == "Organization") {
                ?>
                <p>
                  <strong>Contact Person Name: </strong> <?php echo $donor_contact_name; ?> <br>
                  <strong>Organization Type: </strong> <?php echo $org_type; ?>
                </p>
                <?php
                  }
                ?>
                <p>Registration Permit or Valid IDs</p>
                <hr>
                <div class="columns is-multiline">
                  <?php
                    $imageG = "SELECT image_data, image_type FROM `tblimages` WHERE table_id = '$donor_id' AND category = 'donor_permit' AND permit_type = 'valid_ids'";
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
                <form action="" method="POST">
                  <button class="button is-success is-small" name="donorValidYes">Approve</button>
                  <button class="button is-danger is-small" name="donorValidNo">Deny</button>
                </form>
              </footer>
            </div>
          </div>
          <?php
            }
          }
          ?>
          <script>
            function openModalDonor(modalId) {
              const modal = document.getElementById(`modal-dr-${modalId}`);
              modal.classList.add("is-active");
            }

            function closeModalDonor(modalId) {
              const modal = document.getElementById(`modal-dr-${modalId}`);
              modal.classList.remove("is-active");
            }
          </script>
        </div>

        <div id="charity-tab" class="content is-hidden">
          <h1 class="title has-text-centered">Validation for Charity Registration</h1>
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
                $chq = "SELECT * FROM `tblorgs` WHERE is_approved = '0'";
                $chr = mysqli_query($conn, $chq);

                if ($chr->num_rows > 0) {
                  while ($orgs = mysqli_fetch_assoc($chr)) {
              ?>
              <tr>
                <td><?php echo $orgs['org_id']; ?></td>
                <td><?php echo $orgs['org_name']; ?></td>
                <td><?php echo $orgs['org_type']; ?></td>
                <td><button class="button is-small is-info" onclick="openModalOrg('<?php echo $orgs['org_id']; ?>')">View Details</button></td>
              </tr>
              <?php
                  }
                }
              ?>
            </tbody>
          </table>

          <?php
            $chq = "SELECT * FROM `tblorgs` WHERE is_approved = '0'";
            $chr = mysqli_query($conn, $chq);
          
            if ($chr->num_rows > 0) {
              while ($orgs = mysqli_fetch_assoc($chr)) {
                $org = $orgs;
                $org_id = $org['org_id'];
                $org_name = $org['org_name'];
                $org_person_name = $org['org_person_name'];
                $org_phone = $org['org_phone'];
                $org_address = $org['org_address'];
                $org_type = $org['org_type'];

                $userq = mysqli_query($conn, "SELECT * FROM `tblusers` WHERE user_id = '$org_id'");
                if ($userq->num_rows > 0) {
                  $user = mysqli_fetch_assoc($userq);
                  $email = $user['email'];
                }
          ?>
          <div class="modal" id="modal-org-<?php echo $org_id; ?>">
            <div class="modal-background"></div>
            <div class="modal-card">
              <header class="modal-card-head">
                <p class="modal-card-title"><strong>Org ID: </strong> <?php echo $org_id; ?></p>
                <button class="delete" aria-label="close" onclick="closeModalOrg('<?php echo $org_id; ?>')"></button>
              </header>
              <section class="modal-card-body">
                <p>
                  <strong>Email: </strong> <?php echo $email; ?> <br>
                  <strong>Charity Name: </strong> <?php echo $org_name; ?> <br>
                  <strong>Contact Person Name: </strong> <?php echo $org_person_name; ?> <br>
                  <strong>Phone Number: </strong> <?php echo $org_phone; ?> <br>
                  <strong>Address: </strong> <?php echo $org_address; ?> <br>
                  <strong>Charity Type: </strong> <?php echo $org_type; ?>
                </p>
                <p>Registration Permit</p>
                <hr>
                <div class="columns is-multiline">
                  <?php
                    $imageG = "SELECT image_data, image_type FROM `tblimages` WHERE table_id = '$org_id' AND category = 'org_permit' AND permit_type = 'permit'";
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
                <button class="button is-success is-small" name="orgValidYes">Approve</button>
                <button class="button is-danger is-small" name="orgValidNo">Deny</button>
              </footer>
            </div>
          </div>
          <?php
              }
            }
          ?>
          <script>
            function openModalOrg(modalId) {
              const modal = document.getElementById(`modal-org-${modalId}`);
              modal.classList.add("is-active");
            }

            function closeModalOrg(modalId) {
              const modal = document.getElementById(`modal-org-${modalId}`);
              modal.classList.remove("is-active");
            }
          </script>
        </div>

        <div id="donation-tab" class="content is-hidden">
          <p>Working in Progress..</p>
        </div>

        <div id="org-list-tab" class="content is-hidden">
          <p>Working in Progress..</p>
        </div>

        <div id="donor-list-tab" class="content is-hidden">
          <p>Working in Progress..</p>
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
