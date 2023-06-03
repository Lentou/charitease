<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../../lib/imgs/charitease_icon.png">
  <style>
  #bg {
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: 100% 100%;
  }
  </style>
  <script>
    window.onload = function() {
      // Paste the code snippet here
      const bgImages = [
        '../../lib/imgs/image1.jpg',
        '../../lib/imgs/image2.png',
        '../../lib/imgs/image3.png',
        '../../lib/imgs/image4.png',
        '../../lib/imgs/image5.jpg',
        '../../lib/imgs/image6.jpg'
      ];
      const randomBgImage = bgImages[Math.floor(Math.random() * bgImages.length)];
      document.getElementById('bg').style.backgroundImage = `url(${randomBgImage})`;
    };
  </script>

</head>

<body>
  <?php
    session_start();
    if (isset($_SESSION['user'])) {
      header('Location: ../../index.php');
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
                  <a href="../../p/dnr/messenger.php" class="navbar-item">Messenger</a>
                </div>
              </div>
        <?php 
            } else if ($_SESSION['user'] == 'charity') {
        ?>
          <!-- ORGANIZATION BUTTON -->
              <div class="navbar-item has-dropdown is-hoverable">
                <a href="" class="navbar-link">Charity</a>
                <div class="navbar-dropdown">
                  <a href="../../p/org/dashboard.php" class="navbar-item">Dashboard</a>
                  <a href="../../p/org/messenger.php" class="navbar-item">Messenger</a>
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
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['email'];
        $password = $_POST['password'];
        $select = $_POST['selection'];

        // Connect to the database
        include '../../lib/database.php';

        $db = new Database();
        $conn = $db->connect();

        if ($select == "admin") {
          $table = "tbladmins";
          $em = "admin_email";
          $pw = "admin_password";
        } else {
          $table = "tblusers";
          $em = "email";
          $pw = "password";
        }

        $sql = "SELECT * FROM `$table` WHERE `$em`='$username'";
        $result = mysqli_query($conn, $sql);

        // Check if the query returned any rows
        if (mysqli_num_rows($result) == 1) {

          $role = $_POST['selection'];
          $row = mysqli_fetch_assoc($result);

          if ($row[$pw] == $password) {
            if (array_key_exists("account_type", $row)) {
              if ($row["account_type"] == $role) {
                $_SESSION['user'] = $row['account_type'];
                $_SESSION['id'] = $row['user_id'];
  
                $_SESSION['status'] = "Login Success";
                $_SESSION['status_text'] = "Successfully Logged-In";
                $_SESSION['status_code'] = "success";
                header('Location: ../../index.php');
                die();
              } else {             
                $_SESSION['status'] = "Login Failed";
                $_SESSION['status_text'] = "Email not found!";
                $_SESSION['status_code'] = "error";
              }
            } else {
              $_SESSION['user'] = "admin";
              $_SESSION['id'] = $row['admin_id'];

              $_SESSION['status'] = "Login Success";
              $_SESSION['status_text'] = "Successfully Logged-In";
              $_SESSION['status_code'] = "success";
              header('Location: ../../index.php');
              die();
            }
          } else {
            $_SESSION['status'] = "Login Failed";
            $_SESSION['status_text'] = "Invalid Password!";
            $_SESSION['status_code'] = "error";
          }
        } else {
          $_SESSION['status'] = "Login Failed";
          $_SESSION['status_text'] = "Email not found!";
          $_SESSION['status_code'] = "error";
        }
      }
    ?>

  <section class="hero is-fullheight" id="bg">
    <div class="hero-body">
      <div class="container has-text-centered">
          <div class="column is-4 is-offset-4">
            
              <div class="box">
              <h3 class="title">Login</h3>
              <hr class="login-hr">
              <p class="subtitle" id="message" name="message">Please login to proceed</p>
                <form method="POST">
                  <div class="field">
                    <div class="control">
                      <input class="input is-medium" type="email" placeholder="Your Email" autofocus="" name="email">
                    </div>
                  </div>
                  <div class="field">
                    <div class="control">
                      <input class="input is-medium" type="password" placeholder="Your Password" name="password">
                    </div>
                  </div>
                  <div class="field">
                    <div class="control">

                    <div class="select is-medium">
                      <select name="selection">
                        <option value="donor">Donor</option>
                        <option value="charity">Charity</option>
                        <option value="admin">Admin</option>
                      </select>
                    </div>

                    </div>
                  </div>
                  <button class="button is-block is-link is-large is-fullwidth" type="submit">Login <i class="fa fa-sign-in" aria-hidden="true"></i></button>
                </form>
              </div>
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

</body>
</html>
