<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../../lib/imgs/charitease_icon.png">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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

    $id = $_SESSION['id'];
    $user = $_SESSION['user'];

    $org_id = $_GET['oid'];

    $getOrgText = "SELECT * FROM `tblorgs` WHERE org_id = $org_id";
		$resultOrg = mysqli_query($conn, $getOrgText);

		if (mysqli_num_rows($resultOrg) > 0) {
			$org = mysqli_fetch_assoc($resultOrg);
		}

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      if (isset($_POST["reviewSubmit"])) {
        if (isset($_POST['selectedStars'], $_POST['reviewText'])) {

          $stars = $_POST['selectedStars'];
          $review = $_POST['reviewText'];
          $timestamp = date('Y-m-d H:i:s');

          $reviewText = "INSERT INTO `tbldonorrating` 
            (donor_id, org_id, rating, review, timestamp) VALUES
            ('$id', '$org_id', '$stars', '$review', '$timestamp')";

          $result = mysqli_query($conn, $reviewText);
          if ($result) {
            $_SESSION["status"] = "Rating Success";
            $_SESSION["status_text"] = "Rating and Review Send Successfully!";
            $_SESSION["status_code"] = "success";
            header("Location: rating.php?oid=$org_id");
            die();
          } else {
            $_SESSION["status"] = "Rating Failed";
            $_SESSION["status_text"] = "Rating and Review Send Failed!";
            $_SESSION["status_code"] = "error";
            header("Location: rating.php?oid=$org_id");
            die();
          }
        }
      }

      if (isset($_POST["reviewEditSubmit"])) {
        if (isset($_POST['selectedStars'], $_POST['reviewText'])) {

          $editedStars = $_POST['selectedStars'];
          $editedReview = $_POST['reviewText'];
          
          $updatedText = "UPDATE `tbldonorrating` SET 
            rating = '$editedStars', 
            review = '$editedReview' 
            WHERE donor_id = '$id' AND org_id = '$org_id'";
          
          $updateResult = mysqli_query($conn, $updatedText);

          if ($updateResult) {
            $_SESSION['status'] = 'Edit Review Success';
            $_SESSION['status_text'] = 'Successfully edited the review!';
            $_SESSION['status_code'] = 'success';
            header("Location: rating.php?oid=$org_id");
            die();
          } else {
            $_SESSION['status'] = 'Edit Review Failed';
            $_SESSION['status_text'] = 'Error editing the review: ' . mysqli_error($conn);
            $_SESSION['status_code'] = 'error';
            header("Location: rating.php?oid=$org_id");
            die();
          }
        }
      }

      if (isset($_POST["donateSubmit"])) {
        header("Location: donation.php?oid=$org_id");
        die();
      }

    }
  ?>
  
  <section class="section">
  <div class="container">
    <h1 class="title">Rate and Review</h1>
    <div class="columns">
      <div class="column">
        <h2 class="subtitle"><?php echo $org['org_name']; ?></h2>
        <div class="card">
          <div class="card-content">
            <div class="content">
              <p><?php echo $org['org_description']; ?></p>
              <!--
              <div class="tags">
                <span class="tag is-primary">Healthcare</span>
                <span class="tag is-secondary">Children</span>
                <span class="tag is-secondary">Education</span>
              </div>
              -->
            </div>
          </div>
        </div>
        <div class="box">
          <form method="POST">

            <?php 
              $donation_sql = "SELECT * FROM `tbldonations` WHERE donor_id='$id' AND org_id='$org_id'";
              $donation_result = mysqli_query($conn, $donation_sql);

             if ($donation_result->num_rows > 0) {
                // Donor has already donated, show rating button
                
                // checking if donor is already rate and comment!
                $rateText = "SELECT * FROM `tbldonorrating` WHERE donor_id='$id' AND org_id='$org_id'";
                $rateResult = mysqli_query($conn, $rateText);

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
            <h3 class="subtitle"><?php echo $labels; ?> a Review</h3>
            <div class="field">
              <label class="label">Rating</label>
              <div class="control">
                <div class="select">
                  <select name="selectedStars">
                    <option value="<?php echo $rateStars; ?>"><?php echo $rateStars; ?> stars</option>
                    <option value="5">5 stars</option>
                    <option value="4">4 stars</option>
                    <option value="3">3 stars</option>
                    <option value="2">2 stars</option>
                    <option value="1">1 star</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label">Review</label>
              <div class="control">
                <textarea class="textarea" placeholder="Write your review here" name="reviewText"><?php echo $rateReview; ?></textarea>
              </div>
            </div>

            <div class="field">
              <div class="control">
                <?php if (!$editMode) { ?>
                  <button class="button is-primary" type="submit" name="reviewSubmit">Submit Review</button>
                <?php } else { ?>
                  <button class="button is-primary" type="submit" name="reviewEditSubmit">Submit Edit Review</button>
                <?php } ?>
              </div>
            </div>
            <?php 
              } else {
            ?>
              <label class="label">Donate first to send a Rating & Review</label>
              <button class="button is-primary" type="submit" name="donateSubmit">Donate Now!</button>
            <?php
              }
            ?>
          </form>
        </div>
      </div>

      <!-- REVIEWS -->
      <div class="column">
        <h3 class="subtitle">Reviews</h3>
        <?php

          $donorRatingText = "SELECT * FROM `tbldonorrating` WHERE org_id = $org_id AND donor_id = $id";
          $resultRating = mysqli_query($conn, $donorRatingText);

          // Check if the user has already submitted a rating and review
          if ($resultRating && mysqli_num_rows($resultRating) > 0) {
            $row = mysqli_fetch_assoc($resultRating);
            $nameid = $row['donor_id'];

            $selectName = "SELECT donor_name FROM `tbldonors` WHERE donor_id = $nameid";
            $resultName = mysqli_query($conn, $selectName);
            $name = '';

            $reviewId = $row['rating_id'];

            if ($resultName && mysqli_num_rows($resultName) > 0) {
              $nameRow = mysqli_fetch_assoc($resultName);
              $name = $nameRow['donor_name'];
            }

            $stars = $row['rating'];
            $review = $row['review'];
            $count = '';

            for ($i = 1; $i <= $stars; $i++) {
              $count .= '<i class="fa fa-star"></i>';
            }

            for ($i = $stars + 1; $i <= 5; $i++) {
              $count .= '<i class="fa fa-star-o"></i>';
            }
        ?>
          <article class="message">
            <div class="message-header">
              <p>Your Review</p>
              <button onclick="confirmDelete(<?php echo $reviewId; ?>)" class="delete"></button>
            </div>
            <div class="message-body">
              <article class="media">
                <figure class="media-left">
                  <p class="image is-64x64">
                    <img src="https://bulma.io/images/placeholders/128x128.png" alt="Reviewer">
                  </p>
                </figure>
                <div class="media-content">
                  <div class="content">
                    <p>
                      <strong><?php echo $name; ?></strong> <small><?php echo $count; ?></small>
                      <br>
                      <?php echo $review; ?>
                    </p>
                  </div>
                </div>
              </article>
            </div>
          </article>
        <?php 
          }

          $donorRatingText = "SELECT * FROM `tbldonorrating` WHERE org_id = $org_id";
          $resultRating = mysqli_query($conn, $donorRatingText);

            while ($row = $resultRating->fetch_assoc()) {
              $nameid = $row['donor_id'];

              if ($nameid !== $id) {
              $selectName = "SELECT donor_name FROM `tbldonors` WHERE donor_id = $nameid";
              $resultName = mysqli_query($conn, $selectName);
              $name = '';

              if ($resultName && mysqli_num_rows($resultName) > 0) {
                $nameRow = mysqli_fetch_assoc($resultName);
                $name = $nameRow['donor_name'];
              }

              $stars = $row['rating'];
              $review = $row['review'];
              $count = '';

              for ($i = 1; $i <= $stars; $i++) {
                $count .= '<i class="fa fa-star"></i>';
              }
  
              for ($i = $stars + 1; $i <= 5; $i++) {
                $count .= '<i class="fa fa-star-o"></i>';
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
                  <strong><?php echo $name; ?></strong> <small><?php echo $count; ?></small>
                  <br>
                  <?php echo $review; ?>
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

<script>
  function deleteReview(reviewId) {
    var formData = new FormData();
    formData.append('reviewId', reviewId);

    axios.post('../../lib/php/delete_review.php', formData)
      .then(function (response) {
        // Handle success response
        console.log(response);
        // Display success message or perform any necessary actions
        swal('Success', 'Review deleted', 'success').then(function () {
          // Redirect to the desired location
          window.location.href = 'rating.php?oid=<?php echo $org_id; ?>';
        });
      })
      .catch(function (error) {
        // Handle error response
        console.error(error);
        // Display error message or perform any necessary actions
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