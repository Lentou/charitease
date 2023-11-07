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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;700&display=swap">

  <style>
    body {
      font-family: 'Red Hat Display', sans-serif;
    }
    .star {
      font-size: 2rem;
      cursor: pointer;
    }
    .star:hover {
      color: gold;
    }
    .star.selected {
      color: gold;
    }
  </style>


</head>

<body>
  <?php
    if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'donor')) {
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

    $id = $_SESSION['id'];
    $user = $_SESSION['user'];

    $org_id = $_GET['oid'];

    $getOrgText = "SELECT c.* FROM tblclients c JOIN tblusers u ON c.client_id = u.user_id WHERE c.client_id = '$org_id' AND u.account_type = 'c'";
    $resultOrg = $db->query($getOrgText);

    if ($resultOrg->num_rows > 0) {
      $org = $resultOrg->fetch_assoc();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      if (isset($_POST["review_submit"])) {
        if (isset($_POST["stars"], $_POST["review"])) {
          $stars = $_POST["stars"];
          $review = $_POST["review"];
          $timestamp = date('Y-m-d H:i:s');

          $reviewText = "INSERT INTO `tblratings` (
            donor_id,
            org_id,
            rating,
            review,
            timestamp
          ) VALUES (
            '$id',
            '$org_id',
            '$stars',
            '$review',
            '$timestamp'
          )";

          $resulte = $db->query($reviewText);
          
          $status = ($resulte) ? "success" : "error";
          $status_text = ($resulte) ? "Rating and Review Send Successfully!" : "Rating and Review Send Failed";
          $_SESSION["status"] = "Rating " . $status;
          $_SESSION["status_text"] = $status_text;
          $_SESSION["status_code"] = $status;
          location("rating.php?oid=$org_id");
        }
      }

      if (isset($_POST["review_edit"])) {
        if (isset($_POST["stars"], $_POST["review"])) {
          $stars = $_POST["stars"];
          $review = $_POST["review"];

          $updatedText = "UPDATE `tblratings` SET 
            rating = '$stars',
            review = '$review'
            WHERE donor_id = '$id' AND org_id = '$org_id'";
          
          $updateResult = $db->query($updatedText);

          $status = ($updateResult) ? "success" : "error";
          $status_text = ($updateResult) ? "Successfully edited the review!" : "Error editing the review";
          $_SESSION["status"] = "Rating " . $status;
          $_SESSION["status_text"] = $status_text;
          $_SESSION["status_code"] = $status;
          location("rating.php?oid=$org_id");
        }
      }

      if (isset($_POST["donate_submit"])) {
        location("timeline.php?oid=$org_id");
      }

    }

    $ratedover = $db->query("SELECT ROUND(AVG(rating), 1) AS overall FROM `tblratings` WHERE org_id = $org_id");
    if ($ratedover) {
      $overall = mysqli_fetch_assoc($ratedover);
      $overall_rating = $overall['overall'];

      $starIcon = '<span class="material-symbols-outlined" style="color:gold;">star</span>';
      $emptyStarIcon = '<span class="material-symbols-outlined" style="color:darkgray;">star</span>';

      // Generate the HTML for the star icons
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
  
  <section class="section has-background-info">
  <div class="container">
    <a href="donate.php" class="button is-pulled-left is-link is-small">Back</a>
    <h1 class="title has-text-white has-text-centered">Rate and Review</h1>
    <div class="columns">
      <div class="column">
        <h2 class="subtitle has-text-white"><?= $org['client_name']; ?></h2>
        <div class="card">
          <div class="card-content">
            <div class="content">
              <p><?= $org['client_bio']; ?></p>
              
              <span class="tag is-info is-large"><?= $overall_rating . " " . $starsHtml; ?></span>
            </div>
          </div>
        </div>
        <br>
        <div class="box">
          <form method="POST">

            <?php 
              $donation_sql = "SELECT * FROM `tbldonations` WHERE donor_id='$id' AND org_id='$org_id'";
              //$donation_result = mysqli_query($conn, $donation_sql);
              $donation_result = $db->query($donation_sql);

             if ($donation_result->num_rows > 0) {
                // Donor has already donated, show rating button
                
                // checking if donor is already rate and comment!
                $rateText = "SELECT * FROM `tblratings` WHERE donor_id='$id' AND org_id='$org_id'";
                //$rateResult = mysqli_query($conn, $rateText);
                $rateResult = $db->query($rateText);

                $rateStars = 0;
                $rateReview = "";
                $labels = "Write";
                $editMode = false;

                if ($rateResult->num_rows > 0) {
                  $ratedRow = mysqli_fetch_assoc($rateResult);
                  $rateStars = $ratedRow['rating'];
                  $rateReview = $ratedRow['review'];
                  $labels = "Edit";
                  $editMode = true;
                }
            ?>
            <h3 class="subtitle"><?= $labels; ?> a Review</h3>
            <div class="field">
              <label class="label">Rating</label>
              <div class="control">
                <!--<div class="select">
                  <select name="stars">
                    <option value=""> stars</option>
                    <option value="5">5 stars</option>
                    <option value="4">4 stars</option>
                    <option value="3">3 stars</option>
                    <option value="2">2 stars</option>
                    <option value="1">1 star</option>
                    <option value="0">0 star</option>
                  </select>
                </div>-->
                <div class="stars">
                  <span class="star" data-value="1">&#9733;</span>
                  <span class="star" data-value="2">&#9733;</span>
                  <span class="star" data-value="3">&#9733;</span>
                  <span class="star" data-value="4">&#9733;</span>
                  <span class="star" data-value="5">&#9733;</span>
                </div>
                <input type="hidden" id="rating" name="stars" value="<?= $rateStars; ?>">
              </div>
            </div>

            <div class="field">
              <label class="label">Review</label>
              <div class="control">
                <textarea class="textarea" placeholder="Write your review here" name="review"><?= $rateReview; ?></textarea>
              </div>
            </div>

            <div class="field">
              <div class="control">
                <?php if (!$editMode) { ?>
                  <button class="button is-primary" type="submit" name="review_submit">Submit Review</button>
                <?php } else { ?>
                  <button class="button is-primary" type="submit" name="review_edit">Submit Edit Review</button>
                <?php } ?>
              </div>
            </div>
            <?php 
              } else {
            ?>
              <label class="label">Donate first to send a Rating & Review</label>
              <button class="button is-primary" type="submit" name="donate_submit">Donate Now!</button>
            <?php
              }
            ?>
          </form>
        </div>
      </div>

      <!-- REVIEWS -->
      <div class="column">
        <h3 class="subtitle has-text-white">Reviews</h3>
        <?php

          $donorRatingText = "SELECT * FROM `tblratings` WHERE org_id = $org_id AND donor_id = $id";
          //$resultRating = mysqli_query($conn, $donorRatingText);
          $resultRating = $db->query($donorRatingText);

          // Check if the user has already submitted a rating and review
          if ($resultRating && $resultRating->num_rows > 0) {
            $row = mysqli_fetch_assoc($resultRating);
            $nameid = $row['donor_id'];

            $selectName = "SELECT client_name FROM `tblclients` WHERE client_id = $nameid";
            //$resultName = mysqli_query($conn, $selectName);
            $resultName = $db->query($selectName);
            $name = '';

            $reviewId = $row['rating_id'];

            if ($resultName && $resultName->num_rows > 0) {
              //$nameRow = mysqli_fetch_assoc($resultName);
              $nameRow = $resultName->fetch_assoc();
              $name = $nameRow['client_name'];
            }

            $stars = $row['rating'];
            $review = $row['review'];
            $count = '';

            for ($i = 1; $i <= $stars; $i++) {
              $count .= '<span class="material-symbols-outlined" style="color:gold;">star</span>';
            }

            for ($i = $stars + 1; $i <= 5; $i++) {
              $count .= '<span class="material-symbols-outlined" style="color:darkgray;">star</span>';
            }
        ?>
          <article class="message">
            <div class="message-header">
              <p>Your Review</p>
              <button onclick="confirmDelete(<?= $reviewId; ?>)" class="delete"></button>
            </div>
            <div class="message-body">
              <article class="media">
                <figure class="media-left">
                  <p class="image is-64x64">
                    <?php
                      $get_pic = $db->query("SELECT * FROM `tblimages` WHERE client_id = '$nameid' AND category = 'icon'");
                      if ($get_pic->num_rows > 0) {
                        $gett = $get_pic->fetch_assoc();
                        $imageData = $gett['image_data'];
                    ?>
                      <img src="data:image;base64,<?= $imageData ?>" alt="Event Image">
                    <?php 
                      } else {
                    ?>
                      <img src="https://bulma.io/images/placeholders/128x128.png" alt="<?= $name . ' logo';?>">
                    <?php 
                      }
                    ?>
                  </p>
                </figure>
                <div class="media-content">
                  <div class="content">
                    <p>
                      <strong><?= $name; ?></strong> <small><?= $stars . " / 5 " . $count; ?></small>
                      <br>
                      <?= $review; ?>
                    </p>
                  </div>
                </div>
              </article>
            </div>
          </article>
        <?php 
          }

          $donorRatingText = "SELECT * FROM `tblratings` WHERE org_id = $org_id";
          //$resultRating = mysqli_query($conn, $donorRatingText);
          $resultRating = $db->query($donorRatingText);

            while ($row = $resultRating->fetch_assoc()) {
              $nameid = $row['donor_id'];

              if ($nameid !== $id) {
              $selectName = "SELECT client_name FROM `tblclients` WHERE client_id = $nameid";
              $resultName = $db->query($selectName);
              $name = '';

              if ($resultName && $resultName->num_rows > 0) {
                $nameRow = $resultName->fetch_assoc();
                $name = $nameRow['client_name'];
              }

              $stars = $row['rating'];
              $review = $row['review'];
              $count = '';

              for ($i = 1; $i <= $stars; $i++) {
                $count .= '<span class="material-symbols-outlined" style="color:gold;">star</span>';
              }
  
              for ($i = $stars + 1; $i <= 5; $i++) {
                $count .= '<span class="material-symbols-outlined" style="color:darkgray;">star</span>';
              }
          ?>
        <div class="box">
          <article class="media">
            <figure class="media-left">
              <p class="image is-64x64">
                <img src="https://bulma.io/images/placeholders/128x128.png" alt="Reviewer">
              </p>
            </figure>
            <div class="media-content">
              <div class="content">
                <p>
                  <strong><?= $name; ?></strong> <small><?= $stars . " / 5 " . $count; ?></small>
                  <br>
                  <?= $review; ?>
                </p>
              </div>
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
</section>

  <script>
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const ratingValue = parseInt(star.getAttribute('data-value'));
                ratingInput.value = ratingValue;

                stars.forEach(s => {
                    if (parseInt(s.getAttribute('data-value')) <= ratingValue) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
            });
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

<script>
  function deleteReview(reviewId) {
    var formData = new FormData();
    formData.append('reviewId', reviewId);

    axios.post('action/a.review.php', formData)
      .then(function (response) {
        console.log(response);
        swal('Success', 'Review deleted', 'success').then(function () {
          window.location.href = 'rating.php?oid=<?php echo $org_id; ?>';
        });
      })
      .catch(function (error) {
        console.error(error);
        swal('Error', 'Failed to delete review', 'error');
      });
  }

  function confirmDelete(reviewId) {
    swal({
      title: 'Are you sure?',
      text: 'This action cannot be undone',
      icon: 'warning',
      buttons: ['Cancel', 'Delete'],
      dangerMode: true,
    }).then(function (confirm) {
      if (confirm) {
        deleteReview(reviewId);
      }
    });
  }
</script>


</body>
</html>