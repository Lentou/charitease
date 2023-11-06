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
        '../lib/imgs/image1.webp',
        '../lib/imgs/image2.webp',
        '../lib/imgs/image3.webp',
        '../lib/imgs/image4.webp',
        '../lib/imgs/image5.webp',
        '../lib/imgs/image6.webp'
      ];
      const randomBgImage = bgImages[Math.floor(Math.random() * bgImages.length)];
      document.getElementById('bg').style.backgroundImage = `url(${randomBgImage})`;
    };
  </script>
  
</head>

<body>
  <?php
    if (isset($_SESSION['user'])) {
      header('Location: ../index.php');
      exit;
    }
  ?>

  <!-- HEADER -->
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <div class="navbar-start">
        <a href="" class="navbar-item">
          <img src="../lib/imgs/charitease_icon.png" alt="Logo">
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
            <a href="../index.php" class="navbar-item"><span class="material-symbols-outlined">home</span>Home</a>
            <a href="../reg/register.php" class="navbar-item"><span class="material-symbols-outlined">how_to_reg</span>Register</a>
            <a href="../reg/login.php" class="navbar-item"><span class="material-symbols-outlined">login</span>Login</a>
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['email'];
    $password = $_POST['password'];
    $select = $_POST['selection'];

    include '../lib/database.php';
    include '../lib/config.php';

    $db = new Database();

    if ($username != "" && $password != "") {
      $result = $db->query("SELECT * FROM `tblusers` WHERE email = '$username'");
      $resultt = $result->fetch_assoc();

      if ($result) {
        if ($result->num_rows > 0) {
          if (password_verify($password, $resultt["password"])) {
            $account_type = $resultt["account_type"];
            $user_id = $resultt["user_id"];

            $new_selection = ($select == "charity") ? "c" : (($select == "donor") ? "d" : "a");
            $new_account_type = ($account_type == "c") ? "charity" : (($account_type == "d") ? "donor" : "admin");

            if ($account_type == $new_selection) {

              $is_approved = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$user_id' AND is_approved = '1'");

              if ($is_approved) {
                if ($is_approved->num_rows > 0) {
                  $isa = $is_approved->fetch_assoc();

                  $_SESSION['name'] = $isa['client_name'];
                  $_SESSION['user'] = $new_account_type;
                  $_SESSION['id'] = $user_id;
                  $_SESSION['status'] = "Login Success";
                  $_SESSION['status_text'] = "Successfully Logged-In";
                  $_SESSION['status_code'] = "success";
                  location('../index.php');
                } else {
                  $login_error_message = "You are not verified yet as " . $new_selection;
                }
            } else {
              $login_error_message = "Email not found!";
            }
          } else {
            $login_error_message = "Invalid Password!";
          }
        } else {
          $login_error_message = "Email not found!";
        }
      } else {
        $login_error_message = "Email not found!";
      }

      $_SESSION['status'] = "Login Failed";
      $_SESSION['status_text'] = $login_error_message;
      $_SESSION['status_code'] = "error";
      }
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
                      <input class="input" type="email" placeholder="Your Email" autofocus="" name="email" autocomplete="off">
                    </div>
                  </div>
                  <div class="field">
                    <div class="control">
                      <input class="input" type="password" placeholder="Your Password" name="password">
                    </div>
                  </div>
                  <div class="field">
                    <div class="control">

                    <div class="select">
                      <select name="selection">
                        <option value="donor">Donor</option>
                        <option value="charity">Charity</option>
                        <option value="admin">Admin</option>
                      </select>
                    </div>

                    </div>
                  </div>
                  <button class="button is-block is-link is-fullwidth" type="submit">Login <i class="fa fa-sign-in" aria-hidden="true"></i></button>
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
