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

  <!-- Include Sweet Alert CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
  <!-- Include Sweet Alert JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

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
// connect to database
include '../../lib/database.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST["donorSubmit"])) {
    if (isset($_POST["donorName"], $_POST["donorEmail"], $_POST["donorAddress"], $_POST["donorPhone"], $_POST["donorPass"], $_POST["donorConfirmPass"])) {

      $donorName = mysqli_real_escape_string($conn, $_POST["donorName"]);
      $donorEmail = mysqli_real_escape_string($conn, $_POST["donorEmail"]);
      $donorAddress = mysqli_real_escape_string($conn, $_POST["donorAddress"]);
      $donorType = mysqli_real_escape_string($conn, $_POST["donorType"]);
      $donorContactName = null;
      $donorOrgType = "";
      $donorPhone = mysqli_real_escape_string($conn, $_POST["donorPhone"]);
      $donorPass = mysqli_real_escape_string($conn, $_POST["donorPass"]);
      $donorConfirmPass = mysqli_real_escape_string($conn, $_POST["donorConfirmPass"]);

      if ($donorPass !== $donorConfirmPass) {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Please confirm your password!";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
        die();
      }

      if ($donorType == "Organization") {
        if (isset($_POST["donorContactName"])) {
          $donorContactName = mysqli_real_escape_string($conn, $_POST["donorContactName"]);
        }
        if (isset($_POST["donorOrgType"])) {
          $donorOrgType = mysqli_real_escape_string($conn, $_POST["donorOrgType"]);
        }
      }

      $checkEmailText = "SELECT * FROM `tblusers` WHERE email='$donorEmail'";
      $checkResult = mysqli_query($conn, $checkEmailText);
  
      if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Email already registered";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
        die();
      }

      $hash_pass = $donorPass;
      $verify_pin = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
      $addUserText = "INSERT INTO `tblusers` (email, password, account_type, is_verified, verification_pin) 
                    VALUES ('$donorEmail', '$hash_pass', 'donor', '0', '$verify_pin')";
      if (mysqli_query($conn, $addUserText)) {
        $user_id = mysqli_insert_id($conn);

        // todo blob must be in database? or in temporary folder and then save
        if (isset($_FILES['donorPermit'])) {
          $files = $_FILES["donorPermit"];
          $checkimages = ["image/jpeg", "image/jpg", "image/png"];

          $insertImage = $conn->prepare("INSERT INTO `tblimages` (table_id, permit_type, category, image_name, image_type, image_data) VALUES (?, ?, ?, ?, ?, ?)");
          $insertImage->bind_param("isssss", $tableId, $permitType, $category, $imageName, $fileType, $imageData);

          for ($i = 0; $i < count($files['name']); $i++) {
              $tmpFilePath = $files["tmp_name"][$i];
              $fileType = $files["type"][$i];

              if (!empty($tmpFilePath) && in_array($fileType, $checkimages)) {
                  $tableId = $user_id;
                  $permitType = 'valid_ids';
                  $category = 'donor_permit';
                  $imageName = $files["name"][$i];
                  $imageData = base64_encode(file_get_contents(addslashes($tmpFilePath)));

                  $insertImage->execute();
              }
          }

          $insertImage->close();
      }

        $addDonorText = "INSERT INTO `tbldonors` (donor_id, donor_name, donor_contact_name, donor_address, donor_type, org_type, donor_phone, date_approved, is_approved)
                        VALUES ('$user_id', '$donorName', '$donorContactName', '$donorAddress', '$donorType', '$donorOrgType', '$donorPhone', 'null', '0')";
        
        if (mysqli_query($conn, $addDonorText)) {
          $_SESSION['status'] = "Registration Success";
          $_SESSION['status_text'] = "Donor Register Success! please wait for your verification";
          $_SESSION['status_code'] = "success";
          // todo pin alert
        } else {
          $query = "DELETE FROM `tblusers` WHERE user_id = $user_id";
          mysqli_query($conn, $query);

          $_SESSION['status'] = "Registration Failed";
          $_SESSION['status_text'] = "Donor registration failed! Please try again later";
          $_SESSION['status_code'] = "error";
        }

      } else {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Donor registration failed! Please try again later";
        $_SESSION['status_code'] = "error";
      }
    }
  }

  if (isset($_POST["orgSubmit"])) {
    if (isset($_POST["orgName"], $_POST["orgContactName"], $_POST["orgPhone"], $_POST["orgEmail"], $_POST["orgAddress"], $_POST["orgFoundingDate"], $_POST["orgType"], $_POST["orgPass"], $_POST["orgConfirmPass"])) {
      $orgName = mysqli_real_escape_string($conn, $_POST['orgName']);
      $orgContactName = mysqli_real_escape_string($conn, $_POST['orgContactName']);
      $orgEmail = mysqli_real_escape_string($conn, $_POST['orgEmail']);
      $orgPhone = mysqli_real_escape_string($conn, $_POST['orgPhone']);
      $orgAddress = mysqli_real_escape_string($conn, $_POST['orgAddress']);
      $orgFoundingDate = mysqli_real_escape_string($conn, $_POST['orgFoundingDate']);
      $orgType = mysqli_real_escape_string($conn, $_POST['orgType']);
      $orgPass = mysqli_real_escape_string($conn, $_POST['orgPass']);
      $orgConfirmPass = mysqli_real_escape_string($conn, $_POST['orgConfirmPass']);

      if ($orgPass !== $orgConfirmPass) {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Please confirm your password!";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
        die();
      }

      $checkEmailText = "SELECT * FROM `tblusers` WHERE email='$orgEmail'";
      $checkQuery = mysqli_query($conn, $checkEmailText);

      if (mysqli_num_rows($checkQuery) > 0) {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Email has already registered!";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
        die();
      }

      $hash_pass = $orgPass;
      $verify_pin = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

      $addUserText = "INSERT INTO `tblusers` (email, password, account_type, is_verified, verification_pin) VALUES ('$orgEmail', '$hash_pass', 'charity', '0', '$verify_pin')";
      if (mysqli_query($conn, $addUserText)) {
        $user_id = mysqli_insert_id($conn);

        // todo blob must be put in temporary image or stay in db?
        if (isset($_FILES['orgPermit'])) {
          $files = $_FILES["orgPermit"];
          $checkimages = ["image/jpeg", "image/jpg", "image/png"];

          $insertImage = $conn->prepare("INSERT INTO `tblimages` (table_id, permit_type, category, image_name, image_type, image_data) VALUES (?, ?, ?, ?, ?, ?)");
          $insertImage->bind_param("isssss", $tableId, $permitType, $category, $imageName, $fileType, $imageData);

          for ($i = 0; $i < count($files['name']); $i++) {
              $tmpFilePath = $files["tmp_name"][$i];
              $fileType = $files["type"][$i];

              if (!empty($tmpFilePath) && in_array($fileType, $checkimages)) {
                  $tableId = $user_id;
                  $permitType = 'permit';
                  $category = 'org_permit';
                  $imageName = $files["name"][$i];
                  $imageData = base64_encode(file_get_contents(addslashes($tmpFilePath)));

                  $insertImage->execute();
              }
          }

          $insertImage->close();
      }
   

        $addOrgText = 
          "INSERT INTO `tblorgs` (org_id, org_name, org_person_name, org_phone, org_address, is_approved, org_description, org_type, date_founded, date_approved) 
            VALUES ('$user_id', '$orgName', '$orgContactName', '$orgPhone', '$orgAddress', '0', 'null', '$orgType', '$orgFoundingDate', 'null')";

        if (mysqli_query($conn, $addOrgText)) {
          $_SESSION['status'] = "Registration Success";
          $_SESSION['status_text'] = "Charity Registration Success! please wait for verification!";
          $_SESSION['status_code'] = "success";

          // mail ();
          // todo pin alert
        } else {
          $removeUserText = "DELETE FROM `tblusers` WHERE user_id = $user_id";
          mysqli_query($conn, $removeUserText);

          $_SESSION['status'] = "Registration Failed";
          $_SESSION['status_text'] = "Charity Registration failed, please try again later!";
          $_SESSION['status_code'] = "error";
        }
      } else {
        $_SESSION['status'] = "Registration Failed";
        $_SESSION['status_text'] = "Charity Registration failed, please try again later!";
        $_SESSION['status_code'] = "error";
      }
    } else {
      $_SESSION['status'] = "Registration Failed";
      $_SESSION['status_text'] = "Please fill up the fields";
      $_SESSION['status_code'] = "error";
    }
  }
}

