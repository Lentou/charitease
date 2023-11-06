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
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'charity')) {
        location('../reg/login.php');
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
    $org_id = $id;

    $getOrgText = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE c.client_id = '$id' AND u.account_type = 'c'";

    //$dq = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'd' AND c.is_approved = '0'";
    //$getOrgText = "SELECT * FROM `tblorgs` WHERE org_id = $id";
		//$resultOrg = mysqli_query($conn, $getOrgText);
    $resultOrg = $db->query($getOrgText);

    if ($resultOrg && $resultOrg->num_rows > 0) {
      $org = $resultOrg->fetch_assoc();
    }

    $getUserText = "SELECT * FROM `tblusers` WHERE user_id = '$id'";
    $resultUser = $db->query($getUserText);

    if ($resultUser && $resultUser->num_rows > 0) {
      $user = $resultUser->fetch_assoc();
    }

    //$getUserText = "SELECT * FROM `tblusers` WHERE user_id = $id";
		//$resultUser = mysqli_query($conn, $getUserText);

		//if (mysqli_num_rows($resultUser) > 0) {
			//$user = mysqli_fetch_assoc($resultUser);
		//}

  ?>

  <section class="hero is-link">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Charity Organization Dashboard</h1>
				<h2 class="subtitle">Welcome back, <strong><?= $org['client_name']; ?></strong></h2>
			</div>
		</div>
	</section>

  <section class="section">

    <div class="columns">

      <div class="column is-one-fifth">
        <div class="box">
          <aside class="menu">
            <p class="menu-label">Timeline</p>
            <ul class="menu-list">
              <li><a href="#post-tab" class="is-active"><span class="material-symbols-outlined">post_add</span>Post Timeline</a></li>
              <!--<li><a href="#payment-tab">Payment Method</a></li>-->
              <li><a href="#listpost-tab"><span class="material-symbols-outlined">feed</span>List Timeline</a></li>
              <li><a href="#donations-tab"><span class="material-symbols-outlined">volunteer_activism</span>List of Donations</a></li>
            </ul>
            <p class="menu-label">Others</p>
            <ul class="menu-list">
              <!--<li><a href="#totalamount-tab">Total Amount</a></li>-->
              <li><a href="#rating-tab"><span class="material-symbols-outlined">hotel_class</span>Rate and Review</a></li>
              <li><a href="#statistics-tab"><span class="material-symbols-outlined">monitoring</span>Statistics</a></li>
              <li><a href="#monetaryhistory-tab"><span class="material-symbols-outlined">request_quote</span>Monetary History</a></li>
              <li><a href="#inkindhistory-tab"><span class="material-symbols-outlined">note</span>Inkind History</a></li>
            </ul>
          </aside>
        </div>
      </div>

      <div class="column">

        <div id="post-tab" class="content">
            <div class="box">
                <h2 class="title has-text-centered">Post Timeline Event/Blog with Donation Goal</h2>
                <p><strong>* - Required</strong> You can post in this section about your events/blogs, You can set the Start date and End Date for the Event Date and so on.</p>
                <form action="action/a.timeline.php" method="POST" enctype="multipart/form-data">
            
                    <div class="field">
                        <div class="control">
                            <div class="select">
                                <select name="event_type" id="timelineTypeSelect" onchange="toggleFields()">
                                    <option>Select Post Type</option>
                                    <option value="a">Announcement</option>
                                    <option value="e">Charity Event</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <div class="columns">
                          <div class="column">
                            <label for="" class="label">* Title</label>
                            <div class="control">
                                <input type="text" class="input" name="event_title" placeholder="Type the title of the post">
                            </div>
                          </div>
                          <div class="column">
                            <label for="" class="label">Upload Images</label>
                            <div class="control">
                                <input type="file" class="input" name="event_images[]" multiple accept=".png, .jpg, .jpeg">
                            </div>
                          </div>
                        </div>

                    </div>

                    <div class="field">
                        <label for="" class="label">* Description</label>
                        <div class="control">
                            <textarea class="textarea" placeholder="Type your description here" name="event_description"></textarea>
                        </div>
                    </div>

                    <div class="field is-hidden" id="eventDateFields">
                        <div class="columns">
                            <div class="column">
                                <label for="" class="label">* Event Start Date</label>
                                <div class="control">
                                    <input type="date" class="input" name="event_start_date" min="<?= date("Y-m-d"); ?>">
                                </div>
                            </div>
                            <div class="column">
                                <label for="" class="label">* Event End Date</label>
                                <div class="control">
                                    <input type="date" class="input" name="event_end_date" min="<?= date("Y-m-d"); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field is-hidden" id="checkBoxFields">
                      <div class="columns">
                        <div class="column">
                                <div class="field">
                                    <label class="label">Target Value Monetary</label>
                                    <div class="control">
                                        <input type="number" class="input" name="targetMonetary" value="0">
                                    </div>
                                </div>
                        </div>
                        <div class="column">
                          <div class="field">
                                <label class="label">Target Value Inkind</label>
                                <div class="control">
                                    <input type="number" class="input" name="targetInkind" value="0">
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>

                    <button class="button is-info" type="submit" name="post_timeline">Submit</button>
                </form>
            </div>
            <script>
                function toggleFields() {
                  var selectElement = document.getElementById("timelineTypeSelect");
                  var eventDateFields = document.getElementById("eventDateFields");
                  var checkBoxFields = document.getElementById("checkBoxFields");
                  var targetFields = document.getElementById("targetFields");

                  if (selectElement.value === "e") {
                      eventDateFields.classList.remove("is-hidden");
                      checkBoxFields.classList.remove("is-hidden");
                      targetFields.classList.remove("is-hidden");
                  } else {
                      eventDateFields.classList.add("is-hidden");
                      checkBoxFields.classList.add("is-hidden");
                      targetFields.classList.add("is-hidden");
                  }
                }
            </script>
        </div>

        <div id="listpost-tab" class="content is-hidden">
          <div class="box">
          <h1 class="subtitle has-text-centered">List of Post Timelines</h1>
          <?php
            $listOfEvents = [];

            //$eventQuery = "SELECT * FROM `tblorgtimeline` WHERE org_id = '$id'";
            //$eventResult = mysqli_query($conn, $eventQuery);

            $eventQuery = "SELECT * FROM `tblevents` WHERE org_id = '$id'";
            $eventResult = $db->query($eventQuery);

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
                $status = $event['event_status'];

                $event_start_date = $event['event_start_date'];
                $event_end_date = $event['event_end_date'];

                $et = $eventType == "a" ? "Announcement" : "Charity Event";
                $tag = $eventType == "a" ? "is-link" : "is-info";

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
                  <td><?= $eventId; ?></td>
                  <td><?= $eventTitle; ?></td>
                  <td><?= $et; ?></td>
                  <td><span class="tag <?= $tag_st; ?>"><?= $st; ?></span></td>
                  <td>
                    <?php if ($status != "pending") { ?>
                      <button class="button is-info is-small" onclick="openModal('<?= $eventId; ?>')">View Details</button>
                    <?php } else { ?>
                      <p>On Process</p>
                    <?php } ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          </div>

          <?php foreach ($listOfEvents as $event) {
            $event_id = $event['event_id'];
            $event_title = $event['event_title'];
            $event_desc = $event['event_description'];
            $event_type = $event['event_type'];
            $event_start_date = $event['event_start_date'];
            $event_end_date = $event['event_end_date'];

            $collections = $db->query("SELECT * FROM `tblcollections` WHERE event_id = '$event_id'");

            $current_inkind = 0;
            $current_funds = 0;
            $target_inkind = 0;
            $target_funds = 0;

            if ($collections) {
                while ($collect = $collections->fetch_assoc()) {
                  $clt = $collect;
                  $current_inkind = $clt['current_inkind'];
                  $current_funds = $clt['current_funds'];

                  $target_inkind = $clt['target_inkind'];
                  $target_funds = $clt['target_funds'];
                }
            }

            $eventStatus = $event['event_status'];
            $type = $event['event_type'] == "a" ? "Announcement" : "Charity Event";

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
            <div class="modal" id="modal-<?= $event_id; ?>">
              <form id="form-<?= $event_id; ?>">
              <div class="modal-background"></div>
              <div class="modal-card">
                <header class="modal-card-head">
                  <p class="modal-card-title"><strong>Post ID: </strong><?= $event_id; ?></p>
                  <button class="delete" onclick="closeModal('<?= $event_id; ?>'); return false;" aria-label="close"></button>
                </header>
                <section class="modal-card-body">

                  <div class="field is-horizontal">
                    <div class="field-label is-small">
                      <input type="hidden" name="org_id" value="<?= $id; ?>">
                      <input type="hidden" name="event_id" value="<?= $event_id; ?>">
                      <label class="label is-small">Post Type</label>
                    </div>
                    <div class="field-body">
                      <div class="field">
                        <input type="hidden" name="event_type" value="<?= $event_type; ?>">
                        <p class="is-small"><?= $type; ?></p>
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
                            <input name="event_title" class="input is-small" type="text" placeholder="title" value="<?= $event_title; ?>">
                          <?php } else { ?>
                            <input name="event_title" class="input is-small" type="text" placeholder="title" value="<?= $event_title; ?>" readonly>
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
                          <textarea name="event_description" id="" cols="20" rows="5" class="textarea is-small"><?= $event_desc; ?></textarea>
                        <?php } else { ?>
                          <textarea name="event_description" id="" cols="20" rows="5" class="textarea is-small" readonly><?= $event_desc; ?></textarea>
                        <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <?php 
                    if ($event_type == "e") {
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
                            <input name="event_start_date" class="input is-small" type="date" placeholder="title" value="<?= $event_start_date; ?>">
                          <?php } else { ?>
                            <input name="event_start_date" class="input is-small" type="date" placeholder="title" value="<?= $event_start_date; ?>" readonly>
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
                            <input name="event_end_date" class="input is-small" type="date" placeholder="title" value="<?= $event_end_date; ?>">
                          <?php } else { ?>
                            <input name="event_end_date" class="input is-small" type="date" placeholder="title" value="<?= $event_end_date; ?>" readonly>
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
                          <input type="hidden" name="current_inkind" value="<?php echo $current_inkind; ?>">
                          <?php if ($st != 'Ended') { ?>
                            <input name="target_inkind" class="input is-small" type="number" placeholder="title" value="<?= $target_inkind; ?>">
                          <?php } else { ?>
                            <input name="target_inkind" class="input is-small" type="number" placeholder="title" value="<?= $target_inkind; ?>" readonly>
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
                          <input type="hidden" name="current_funds" value="<?= $current_funds; ?>">
                          <?php if ($st != 'Ended') { ?>
                            <input name="target_funds" class="input is-small" type="number" placeholder="title" value="<?= $target_funds; ?>">
                          <?php } else { ?>
                            <input name="target_funds" class="input is-small" type="number" placeholder="title" value="<?= $target_funds; ?>" readonly>
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
                    $imageG = "SELECT image_data FROM `tblimages` WHERE event_id = '$event_id' AND category = 'event_image'";
                    $imageR = $db->query($imageG);
                    
                    if ($imageR && $imageR->num_rows > 0) {
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
                    $imageG = "SELECT image_data FROM `tblimages` WHERE event_id = '$event_id' AND category = 'event_image'";
                    $imageR = mysqli_query($conn, $imageG);
                    
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
                  <?php if ($st != 'Ended') { ?>
                    <button class="button is-success is-small" name="saveEditTimeline" onclick="onSaveModal('<?= $event_id; ?>'); return false;">Save</button>
                    <button class="button is-danger is-small" name="deleteEditTimeline" onclick="onDeleteModal('<? $event_id; ?>'); return false;">Delete</button>
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
                  formData.append('post_status', 'edit');
                  

                  axios.post('action/a.timeline.php', formData)
                    .then((response) => {
                      const { success, message } = response.data;
                      console.log(response.data);
                      if (success) {
                        Swal.fire('Success', 'Timeline event edited successfully.', 'success')
                        .then(() => {
                          window.location.href = '../org/dashboard.php';
                        });
                      } else {
                        Swal.fire('Error', message, 'error')
                        .then(() => {
                          window.location.href = '../org/dashboard.php';
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
                  formData.append('post_status', 'delete');

                  axios.post('action/a.timeline.php', formData)
                    .then((response) => {
                      const { success, message } = response.data;
                      if (success) {
                        Swal.fire('Success', 'Timeline post deleted successfully.', 'success')
                        .then(() => {
                          window.location.href = '../org/dashboard.php';
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

        <div id="donations-tab" class="content is-hidden">
          <div class="box">
          <h1 class="title has-text-centered">Validation for Donations</h1>
          <table class="table">
            <thead>
              <tr>
                <th>Donation ID</th>
                <th>Event Title</th>
                <th>Donor Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              
                $donations = $db->query("SELECT * FROM `tbldonations` WHERE donation_status = 'approved' AND org_id = '$org_id'");

                if ($donations->num_rows > 0) {
                  while ($dons = $donations->fetch_assoc()) {
                    $donation_id = $dons['donation_id'];
                    $event_id = $dons['event_id'];
                    $donor_id = $dons['donor_id'];

                    $donors = $db->query("SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'd' AND c.client_id = '$donor_id'");
                    //$donors = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
                    //$events = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id'");
                    $events = $db->query("SELECT * FROM `tblevents` WHERE event_id = '$event_id'");

                    if ($events) {
                      $event = $events->fetch_assoc();
                      $event_title = $event['event_title'];
                    }

                    if ($donors) {
                      $donr = $donors->fetch_assoc();
                      $donor_name = $donr['client_name'];
                    }
              ?>
              <tr>
                <td><?= $donation_id; ?></td>
                <td><?= $event_title; ?></td>
                <td><?= $donor_name; ?></td>
                <td><button class="button is-small is-info" onclick="openModalDonation('<?php echo $donation_id; ?>')">View Details</button></td>
              </tr>
              <?php
                  }
                }
              ?>
            </tbody>
          </table>
          </div>

          <?php
          
            $donations = $db->query("SELECT * FROM `tbldonations` WHERE donation_status = 'approved' AND org_id = '$org_id'");

            if ($donations->num_rows > 0) {
                while ($dons = mysqli_fetch_assoc($donations)) {
                  $donation_id = $dons['donation_id'];
                  $donor_id = $dons['donor_id'];
                  $event_id = $dons['event_id'];

                  //$donors = $db->query("SELECT * FROM `tbldonors` WHERE donor_id = '$donor_id'");
                  //$events = $db->query("SELECT * FROM `tblorgtimeline` WHERE event_id = '$event_id'");

                  $donors = $db->query("SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE u.account_type = 'd' AND c.client_id = '$donor_id'");
                  $events = $db->query("SELECT * FROM `tblevents` WHERE event_id = '$event_id'");

                  if ($donors) {
                    $donr = $donors->fetch_assoc();
                    $donor_name = $donr['donor_name'];
                    $donor_phone = $donr['donor_phone'];
                  }

                  if ($events) {
                    $event = $events->fetch_assoc();
                    $event_title = $event['event_title'];
                    $event_desc = $event['event_description'];
                    $event_start_date = $event['event_start_date'];
                    $event_end_date = $event['event_end_date'];
                  }

                  $donation_name = $dons['donation_name'];
                  $donation_desc = $dons['donation_description'];
                  $donation_amount = $dons['donation_amount'];
                  $donation_date = $dons['donation_date'];
          ?>
          <div class="modal" id="modal-donation-<?= $donation_id; ?>">
            <div class="modal-background"></div>
            <div class="modal-card">
              <header class="modal-card-head">
                <p class="modal-card-title"><strong>Donation ID: </strong> <?= $donation_id; ?></p>
                <button class="delete" aria-label="close" onclick="closeModalDonation('<?= $donation_id; ?>')"></button>
              </header>
              <section class="modal-card-body">
                <p>
                  <strong>[Donor Details]</strong> <br> <br>
                  <strong>Donor: </strong> <?= $donor_name; ?> <br>
                  <strong>Donor Contact Phone: </strong> <?= $donor_phone; ?> <br>
                </p>
                <p>
                  <strong>[Event Details]</strong> <br> <br>
                  <strong>Event Title: </strong> <?= $event_title; ?> <br>
                  <strong>Event Description: </strong> <br>
                  <?php echo $event_desc; ?> <br>
                  <strong>Start Date: </strong> <?= $event_start_date; ?> <br>
                  <strong>End Date: </strong> <?= $event_end_date; ?>
                </p>
                <p>
                  <strong>[Item Details]</strong> <br> <br>
                  <strong>Item Name: </strong> <?= $donation_name; ?> <br>
                  <strong>Quantity: </strong> <?= $donation_amount; ?> <br>
                  <strong>Description: </strong> <br>
                  <?= $donation_desc; ?> <br>
                  <strong>Delivery Date: </strong> <?php echo $donation_date; ?>
                </p>
                <p>
                  <strong>[Images]</strong>
                </p>
                <hr>
                <div class="columns is-multiline">
                  <?php
                    $imageG = "SELECT image_data FROM `tblimages` WHERE client_id = '$donation_id' AND category = 'donation_image'";
                    $imageR = $db->query($imageG);
                    
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
                <form action="" method="POST">
                  <input type="hidden" name="donation_id" value="<?= $donation_id; ?>">
                  <input type="hidden" name="org_id" value="<?= $org_id; ?>">
                  <input type="hidden" name="donor_id" value="<?= $donor_id; ?>">
                  <input type="hidden" name="event_id" value="<?= $event_id; ?>">
                  <button class="button is-success is-small" name="donationYes" type="submit">Approve</button>
                  <button class="button is-danger is-small" name="donationNo" type="submit">Deny</button>
                </form>
              </footer>
            </div>
          </div>
          <?php
              }
            }
          ?>
          <script>
            function openModalDonation(modalId) {
              const modal = document.getElementById(`modal-donation-${modalId}`);
              modal.classList.add("is-active");
            }

            function closeModalDonation(modalId) {
              const modal = document.getElementById(`modal-donation-${modalId}`);
              modal.classList.remove("is-active");
            }
          </script>
        </div>

        <div id="rating-tab" class="content is-hidden">
          <?php 
            $ratedover = $db->query("SELECT ROUND(AVG(rating), 1) AS overall FROM `tblratings` WHERE org_id = $org_id");
            if ($ratedover) {
              $overall = $ratedover->fetch_assoc();
              $overall_rating = $overall['overall'];

              $starIcon = '<span class="material-symbols-outlined" style="color:gold;">star</span>';
              $emptyStarIcon = '<span class="material-symbols-outlined" style="color:darkgray;">star</span>';

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
          <div class="box">
          <h1 class="title has-text-centered">Rate and Reviews <span class="tag is-large"><?= $overall_rating . " " . $starsHtml; ?></span> </h1>
          <hr>
          <div class="columns is-multiline">
          <?php 
            $rating = $db->query("SELECT * FROM `tblratings` WHERE org_id = $org_id");
            if ($rating->num_rows > 0) {
              while ($rates = $rating->fetch_assoc()) {
                $nameid = $rates['donor_id'];

                $selectName = $db->query("SELECT client_name FROM `tblclients` WHERE client_id = $nameid");

                if ($selectName) {
                  $donorss = mysqli_fetch_assoc($selectName);
                  $donor_name = $donorss['client_name'];
                }

                $stars = $rates['rating'];
                $review = $rates['review'];
                $count = '';
  
                for ($i = 1; $i <= $stars; $i++) {
                  $count .= '<span class="material-symbols-outlined" style="color:gold;">star</span>';
                }
    
                for ($i = $stars + 1; $i <= 5; $i++) {
                  $count .= '<span class="material-symbols-outlined" style="color:darkgray;">star</span>';
                }
          ?>
            <div class="column is-one-third">
              <article class="message">
                <div class="message-header">
                  <p><?= $donor_name . " " . $stars . "/5 <br>" . $count; ?></p>
                  
                </div>
                <div class="message-body">
                  <p><?= $review; ?></p>
                </div>
              </article>
            </div>
          <?php 
              }
            }
          ?>
          </div>
          </div>

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
                      <p class="is-size-5"><?= $total_donated; ?></p>
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
                        $total_finished = $db->query("SELECT COUNT(event_id) AS finished_events FROM `tblevents` WHERE org_id = '$org_id' AND event_status = 'ended'");

                        if ($total_finished) {
                          $total_finished_assoc = $total_finished->fetch_assoc();
                          $total_finish = $total_finished_assoc['finished_events'];
                        }
                      ?>
                      <p class="is-size-5"><?= $total_finish; ?></p>
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
                        $total_ongoingg = $db->query("SELECT COUNT(event_id) AS ongoing_events FROM `tblevents` WHERE org_id = '$org_id' AND event_status = 'ongoing' AND event_type = 'event'");

                        if ($total_ongoingg) {
                          $total_ongoing_assoc = $total_ongoingg->fetch_assoc();
                          $total_ongoing = $total_ongoing_assoc['ongoing_events'];
                        }
                      ?>
                      <p class="is-size-5"><?= $total_ongoing; ?></p>
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
                        $total_posted = $db->query("SELECT COUNT(event_id) AS posted_timelines FROM `tblevents` WHERE org_id = '$org_id'");

                        if ($total_posted) {
                          $total_post_assoc = $total_posted->fetch_assoc();
                          $total_post = $total_post_assoc['posted_timelines'];
                        }
                      ?>
                      <p class="is-size-5"><?= $total_post; ?></p>
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
                        $tf = $db->query("SELECT SUM(donation_amount) AS total_funds FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'm'");

                        if ($tf) {
                          $tfa = $tf->fetch_assoc();
                          $total_funds = $tfa['total_funds'];
                        }
                      ?>
                      <p class="is-size-5"><?= "₱" . number_format($total_funds); ?></p>
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
                      <p class="is-size-5"><?= "₱" . number_format($average_funds); ?></p>
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
                        $total_transact = $db->query("SELECT COUNT(donor_id) AS total_transaction FROM `tbldonations` WHERE org_id = '$org_id' and donation_type = 'm'");

                        if ($total_transact) {
                          $total_transact_assoc = $total_transact->fetch_assoc();
                          $total_monetary_transaction = $total_transact_assoc['total_transaction'];
                        }
                      ?>
                      <p class="is-size-5"><?= $total_monetary_transaction; ?></p>
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
                        "FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'm' " .
                        "GROUP BY DATE_FORMAT(donation_date, '%Y-%m')"
                      );

                      if ($monthly_monetary->num_rows > 0) {
                        $monthly_funds_assoc = $monthly_monetary->fetch_assoc();
                        $get_monthly_funds = $monthly_funds_assoc['monthly_donation'];
                      } else {
                        $get_monthly_funds = 0;
                      }
                  
                      ?>
                      <p class="is-size-5"><?= "₱" . number_format($get_monthly_funds); ?></p>
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
                        $ti = $db->query("SELECT SUM(donation_amount) AS total_inkind FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'i'");

                        if ($ti) {
                          $tia = $ti->fetch_assoc();
                          $total_inkind = $tia['total_inkind'];
                        }
                      ?>
                      <p class="is-size-5"><?= number_format($total_inkind); ?></p>
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
                        $average_inkind = $db->query("SELECT AVG(donation_amount) AS average_inkind FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'i'");

                        if ($average_inkind) {
                          $ave_inkind = $average_inkind->fetch_assoc();
                          $average_inkind = $ave_inkind['average_inkind'];
                        }

                      ?>
                      <p class="is-size-5"><?= number_format($average_inkind); ?></p>
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
                        $total_inkind = $db->query("SELECT COUNT(donor_id) AS total_transaction FROM `tbldonations` WHERE org_id = '$org_id' and donation_type = 'i'");

                        if ($total_inkind) {
                          $total_inkind_assoc = $total_inkind->fetch_assoc();
                          $total_inkind_transaction = $total_inkind_assoc['total_transaction'];
                        }
                      ?>
                      <p class="is-size-5"><?= $total_inkind_transaction; ?></p>
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
                        "FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'i' " .
                        "GROUP BY DATE_FORMAT(donation_date, '%Y-%m')"
                      );

                      if ($monthly_inkind->num_rows > 0) {
                        $monthly_inkind_assoc = $monthly_inkind->fetch_assoc();
                        $get_monthly_inkind = $monthly_inkind_assoc['monthly_donation'];
                      } else {
                        $get_monthly_inkind = 0;
                      }
                  
                      ?>
                      <p class="is-size-5"><?= number_format($get_monthly_inkind); ?></p>
										</div>
								</div>
              </div>

            </div>
          </div>


        </div>

        <div id="monetaryhistory-tab" class="content is-hidden">
          <?php 
            $array_monetary = [];
            $fund = $db->query("SELECT * FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'm'");
            if ($fund) {
              while ($funds = $fund->fetch_assoc()) {
                $array_monetary[] = $funds;
              }
            }
          ?>

          <div class="box">
            <h1 class="title has-text-centered">Monetary Transaction History</h1>
            <table class="table is-striped is-fullwidth">
              <thead>
                <tr>
                  <th>Donation ID</th>
                  <th>Charity Event</th>
                  <th>Donor</th>
                  <th>Amount</th>
                  <th>Date Donated</th>
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
                    $status = $data_funds['donation_status'];

                    $donor_id = $data_funds['donor_id'];
                    $event_id = $data_funds['event_id'];

                    $events = $db->query("SELECT * FROM `tblevents` WHERE event_id = '$event_id'");
                    if ($events) {
                      $event_assoc = $events->fetch_assoc();
                      $event_title = $event_assoc['event_title'];
                    }

                    $donors = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$donor_id'");
                    if ($donors) {
                      $donor_assoc = $donors->fetch_assoc();
                      $donor_name = $donor_assoc['client_name'];
                    }

                    $donate_status = ($status == "a") ? "Approved" : (($status == "p") ? "Pending" : "Rejected");
                ?>
                <tr>
                  <td><?= $donation_id; ?></td>
                  <td><?= $event_title; ?></td>
                  <td><?= $donor_name; ?></td>
                  <td><?= "₱" . number_format($donation_amount); ?></td>
                  <td><?= $donation_date; ?></td>
                  <td><?= $donate_status; ?></td>
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

                $events = $db->query("SELECT * FROM `tblevents` WHERE event_id = '$event_id'");
                if ($events) {
                  $event_assoc = $events->fetch_assoc();
                  $event_title = $event_assoc['event_title'];
                }

                $donors = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$donor_id'");
                if ($donors) {
                  $donor_assoc = $donors->fetch_assoc();
                  $donor_name = $donor_assoc['client_name'];
                  $donor_address = $donor_assoc['client_address'];
                }

						?>
							<div class="modal" id="modal-monetary-<?= $donation_id; ?>">
								<div class="modal-background"></div>
								<div class="modal-card">
									<header class="modal-card-head">
										<p class="modal-card-title">Donation ID: <?= $donation_id; ?></p>
										<button class="delete" aria-label="close" onclick="monetaryModal('<?= $donation_id; ?>', 1)"></button>
									</header>
									<section class="modal-card-body">
                    <h1 class="h4">Donor Monetary Donation Details</h1>
										<p>
											<!-- Payment Method ID -->
                      <strong> Donor Name: </strong> <?= $donor_name; ?> <br>
											<strong> Donated Amount: </strong> <?= "₱" . number_format($donation_amount); ?> <br>
											<strong> Date Donated: </strong> <?= $donation_date; ?> <br>
                      <strong> Address: </strong> <?= $donor_address; ?> 
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

        <div id="inkindhistory-tab" class="content is-hidden">
          <?php 
            $array_inkind = [];
            $inkind = $db->query("SELECT * FROM `tbldonations` WHERE org_id = '$org_id' AND donation_type = 'i'");
            if ($inkind) {
              while ($inkinds = $inkind->fetch_assoc()) {
                $array_inkind[] = $inkinds;
              }
            }
          ?>
          
          <div class="box">
            <h1 class="title has-text-centered">Inkind Transaction History</h1>
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
                    $status = $data_inkind['donation_status'];

                    $events = $db->query("SELECT * FROM `tblevents` WHERE event_id = '$event_id'");
                    if ($events) {
                      $event_assoc = $events->fetch_assoc();
                      $event_title = $event_assoc['event_title'];
                    }
    
                    $donors = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$donor_id'");
                    if ($donors) {
                      $donor_assoc = $donors->fetch_assoc();
                      $donor_name = $donor_assoc['client_name'];
                    }

                    $donate_status = ($status == "a") ? "Approved" : (($status == "p") ? "Pending" : "Rejected");
                ?>
                <tr>
                  <td><?= $donation_id; ?></td>
                  <td><?= $donor_name; ?></td>
                  <td><?= $event_title; ?></td>
                  <td><?= $donation_name; ?></td>
                  <td><?= number_format($donation_amount); ?></td>
                  <td><?= $donation_date; ?></td>
                  <td><?= $donate_status; ?></td>
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
								$donation_amount = $data_inkind['donation_amount'];
								$donation_date = $data_inkind['donation_date'];
								$status = $data_inkind['donation_status'];
								$event_id = $data_inkind['event_id'];

                $donors = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$donor_id'");
                if ($donors) {
                  $donor_assoc = $donors->fetch_assoc();
                  $donor_name = $donor_assoc['client_name'];
                  $donor_address = $donor_assoc['client_address'];
                }

								$timeline = $db->query("SELECT * FROM `tblevents` WHERE org_id = '$org_id' AND event_id = '$event_id'");
								if ($timeline) {
									$timeline_assoc = $timeline->fetch_assoc();
									$event_title = $timeline_assoc['event_title'];
									$event_desc = $timeline_assoc['event_description'];
								}
						?>
							<div class="modal" id="modal-inkind-<?= $donation_id; ?>">
								<div class="modal-background"></div>
								<div class="modal-card">
									<header class="modal-card-head">
										<p class="modal-card-title">Donation ID: <?= $donation_id; ?></p>
										<button class="delete" aria-label="close" onclick="inkindModal('<?= $donation_id; ?>', 1)"></button>
									</header>
									<section class="modal-card-body">
										<h1 class="h4">Donor Inkind Donation Details</h1>
                    <p>
                      <strong> Donor Name: </strong> <?= $donor_name; ?> <br>
                      <strong> Address: </strong> <?= $donor_address; ?>
                    </p>
										<p>
											<strong> Item Name: </strong> <?php echo $donation_name; ?> <br>
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
											$imageG = "SELECT image_data FROM `tblimages` WHERE client_id = '$donation_id' AND category = 'donation_image'";
                      $imageR = $db->query($imageG);
											
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
