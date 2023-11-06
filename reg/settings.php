<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CharitEase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="icon" href="../lib/imgs/charitease_icon.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;700&display=swap">

    <style>
        body {
            font-family: 'Red Hat Display', sans-serif;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
    <?php
        if (!isset($_SESSION['user'])) {
            header('Location: ../index.php');
            exit;
        }

        include '../lib/database.php';

        $db = new Database();
        $conn = $db->connect();
        $id = $_SESSION['id'];
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
	
		$user = $db->query("SELECT * FROM `tblusers` WHERE user_id = '$id'");
		$client = $db->query("SELECT * FROM `tblclients` WHERE client_id = '$id'");

		if ($user && $client) {
			$array_user = $user->fetch_assoc();
			$array_client = $client->fetch_assoc();
		}
	?>

    <section class="section" style="background-color:#dee2e6;">
		<div class="container">
			<div class="columns">
				<div class="column is-one-fifth">
					<div class="box">
						<aside class="menu">
							<p class="menu-label">
								General
							</p>
							<ul class="menu-list">
								<li><a class="is-active" href="#profile-tab"><span class="icon-text material-symbols-outlined">account_circle</span>Profile</a></li>
                                <li><a href="#about-tab"><span class="icon-text material-symbols-outlined">info</span>About</a></li>
							</ul>
							<p class="menu-label">
								Account
							</p>
							<ul class="menu-list">
								<li><a href="#email-tab"><span class="icon-text material-symbols-outlined">mail</span>Email</a></li>
								<li><a href="#password-tab"><span class="icon-text material-symbols-outlined">lock</span>Password</a></li>
							</ul>
						</aside>
					</div>
					
				</div>
                <div class="column">
                    <div id="profile-tab" class="content">
                        <div class="box">
							<h2 class="title has-text-centered">Profile</h2>
							<hr>
							<div class="columns">
								<div class="column is-flex is-justify-content-center is-align-items-center">
									<figure class="image is-128x128">
										<?php
											$get_pic = $db->query("SELECT * FROM `tblimages` WHERE category = 'icon' AND client_id = '$id'");
											if ($get_pic && $get_pic->num_rows > 0) {
												$gett = $get_pic->fetch_assoc();
												$imageData = $gett['image_data'];
										?>
											<img class="is-round" src="data:image;base64,<?php echo $imageData ?>" alt="logo">
										<?php 
											} else {
										?>
											<img class="is-round" src="https://bulma.io/images/placeholders/128x128.png" alt="logo">
										<?php 
											}
										?>
									</figure>
								</div>

								<?php 
									$user_type = match ($array_user['account_type']) {
										'a' => "ADMIN",
										'c' => "CHARITY",
										'd' => "DONOR",
										default => ""
									};
									
									$group_type = match ($array_client['client_user_type']) {
										'i' => "Individual",
										'o' => "Organization",
										default => ""
									};

									$org_type = match ($array_client['client_org_type']) {
										'ed' => "Educational",
										're' => "Religious",
										'he' => "Health",
										'en' => "Environmental",
										default => ""
									};

									$gender = $array_user['gender'] == 'm' ? "Male" : "Female";
								?>

								<div class="column">
									<p>
										<strong>Name: </strong> <?= $array_client['client_name']; ?><br>
										<strong>Contact Name: </strong><?= $array_client['client_contact_name']; ?><br>
										<strong>Address: </strong> <?= $array_client['client_address']; ?><br>
                                        <strong>Gender: </strong> <?= $gender; ?><br>
                                        <strong>Birthday: </strong><?= $array_user['bday']; ?><br>
										<strong>Contact Number: </strong><?= $array_client['client_phone']; ?>
									</p>
									<p>
										<strong>Role Type: </strong> <?= $user_type; ?> <br>
										<?php if ($group_type !== "") { ?>
										<strong>User Type: </strong> <?= $group_type; ?> <br>
										<?php } ?>
										<?php if ($org_type !== "") { ?>
										<strong>Org Type: </strong> <?= $org_type; ?> <br>
										<?php } ?>
									</p>
								</div>
							</div>

                            <hr>

							<h2 class="title has-text-centered">Profile Settings</h2>
							<form action="action/a.settings.php" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="client_id" value="<?= $array_user['user_id']; ?>">
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
										<?php if ($array_user['account_type'] == 'd') { ?>
										<div class="column">
											<div class="field">
												<div class="control">
													<label for="" class="label">User Type</label>
													<div class="select">
														<select name="client_user_type" id="userList">
															<option value="i">Individual</option>
															<option value="o">Organization</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<?php } else { ?>
										<span class="column"></span>
										<?php } 
											if ($array_client['client_user_type'] == 'o' || $array_user['account_type'] == 'c') {
										?>
										<div class="column">
											<div class="field">
												<div class="control">
													<label for="" class="label" id="org_list">Org Type</label>
													<div class="select">
														<select name="client_org_type">
															<option value="en">Environmental Charity</option>
															<option value="he">Health Charity</option>
															<option value="re">Religious Charity</option>
															<option value="ed">Education Charity</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<?php } else { ?>
										<span class="column"></span>
										<?php } ?>
									</div>
								</div>

								<div class="field">
									<div class="columns">
                      					<div class="column">
											<label for="" class="label">Name</label>
											<div class="control">
												<input type="text" class="input" value="<?= $array_client['client_name']; ?>" name="client_name">
											</div>
										</div>
										<div class="column">
											<label for="" class="label">Contact Name</label>
											<div class="control">
												<input type="text" class="input" value="<?= $array_client['client_contact_name']; ?>" name="client_contact_name">
											</div>
										</div>
									</div>
								</div>

								<div class="field">
									<div class="columns">
										<div class="column">
											<label for="" class="label">Address</label>
											<div class="control">
												<input type="text" class="input" value="<?= $array_client['client_address']; ?>" name="client_address">
											</div>
										</div>
										<div class="column">
											<label for="" class="label">Contact Number</label>
											<div class="control">
												<input type="text" class="input" value="<?= $array_client['client_phone']; ?>" name="client_phone">
											</div>
										</div>
									</div>
								</div>								

								<button class="button is-info" type="submit" name="edit_submit">Submit</button>
							</form>
						</div>

                    </div>

                    <div id="about-tab" class="content is-hidden">
                        <div class="box">
                            <h2 class="subtitle">About <strong><?= $array_client['client_name']; ?></strong></h2>
                            <hr>
                            <p><?= $array_client['client_bio']; ?></p>
                            <hr>
                            <h2 class="title">Edit About</h2>
                            <form action="action/a.settings.php" method="POST">
								<input type="hidden" name="client_id" value="<?= $array_user['user_id']; ?>">
                                <div class="field">
                                    <label for="" class="label">Description</label>
                                    <div class="control">
                                    <textarea class="textarea" placeholder="type your description here" name="client_bio"><?= $array_client['client_bio']; ?></textarea>
                                    </div>
                                </div>
                                <button class="button is-info" type="submit" name="edit_bio_submit">Submit</button>
                            </form>
                        </div>
                    </div>

                    <div id="email-tab" class="content is-hidden">
                        <div class="box">
							<h2 class="title">Change Email</h2>
							<form action="action/a.settings.php" method="POST">
								<input type="hidden" name="client_id" value="<?= $array_user['user_id']; ?>">
								<input type="hidden" name="current_password" value="<?= $array_user['password']; ?>">
								<div class="field">
									<label for="" class="label">Email</label>
									<div class="control">
										<input type="text" class="input" placeholder="Type your current email" name="new_email" value="<?= $array_user['email']; ?>">
									</div>
								</div>
								<div class="field">
									<label for="" class="label">Current Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Type your current password" name="email_password">
									</div>
								</div>

								<button class="button is-info" type="submit" name="edit_email_submit">Submit</button>
							</form>
                        </div>
                    </div>
                    <div id="password-tab" class="content is-hidden">
                        <div class="box">
							<h2 class="title">Change Password</h2>
							<form action="action/a.settings.php" method="POST">

								<input type="hidden" name="client_id" value="<?= $array_user['user_id']; ?>">
								<input type="hidden" name="current_password" value="<?= $array_user['password']; ?>">
								<div class="field">
									<label for="" class="label">Current Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Type your current password" name="old_password">
									</div>
								</div>

								<div class="field">
									<label for="" class="label">New Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Type your new password" name="new_password">
									</div>
								</div>

								<div class="field">
									<label for="" class="label">Confirm New Password</label>
									<div class="control">
										<input type="password" class="input" placeholder="Confirm your new password" name="confirm_password">
									</div>
								</div>

								<button class="button is-info" type="submit" name="edit_pass_submit">Submit</button>
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