mysqli_close($conn);
?>

  <section class="section" id="bg">
    <div class="container">

      <div class="columns">
        <div class="column is-one-third">
          <aside class="menu">
            <div class="box">
              <p class="menu-label">Registration Form</p>
              <ul class="menu-list">
                <li><a href="#donor-tab" class="is-active">Donor</a></li>
                <li><a href="#organization-tab">Organization</a></li>
              </ul>
            </div>
            <hr class="menu-hr">
            <ul class="menu-list">
              <div class="box">
                <p class="is-size-1">Donor</p>
                <p>[do·nor] <strong>noun.</strong></p>
                <p style="font-family:'Times New Roman', Times, serif;">a person who donates something, especially money to a fund or charity.</p>
              </div>      
              <div class="box">
                <p class="is-size-1">Charity</p>
                <p>[char·i·ty] <strong>noun.</strong></p>
                <p style="font-family:'Times New Roman', Times, serif;">an organization set up to provide help and raise money for those in need.</p>
              </div>
            </ul>
          </aside>
        </div>

        <div class="column">

          <!-- DONOR -->
          <div id="donor-tab" class="content">
            <div class="box">
              <p class="subtitle">Register as Donor</p>

              <form method="POST" enctype="multipart/form-data">
                  <div class="field">
                    <div class="columns row-one">
                      <div class="column">
                        <label for="donorName" class="label">Name</label>
                        <div class="control">
                          <input type="text" class="input is-medium" placeholder="Name" name="donorName" autocomplete="off">
                        </div>
                      </div>
                      <div class="column">
                        <label for="donorEmail" class="label">Email Address</label>
                        <div class="control">
                          <input type="email" class="input is-medium" placeholder="Email Address" name="donorEmail" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field">
                    <div class="columns row-one">
                      <div class="column">
                        <label for="donorType" class="label">Donor Type</label>
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
                        <label for="donorPhone" class="label">Telephone Number</label>
                        <div class="control">
                          <input type="text" class="input is-medium" placeholder="Telephone Number" name="donorPhone" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field is-hidden" id="contactPersonDonor">
                    <div class="columns row-one">
                      <div class="column">
                        <label for="" class="label">Types of Charitable Organization</label>
                        <div class="control">
                          <div class="select is-medium">
                            <select name="donorOrgType" id="">
                              <option value="environment">Environmental Charity</option>
                              <option value="health">Health Charity</option>
                              <option value="religious">Religious Charity</option>
                              <option value="education">Education Charity</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="column">
                        <label for="donorContactName" class="label">Contact Person Name</label>
                        <div class="control">
                          <input type="text" class="input is-medium" placeholder="Contact Person Name" name="donorContactName" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field">
                    <label for="" class="label">Address</label>
                    <div class="control">
                      <input type="text" class="input is-medium" name="donorAddress" placeholder="Address" autocomplete="off">
                    </div>
                  </div>

                  <!-- TODO ADDING A DROPDOWN OF LIST OF IDS / PERMITS -->

                  <div class="field">
                    <label for="donorPermits" class="label">Registration Permits or Valid IDs</label>
                    <div class="control">
                      <input type="file" class="input is-medium" placeholder="Registration Permits or Valid IDs" name="donorPermit[]" multiple accept=".png, .jpg, .jpeg">
                    </div>
                  </div>

                  <div class="field">
                    <div class="columns">
                      <div class="column">
                        <label for="donorPass" class="label">Password</label>
                        <div class="control">
                          <input type="password" class="input is-medium" placeholder="Password" name="donorPass">
                        </div>
                      </div>
                      <div class="column">
                        <label for="donorConfirmPass" class="label">Confirm Password</label>
                        <div class="control">
                          <input type="password" class="input is-medium" placeholder="Confirm Password" name="donorConfirmPass">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field is-grouped">
                    <div class="control">
                      <button class="button is-link is-medium" type="submit" name="donorSubmit">Register</button>
                    </div>
                  </div>
              </form>

            </div>
          </div>

          <!-- CHARITY ORGANIZATION -->
          <div id="organization-tab" class="content is-hidden">
            <div class="box">
              <p class="subtitle">Register as Charitable Organization</p>

              <form method="POST" enctype="multipart/form-data">
                  <div class="field">
                    <div class="columns row-one">
                      <div class="column">
                        <label for="orgName" class="label">Organization Name</label>
                        <div class="control">
                          <input type="text" class="input is-medium" placeholder="Organization Name" name="orgName" autocomplete="off">
                        </div>
                      </div>
                      <div class="column">
                        <label for="orgContactName" class="label">Contact Person Name</label>
                        <div class="control">
                          <input type="text" class="input is-medium" placeholder="Person Name" name="orgContactName" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field">
                    <div class="columns row-one">
                      <div class="column">
                        <label for="orgEmail" class="label">Email Address</label>
                        <div class="control">
                          <input type="email" class="input is-medium" placeholder="Email Address" name="orgEmail" autocomplete="off">
                        </div>
                      </div>
                      <div class="column">
                        <label for="orgPhone" class="label">Telephone Number</label>
                        <div class="control">
                          <input type="text" class="input is-medium" placeholder="Telephone Number" name="orgPhone" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- TODO ADDING TYPES OF ORGANIZATION -->
                  
                  <div class="field">
                    <label for="orgAddress" class="label">Address</label>
                    <div class="control">
                      <input type="text" class="input is-medium" placeholder="Address" name="orgAddress" autocomplete="off">
                    </div>
                  </div>

                  <div class="field">
                    <div class="columns row-one">
                      <div class="column">
                        <label for="orgFoundingDate" class="label">Founding Date</label>
                        <div class="control">
                          <input type="date" class="input is-medium" placeholder="Founding Date" name="orgFoundingDate">
                        </div>
                      </div>
                      <div class="column">
                        <label for="" class="label">Type of Charitable Organization</label>
                        <div class="control">
                          <div class="select is-medium">
                            <select name="orgType" id="">
                              <option value="environment">Environmental Charity</option>
                              <option value="health">Health Charity</option>
                              <option value="religious">Religious Charity</option>
                              <option value="education">Education Charity</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- TODO ADDING A DROPDOWN OF LIST OF PERMITS -->

                  <div class="field">
                    <label for="orgPermit" class="label">Registration Permits</label>
                    <div class="control">
                      <input type="file" class="input is-medium" placeholder="Registration Permits" name="orgPermit[]" multiple accept=".png, .jpg, .jpeg">
                    </div>
                  </div>

                  <div class="field">
                    <div class="columns">
                      <div class="column">
                        <label for="orgPass" class="label">Password</label>
                        <div class="control">
                          <input type="password" class="input is-medium" placeholder="Password" name="orgPass">
                        </div>
                      </div>
                      <div class="column">
                        <label for="orgConfirmPass" class="label">Confirm Password</label>
                        <div class="control">
                          <input type="password" class="input is-medium" placeholder="Confirm Password" name="orgConfirmPass">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field is-grouped">
                    <div class="control">
                      <button class="button is-link is-medium" type="submit" name="orgSubmit">Register</button>
                    </div>
                  </div>
              </form>

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

      const typeSelect = document.getElementById('select_donortype');
      const contactPersonField = document.getElementById('contactPersonDonor');

      typeSelect.addEventListener('change', function() {
        if (typeSelect.value === 'Individual') {
          contactPersonField.classList.add('is-hidden');
        } else {
          contactPersonField.classList.remove('is-hidden');
        }
      });
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
