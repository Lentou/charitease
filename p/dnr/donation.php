<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../../lib/imgs/charitease_icon.png">

  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
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

  <!-- CONTENT HERE! -->
  <section class="hero is-info is-small">
    <div class="hero-body">
      <div class="container">
        <h1 class="title">Donate Charity Organization</h1>
      </div>
    </div>
  </section>

  <?php
    // Connect to database
    include '../../lib/database.php';

    $db = new Database();
		$conn = $db->connect();

    $org_id = $_GET['oid'];

    $getOrgText = "SELECT * FROM `tblorgs` WHERE org_id = '$org_id'";
    $resultGetOrg = mysqli_query($conn, $getOrgText);

    if (mysqli_num_rows($resultGetOrg) > 0) {
		  $org = mysqli_fetch_assoc($resultGetOrg);
		}
  ?>

  <section class="section">
    <div class="container">
      <!-- COLUMNS 1 START -->
      <div class="columns">
          <!-- COLUMN 1 START -->
          <div class="column">
            <div class="card">
              <div class="card-image">
                <figure class="image is-4by3">
                  <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image"/>
                </figure>
              </div>
              <div class="card-content">
                <h2 class="title is-4"><?php echo $org['org_name']; ?></h2>
                <p><?php echo $org['org_description']; ?></p>
              </div>
            </div>
          </div>
          <!-- COLUMN 1 END -->
          <!-- COLUMN 2 START -->
          <div class="column">
            <div id="googleMap" style="width:100%;height:500px;"></div>
            <script>
              function myMap() {
                var mapProp= {
                  center: new google.maps.LatLng(14.641684,120.481845),
                  zoom: 12,
                };
                var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
              }
            </script>
            <script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=myMap"></script>
          </div>
          <!-- COLUMN 2 END -->
      </div>
      <!-- COLUMNS 1 END -->
      <!-- COLUMNS 2 START -->
      <div class="columns">
        <!-- COLUMN 1 START -->
        <div class="column is-one-third">
          <aside class="menu">
            <p class="menu-label">Donation Area</p>
            <ul class="menu-list">
              <li><a href="#monetary-tab" class="is-active">Monetary</a></li>
              <li><a href="#inkind-tab">In-kind</a></li>
              <li><a href="#both-tab">Both Donate</a></li>
            </ul>
          </aside>
        </div>
        <!-- COLUMN 1 END -->
        <!-- COLUMN 2 START -->
        <div class="column">

              <!-- TODO ADDING GOAL EACH! THERE'S NO DONATION BUTTON OUTSIDE OF CHARITY -->
          <div id="monetary-tab" class="content">
            <div class="box">
              <h3 class="subtitle is-5 has-text-centered">Donate using Monetary</h3>
              <div class="columns">
                <div class="column is-flex is-justify-content-center is-align-items-center">

                
                <div class="card-content">
                  <form action="">

                    <div class="field">
                      <!-- todo: should real funds proceed to admin (if admin then admin will check and proceed to charity) 
                        (if charity then charity will check and accept completely the funds/values)? -->
                      <label for="" class="label">Payment Method</label>
                      <div class="control">
                        <div class="select">
                          <select name="payment">
                            <option>Select Payment Method</option>
                            <option value="gcash">GCash</option>
                            <option value="maya">Maya</option>
                            <option value="paypal">Paypal</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="field">
                      <label for="" class="label">Amount to Donate</label>
                      <div class="control">
                        <input class="input" type="number" placeholder="0.00">
                      </div>
                    </div>
                    <!--
                    <div class="field">
                     todo: selecting payment method will be change here 
                      <label class="label">Waller Number</label>
                      <div class="control">
                        <input class="input" type="text" placeholder="Your Wallet number">
                      </div>
                    </div>
                    -->
                    <div class="field">
                      <div class="control">
                        <button class="button is-link">Proceed to Donation</button>
                      </div>
                    </div>

                  </form>
                </div>
                </div>
              </div>
            </div>
          </div>

          <div id="inkind-tab" class="content is-hidden">
            <div class="box">
              <form action="">
                <h3 class="subtitle is-5 has-text-centered">In-kind Donation</h3>
                
                <div class="field">
                  <label for="" class="label">Item Name</label>
                  <div class="control">
                    <input type="text" class="input" placeholder="Item Name" name="item_name">
                  </div>
                </div>
                
                <div class="field">
                  <label for="" class="label">Description</label>
                  <div class="control">
                    <textarea class="textarea" placeholder="Enter a brief description of the item" name="description"></textarea>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Category</label>
                  <div class="control">
                    <div class="select">
                      <select name="category">
                        <option value="">Select a category</option>
                        <option value="edible">Edible</option>
                        <option value="non-edible">Non-Edible</option>
                        <option value="others">Others</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Quantity</label>
                  <div class="control">
                    <input class="input" type="number" min="1" name="quantity">
                  </div>
                </div>

                <!-- Pickup Location, Pickup Date -->
                <div class="field">
                  <label class="label">Deliver Date</label>
                  <div class="control">
                    <input class="input" type="date" name="pickup_date">
                  </div>
                </div>

                <!-- Contact Person, Contact Number, Contact Email -->
                <div class="field">
                  <label class="label">Image</label>
                  <div class="control">
                    <input class="input" type="file" name="image">
                  </div>
                </div>

                <div class="field">
                  <div class="control">
                    <button class="button is-link" type="submit">Donate</button>
                  </div>
                </div>

              </form>
            </div>
          </div>

          <div id="both-tab" class="content is-hidden">
            <div class="box">
              <form action="">
                <h3 class="subtitle is-5 has-text-centered">Monetary and In-kind Donation</h3>
                
                <div class="field">
                      <label for="" class="label">Payment Method</label>
                      <div class="control">
                        <div class="select">
                          <select name="payment">
                            <option>Select Payment Method</option>
                            <option value="gcash">GCash</option>
                            <option value="maya">Maya</option>
                            <option value="paypal">Paypal</option>
                            <option value="cc_dc">Credit /Debit Card</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="field">
                      <label for="" class="label">Amount to Donate</label>
                      <div class="control">
                        <input class="input" type="number" placeholder="0.00">
                      </div>
                    </div>
                    <!--
                    <div class="field">
                       todo: selecting payment method will be change here
                      <label class="label">Waller Number</label>
                      <div class="control">
                        <input class="input" type="text" placeholder="Your Wallet number">
                      </div>
                    </div>
                    -->
                <hr>
                <div class="field">
                  <label for="" class="label">Item Name</label>
                  <div class="control">
                    <input type="text" class="input" placeholder="Item Name" name="item_name">
                  </div>
                </div>
                
                <div class="field">
                  <label for="" class="label">Description</label>
                  <div class="control">
                    <textarea class="textarea" placeholder="Enter a brief description of the item" name="description"></textarea>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Category</label>
                  <div class="control">
                    <div class="select">
                      <select name="category">
                        <option value="">Select a category</option>
                        <option value="edible">Edible</option>
                        <option value="non-edible">Non-Edible</option>
                        <option value="others">Others</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Quantity</label>
                  <div class="control">
                    <input class="input" type="number" min="1" name="quantity">
                  </div>
                </div>

                <!-- Pickup Location, Pickup Date -->
                <div class="field">
                  <label class="label">Deliver Date</label>
                  <div class="control">
                    <input class="input" type="date" name="pickup_date">
                  </div>
                </div>

                <!-- Contact Person, Contact Number, Contact Email -->
                <div class="field">
                  <label class="label">Image</label>
                  <div class="control">
                    <input class="input" type="file" name="image">
                  </div>
                </div>

                <div class="field">
                  <div class="control">
                    <button class="button is-link" type="submit">Donate</button>
                  </div>
                </div>

              </form>
            </div>
          </div>

        </div>
        <!-- COLUMN 2 END -->
      </div>
      <!-- COLUMNS 2 END -->
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